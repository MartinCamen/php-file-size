<?php

declare(strict_types=1);

use MartinCamen\PhpFileSize\Enums\ByteBase;
use MartinCamen\PhpFileSize\Enums\ConfigurationOption;
use MartinCamen\PhpFileSize\FileSize;

describe('ByteBase enum', function (): void {
    it('has binary multiplier of 1024', function (): void {
        expect(ByteBase::Binary->multiplier())->toBe(1024.0);
    });

    it('has decimal multiplier of 1000', function (): void {
        expect(ByteBase::Decimal->multiplier())->toBe(1000.0);
    });

    it('multiplies correctly for binary', function (): void {
        expect(ByteBase::Binary->multiply(2))->toBe(1048576.0); // 1024^2
    });

    it('multiplies correctly for decimal', function (): void {
        expect(ByteBase::Decimal->multiply(2))->toBe(1000000.0); // 1000^2
    });

    it('has default value', function (): void {
        expect(ByteBase::default())->toBe(ByteBase::Binary);
    });
});

describe('ByteBase switching', function (): void {
    it('converts correctly with binary base', function (): void {
        $size = (new FileSize())->megabytes(1, [ConfigurationOption::ByteBase->value => ByteBase::Binary]);

        expect($size->toKilobytes())->toBe(1024.0);
        expect($size->toBytes())->toBe(1048576.0);
    });

    it('converts correctly with decimal base', function (): void {
        $size = (new FileSize())->megabytes(1, [ConfigurationOption::ByteBase->value => ByteBase::Decimal]);

        expect($size->toKilobytes())->toBe(1000.0);
        expect($size->toBytes())->toBe(1000000.0);
    });

    it('can change byte base fluently', function (): void {
        $size = (new FileSize())->megabytes(1, [ConfigurationOption::ByteBase->value => ByteBase::Binary])
            ->byteBase(ByteBase::Decimal);

        expect($size->getByteBase())->toBe(ByteBase::Decimal);
    });

    it('preserves original when changing byte base', function (): void {
        $original = (new FileSize())->megabytes(1, [ConfigurationOption::ByteBase->value => ByteBase::Binary]);
        $changed = $original->byteBase(ByteBase::Decimal);

        expect($original->getByteBase())->toBe(ByteBase::Binary);
        expect($changed->getByteBase())->toBe(ByteBase::Decimal);
    });
});

describe('labels with different byte bases', function (): void {
    it('uses binary labels for binary base', function (): void {
        $size = (new FileSize())->megabytes(1, [ConfigurationOption::ByteBase->value => ByteBase::Binary]);

        expect($size->forHumans())->toContain('Mebibytes');
        expect($size->formatShort())->toContain('MiB');
    });

    it('uses decimal labels for decimal base', function (): void {
        $size = (new FileSize())->megabytes(1, [ConfigurationOption::ByteBase->value => ByteBase::Decimal]);

        expect($size->forHumans())->toContain('Megabytes');
        expect($size->formatShort())->toContain('MB');
    });
});
