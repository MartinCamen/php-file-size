<?php

declare(strict_types=1);

use MartinCamen\PhpFileSize\Enums\ConfigurationOption;
use MartinCamen\PhpFileSize\Enums\Unit;
use MartinCamen\PhpFileSize\FileSize;

describe('precision handling', function (): void {
    it('uses default precision from config', function (): void {
        $size = (new FileSize(options: [ConfigurationOption::Precision->value => 2]))->kilobytes(1536);

        expect($size->toMegabytes())->toBe(1.5);
    });

    it('can set precision fluently', function (): void {
        $size = (new FileSize())->kilobytes(1536)->precision(4);

        expect($size->getPrecision())->toBe(4);
    });

    it('preserves original when setting precision', function (): void {
        $original = (new FileSize())->megabytes(1);
        $withPrecision = $original->precision(4);

        expect($original->getPrecision())->toBe(2);
        expect($withPrecision->getPrecision())->toBe(4);
    });

    it('applies precision to conversion methods', function (): void {
        $size = (new FileSize())->bytes(1234567);

        expect($size->toKilobytes(0))->toBe(1206.0);
        expect($size->toKilobytes(2))->toBe(1205.63);
        expect($size->toKilobytes(4))->toBe(1205.6318);
    });

    it('applies fluent precision to conversion', function (): void {
        $size = (new FileSize())->bytes(1234567)->precision(0);

        expect($size->toKilobytes())->toBe(1206.0);
    });

    it('method precision overrides fluent precision', function (): void {
        $size = (new FileSize())->bytes(1234567)->precision(0);

        expect($size->toKilobytes(4))->toBe(1205.6318);
    });

    it('uses precision in formatting', function (): void {
        $size = (new FileSize())->megabytes(1.23456);

        expect($size->precision(0)->forHumans())->toBe('1 Mebibytes');
        expect($size->precision(1)->forHumans())->toBe('1.2 Mebibytes');
        expect($size->precision(3)->forHumans())->toBe('1.235 Mebibytes');
    });

    it('uses precision in comparisons', function (): void {
        $size = (new FileSize())->kilobytes(1); // 1024 bytes

        // Exact match
        expect($size->equals(1024, Unit::Byte, [ConfigurationOption::Precision->value => 0]))->toBeTrue();

        // With precision, values are rounded before comparison
        $sizeWithDecimals = (new FileSize())->bytes(1536); // 1.5 KB
        expect($sizeWithDecimals->equals(1.5, Unit::KiloByte, [ConfigurationOption::Precision->value => 2]))->toBeTrue();
    });
});

describe('magic property access with precision', function (): void {
    it('returns value via magic __get', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->kilobytes)->toBe(2048.0);
        expect($size->bytes)->toBe(2097152.0);
    });

    it('applies fluent precision to magic __get', function (): void {
        $size = (new FileSize())->bytes(1234567)->precision(0);

        expect($size->kilobytes)->toBe(1206.0);
    });
});
