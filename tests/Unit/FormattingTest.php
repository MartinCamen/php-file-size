<?php

declare(strict_types=1);

use MartinCamen\PhpFileSize\Enums\ByteBase;
use MartinCamen\PhpFileSize\Enums\ConfigurationOption;
use MartinCamen\PhpFileSize\FileSize;

describe('forHumans', function (): void {
    it('formats bytes', function (): void {
        $size = (new FileSize())->bytes(512);

        expect($size->forHumans())->toBe('512.00 Bytes');
    });

    it('formats kilobytes', function (): void {
        $size = (new FileSize())->kilobytes(1.5);

        expect($size->forHumans())->toBe('1.50 Kibibytes');
    });

    it('formats megabytes', function (): void {
        $size = (new FileSize())->megabytes(1.5);

        expect($size->forHumans())->toBe('1.50 Mebibytes');
    });

    it('formats gigabytes', function (): void {
        $size = (new FileSize())->gigabytes(2.5);

        expect($size->forHumans())->toBe('2.50 Gibibytes');
    });

    it('formats terabytes', function (): void {
        $size = (new FileSize())->terabytes(1.25);

        expect($size->forHumans())->toBe('1.25 Tebibytes');
    });

    it('formats petabytes', function (): void {
        $size = (new FileSize())->petabytes(1.75);

        expect($size->forHumans())->toBe('1.75 Pebibytes');
    });

    it('uses short labels', function (): void {
        $size = (new FileSize())->megabytes(1.5);

        expect($size->forHumans(short: true))->toBe('1.50 MiB');
    });

    it('uses custom precision', function (): void {
        $size = (new FileSize())->megabytes(1.5678);

        expect($size->forHumans(options: [ConfigurationOption::Precision->value => 3]))->toBe('1.568 Mebibytes');
    });

    it('uses decimal labels for decimal base', function (): void {
        $size = (new FileSize())->megabytes(1.5, [ConfigurationOption::ByteBase->value => ByteBase::Decimal]);

        expect($size->forHumans())->toBe('1.50 Megabytes');
    });

    it('uses short decimal labels', function (): void {
        $size = (new FileSize())->megabytes(1.5, [ConfigurationOption::ByteBase->value => ByteBase::Decimal]);

        expect($size->forHumans(short: true))->toBe('1.50 MB');
    });

    it('accepts binary label byte base for binary byte base', function (): void {
        $size = (new FileSize())->megabytes(2.52);

        expect($size->forHumans(options: [ConfigurationOption::LabelStyle->value => ByteBase::Binary]))->toBe('2.52 Mebibytes');
    });

    it('accepts decimal label byte base for binary byte base', function (): void {
        $size = (new FileSize())->megabytes(2.52);

        expect($size->forHumans(options: [ConfigurationOption::LabelStyle->value => ByteBase::Decimal]))->toBe('2.52 Megabytes');
    });

    it('accepts binary label byte base for decimal byte base', function (): void {
        $size = (new FileSize())->megabytes(2.52, [ConfigurationOption::ByteBase->value => ByteBase::Decimal]);

        expect($size->forHumans(options: [ConfigurationOption::LabelStyle->value => ByteBase::Binary]))->toBe('2.52 Mebibytes');
    });

    it('accepts decimal label byte base for decimal byte base', function (): void {
        $size = (new FileSize())->megabytes(2.52, [ConfigurationOption::ByteBase->value => ByteBase::Decimal]);

        expect($size->forHumans(options: [ConfigurationOption::LabelStyle->value => ByteBase::Decimal]))->toBe('2.52 Megabytes');
    });
});

describe('format alias', function (): void {
    it('formats with long labels', function (): void {
        $size = (new FileSize())->megabytes(2.5);

        expect($size->format())->toBe('2.50 Mebibytes');
    });

    it('accepts precision parameter', function (): void {
        $size = (new FileSize())->megabytes(2.567);

        expect($size->format(options: [ConfigurationOption::Precision->value => 1]))->toBe('2.6 Mebibytes');
    });

    it('accepts binary label byte base for binary byte base', function (): void {
        $size = (new FileSize())->megabytes(2.52);

        expect($size->format(options: [ConfigurationOption::LabelStyle->value => ByteBase::Binary->value]))->toBe('2.52 Mebibytes');
    });

    it('accepts decimal label byte base for binary byte base', function (): void {
        $size = (new FileSize())->megabytes(2.52);

        expect($size->format(options: [ConfigurationOption::LabelStyle->value => ByteBase::Decimal->value]))->toBe('2.52 Megabytes');
    });

    it('accepts binary label byte base for decimal byte base', function (): void {
        $size = (new FileSize())->megabytes(2.52, [ConfigurationOption::ByteBase->value => ByteBase::Decimal]);

        expect($size->format(options: [ConfigurationOption::LabelStyle->value => ByteBase::Binary->value]))->toBe('2.52 Mebibytes');
    });

    it('accepts decimal label byte base for decimal byte base', function (): void {
        $size = (new FileSize())->megabytes(2.52, [ConfigurationOption::ByteBase->value => ByteBase::Decimal]);

        expect($size->format(options: [ConfigurationOption::LabelStyle->value => ByteBase::Decimal->value]))->toBe('2.52 Megabytes');
    });
});

