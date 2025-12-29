<?php

use MartinCamen\PhpFileSize\Enums\ByteBase;
use MartinCamen\PhpFileSize\Enums\ConfigurationOption;
use MartinCamen\PhpFileSize\FileSize;

it('converts megabytes to kilobytes with binary base as default', function (): void {
    expect((new FileSize())->megabytes(2)->toKilobytes())
        ->toBe(2048.0);
});

it('converts megabytes to kilobytes with explicit binary base', function (): void {
    expect((new FileSize())->megabytes(2, [ConfigurationOption::ByteBase->value => ByteBase::Binary])->toKilobytes())
        ->toBe(2048.0);
});

it('converts megabytes to kilobytes with decimal base', function (): void {
    expect((new FileSize())->megabytes(2, [ConfigurationOption::ByteBase->value => ByteBase::Decimal])->toKilobytes())
        ->toBe(2000.0);
});

it('converts kilobytes to gigabytes with precision', function (): void {
    expect((new FileSize())->kilobytes(2048, [ConfigurationOption::ByteBase->value => ByteBase::Decimal])->precision(6)->toGigabytes())
        ->toBe(0.002048);
});

it('handles singular forms with binary base', function (): void {
    expect((new FileSize())->megabyte()->toKilobytes())
        ->toBe(1024.0);
});

it('handles singular forms with decimal base', function (): void {
    expect((new FileSize())->megabyte([ConfigurationOption::ByteBase->value => ByteBase::Decimal])->toKilobytes())
        ->toBe(1000.0);
});

it('chains arithmetic operations with binary base', function (): void {
    $result = (new FileSize())->megabytes(2)
        ->subKilobytes(22)
        ->addKilobytes(8)
        ->toKilobytes();

    expect($result)->toBe(2034.0);
});

it('chains arithmetic operations with decimal base', function (): void {
    $result = (new FileSize())->megabytes(2, [ConfigurationOption::ByteBase->value => ByteBase::Decimal])
        ->subKilobytes(22)
        ->addKilobytes(8)
        ->toKilobytes();

    expect($result)->toBe(1986.0);
});

it('formats for humans with binary base as default', function (): void {
    expect((new FileSize())->megabytes(1.5)->forHumans())
        ->toBe('1.50 Mebibytes');
});

it('formats for humans with binary base', function (): void {
    expect((new FileSize())->megabytes(1.5, [ConfigurationOption::ByteBase->value => ByteBase::Binary])->forHumans())
        ->toBe('1.50 Mebibytes');
});

it('formats for humans with decimal base', function (): void {
    expect((new FileSize())->megabytes(1.5, [ConfigurationOption::ByteBase->value => ByteBase::Decimal])->forHumans())
        ->toBe('1.50 Megabytes');
});
