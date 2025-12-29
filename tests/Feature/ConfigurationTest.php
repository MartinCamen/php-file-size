<?php

declare(strict_types=1);

namespace MartinCamen\PhpFileSize\Tests\Feature;

use MartinCamen\PhpFileSize\Configuration\FileSizeOptions;
use MartinCamen\PhpFileSize\Enums\ByteBase;

it('loads configuration from config file', function (): void {
    $config = new FileSizeOptions();

    expect($config->precision)->toBe(2);
    expect($config->decimalSeparator)->toBe('.');
    expect($config->thousandsSeparator)->toBe(',');
    expect($config->spaceBetweenValueAndUnit)->toBe(true);
    expect($config->validationThrowOnNegativeResult)->toBe(false);
    expect($config->validationAllowNegativeInput)->toBe(false);
});

it('resolves byte base from configuration', function (): void {
    $config = new FileSizeOptions();

    expect($config->byteBase())->toBeInstanceOf(ByteBase::class);
});

it('can override configuration values', function (): void {
    $config = new FileSizeOptions(precision: 4);

    expect($config->precision)->toBe(4);
});
