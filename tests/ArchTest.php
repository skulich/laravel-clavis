<?php

arch()->preset()->php();
arch()->preset()->security();

arch()->expect(['die', 'dd', 'dump'])->not->toBeUsed();

arch()
    ->expect('SKulich\LaravelClavis')
    ->classes()
    ->not->toBeAbstract()
    ->toBeFinal()
    ->toUseStrictTypes()
    ->toUseStrictEquality();