describe('formatShort alias', function (): void {
    it('formats with short labels', function (): void {
        $size = (new FileSize())->megabytes(2.5);

        expect($size->formatShort())->toBe('2.50 MiB');
    });

    it('accepts precision parameter', function (): void {
        $size = (new FileSize())->megabytes(2.567);

        expect($size->formatShort(options: [ConfigurationOption::Precision->value => 1]))->toBe('2.6 MiB');
    });
});

describe('best unit selection', function (): void {
    it('selects bytes for small values', function (): void {
        $size = (new FileSize())->bytes(512);

        expect($size->forHumans())->toContain('Bytes');
    });

    it('selects kilobytes for KB range', function (): void {
        $size = (new FileSize())->bytes(2048);

        expect($size->forHumans())->toContain('Kibibytes');
    });

    it('selects megabytes for MB range', function (): void {
        $size = (new FileSize())->kilobytes(2048);

        expect($size->forHumans())->toContain('Mebibytes');
    });

    it('selects gigabytes for GB range', function (): void {
        $size = (new FileSize())->megabytes(2048);

        expect($size->forHumans())->toContain('Gibibytes');
    });

    it('selects terabytes for TB range', function (): void {
        $size = (new FileSize())->gigabytes(2048);

        expect($size->forHumans())->toContain('Tebibytes');
    });

    it('selects petabytes for PB range', function (): void {
        $size = (new FileSize())->terabytes(2048);

        expect($size->forHumans())->toContain('Pebibytes');
    });
});

describe('configuration options', function (): void {
    it('uses configured decimal separator', function (): void {
        $size = (new FileSize(options: [ConfigurationOption::DecimalSeparator->value => ',']))->megabytes(1.5);

        expect($size->forHumans())->toBe('1,50 Mebibytes');
    });

    it('uses configured thousands separator', function (): void {
        $size = (new FileSize(options: [ConfigurationOption::ThousandsSeparator->value => ' ']))->bytes(1500000);

        expect($size->forHumans())->toContain(' ');
    });

    it('respects space between value and unit setting', function (): void {
        $size = (new FileSize(options: [ConfigurationOption::SpaceBetweenValueAndUnit->value => false]))->megabytes(1.5);

        expect($size->forHumans())->toBe('1.50Mebibytes');
    });
});

describe('label style configuration', function (): void {
    it('follows byte base when label_style is null', function (): void {
        $fileSize = new FileSize(options: [ConfigurationOption::LabelStyle->value => null]);

        $binarySize = $fileSize->megabytes(1.5, [ConfigurationOption::ByteBase->value => ByteBase::Binary]);
        $decimalSize = $fileSize->megabytes(1.5, [ConfigurationOption::ByteBase->value => ByteBase::Decimal]);

        expect($binarySize->forHumans())->toBe('1.50 Mebibytes');
        expect($decimalSize->forHumans())->toBe('1.50 Megabytes');

    });

    it('uses decimal labels with binary calculations when label_style is decimal', function (): void {
        // Binary calculations (1024-based) but with decimal labels
        $size = (new FileSize())->megabytes(1.5, [
            ConfigurationOption::ByteBase->value   => ByteBase::Binary,
            ConfigurationOption::LabelStyle->value => ByteBase::Decimal,
        ]);

        expect($size->forHumans())->toBe('1.50 Megabytes');
        expect($size->forHumans(short: true))->toBe('1.50 MB');
        // Verify calculation is still binary (1.5 MB = 1.5 * 1024 * 1024 bytes)
        expect($size->toBytes())->toBe(1572864.0);
    });

    it('uses binary labels with decimal calculations when label_style is binary', function (): void {
        // Decimal calculations (1000-based) but with binary labels
        $size = (new FileSize())->megabytes(1.5, [
            ConfigurationOption::ByteBase->value   => ByteBase::Decimal->value,
            ConfigurationOption::LabelStyle->value => ByteBase::Binary->value,
        ]);

        expect($size->forHumans())->toBe('1.50 Mebibytes');
        expect($size->forHumans(short: true))->toBe('1.50 MiB');
        // Verify calculation is still decimal (1.5 MB = 1.5 * 1000 * 1000 bytes)
        expect($size->toBytes())->toBe(1500000.0);
    });

    it('applies label_style to short format', function (): void {
        $size = (new FileSize())->gigabytes(2, [
            ConfigurationOption::LabelStyle->value => ByteBase::Decimal->value,
        ]);

        expect($size->formatShort())->toBe('2.00 GB');
    });

    it('applies label_style across all unit sizes', function (): void {
        $fileSize = new FileSize(options: [
            ConfigurationOption::LabelStyle->value => ByteBase::Decimal->value,
        ]);

        expect($fileSize->bytes(512)->forHumans())->toBe('512.00 Bytes');
        expect($fileSize->kilobytes(1.5)->forHumans())->toBe('1.50 Kilobytes');
        expect($fileSize->megabytes(1.5)->forHumans())->toBe('1.50 Megabytes');
        expect($fileSize->gigabytes(1.5)->forHumans())->toBe('1.50 Gigabytes');
        expect($fileSize->terabytes(1.5)->forHumans())->toBe('1.50 Terabytes');
        expect($fileSize->petabytes(1.5)->forHumans())->toBe('1.50 Petabytes');
    });
});
