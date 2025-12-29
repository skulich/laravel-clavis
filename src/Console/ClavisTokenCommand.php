<?php

declare(strict_types=1);

namespace SKulich\LaravelClavis\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class ClavisTokenCommand extends Command
{
    use ConfirmableTrait;

    /**
     * @var string
     */
    protected $signature = 'clavis:token
                            {--force : Force the operation to run when in production}';

    /**
     * @var string
     */
    protected $description = 'Generates Clavis token';

    public function handle(): int
    {
        $token = $this->generateToken();
        $hash = $this->hashToken($token);

        if (! $this->setHashInEnvironmentFile($hash)) {
            $this->error('Unable to set clavis token.');

            return Command::FAILURE;
        }

        config(['clavis.hash' => $hash]);

        $this->info('Clavis token hash set successfully.');

        $this->comment('Token: '.$token);

        return Command::SUCCESS;
    }

    /**
     * @return non-empty-string
     */
    protected function generateToken(): string
    {
        /** @var non-empty-string $key */
        $key = Str::random(32);

        return $key;
    }

    /**
     * @param  non-empty-string  $key
     * @return non-empty-string
     */
    protected function hashToken(string $key): string
    {
        /** @var non-empty-string $key */
        $key = base64_encode(Hash::make($key));

        return $key;
    }

    /**
     * @param  non-empty-string  $hash
     */
    protected function setHashInEnvironmentFile(string $hash): bool
    {
        if (! $this->confirmToProceed(callback: fn () => app()->environment(['testing', 'production']))) {
            return false;
        }

        return $this->writeNewEnvironmentFileWith($hash);
    }

    /**
     * @param  non-empty-string  $hash
     */
    protected function writeNewEnvironmentFileWith(string $hash): bool
    {
        $input = File::get(app()->environmentFilePath());

        $pattern = $this->keyReplacementPattern();
        $replacement = 'CLAVIS_HASH='.$hash;

        if (! is_null($pattern) && preg_match($pattern, $input)) {
            $replaced = preg_replace($pattern, $replacement, $input);

            // @codeCoverageIgnoreStart
            if ($replaced === $input || $replaced === null) {
                $this->error('Unable to set Clavis hash.');

                return false;
            }
            // @codeCoverageIgnoreEnd
        } else {
            $replaced = $input.PHP_EOL.$replacement;
        }

        File::put(app()->environmentFilePath(), $replaced);

        return true;
    }

    /**
     * @return non-empty-string|null
     */
    protected function keyReplacementPattern(): ?string
    {
        $key = config('clavis.hash');

        if (! is_string($key)) {
            return null;
        }

        $escaped = preg_quote('='.$key, '/');

        return "/^CLAVIS_HASH{$escaped}/m";
    }
}
