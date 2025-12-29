<?php

declare(strict_types=1);

use MartinCamen\PhpFileSize\Enums\ConfigurationOption;
use MartinCamen\PhpFileSize\Exceptions\InvalidValueException;
use MartinCamen\PhpFileSize\Exceptions\NegativeValueException;
use MartinCamen\PhpFileSize\FileSize;

describe('negative input validation', function (): void {
    it('throws on negative input by default', function (): void {
        (new FileSize())->megabytes(-5);
    })->throws(NegativeValueException::class, 'Negative values are not allowed');

    it('allows negative input when configured', function (): void {
        $size = (new FileSize())->megabytes(-5, [ConfigurationOption::ValidationAllowNegativeInput->value => true]);

        expect($size->toMegabytes())->toBe(-5.0);
    });
});

describe('invalid value validation', function (): void {
    it('throws on infinity', function (): void {
        (new FileSize())->bytes(INF);
    })->throws(InvalidValueException::class, 'Value must be a finite number');

    it('throws on negative infinity', function (): void {
        (new FileSize())->bytes(-INF, [ConfigurationOption::ValidationAllowNegativeInput->value => true]);
    })->throws(InvalidValueException::class);

    it('throws on NaN', function (): void {
        (new FileSize())->bytes(NAN);
    })->throws(InvalidValueException::class, 'Value must be a finite number');
});

describe('divide by zero', function (): void {
    it('throws when dividing by zero integer', function (): void {
        (new FileSize())->megabytes(100)->divide(0);
    })->throws(InvalidValueException::class, 'Cannot divide by zero');

    it('throws when dividing by zero float', function (): void {
        (new FileSize())->megabytes(100)->divide(0.0);
    })->throws(InvalidValueException::class, 'Cannot divide by zero');
});

describe('property access validation', function (): void {
    it('throws on unknown property', function (): void {
        $size = (new FileSize())->megabytes(5);
        $size->unknownProperty;
    })->throws(InvalidValueException::class, 'Unknown property');
});
