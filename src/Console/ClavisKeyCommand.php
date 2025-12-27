<?php

declare(strict_types=1);

namespace SKulich\LaravelClavis\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class ClavisKeyCommand extends Command
{
    use ConfirmableTrait;

    /**
     * @var string
     */
    protected $signature = 'clavis:key
                            {--force : Force the operation to run when in production}';

    /**
     * @var string
     */
    protected $description = 'Generates Clavis key';

    public function handle(): int
    {
        $key = $this->generateRandomKey();

        if (! $this->setKeyInEnvironmentFile($key)) {
            $this->error('Unable to set clavis key.');

            return Command::FAILURE;
        }

        config(['clavis.key' => $key]);

        $this->info('Clavis key set successfully.');

        $this->info($key);

        return Command::SUCCESS;
    }

    /**
     * @return non-empty-string
     */
    protected function generateRandomKey(): string
    {
        /** @var non-empty-string $key */
        $key = base64_encode(Hash::make(Str::random(42)));

        return $key;
    }

    /**
     * @param  non-empty-string  $key
     */
    protected function setKeyInEnvironmentFile(string $key): bool
    {
        if (! $this->confirmToProceed(callback: fn () => app()->environment(['testing', 'production']))) {
            return false;
        }

        return $this->writeNewEnvironmentFileWith($key);
    }

    /**
     * @param  non-empty-string  $key
     */
    protected function writeNewEnvironmentFileWith(string $key): bool
    {
        $input = File::get(app()->environmentFilePath());

        $pattern = $this->keyReplacementPattern();
        $replacement = 'CLAVIS_KEY='.$key;

        if (! is_null($pattern) && preg_match($pattern, $input)) {
            $replaced = preg_replace($pattern, $replacement, $input);

            // @codeCoverageIgnoreStart
            if ($replaced === $input || $replaced === null) {
                $this->error('Unable to set Clavis key.');

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
        $key = config('clavis.key');

        if (! is_string($key)) {
            return null;
        }

        $escaped = preg_quote('='.$key, '/');

        return "/^CLAVIS_KEY{$escaped}/m";
    }
}
