<?php

namespace MartinCamen\PhpFileSize\Concerns;

use MartinCamen\PhpFileSize\Configuration\FileSizeOptions;

/** @phpstan-import-type OptionalFileSizeOptionsType from FileSizeOptions */
trait HandlesSyntheticInitiation
{
    /** @param OptionalFileSizeOptionsType $options */
    public static function fromBytes(int|float $value, array $options = []): static
    {
        return static::initiate($options)->bytes($value);
    }

    /** @param OptionalFileSizeOptionsType $options */
    public static function fromKilobytes(int|float $value, array $options = []): static
    {
        return static::initiate($options)->kilobytes($value);
    }

    /** @param OptionalFileSizeOptionsType $options */
    public static function fromMegabytes(int|float $value, array $options = []): static
    {
        return static::initiate($options)->megabytes($value);
    }

    /** @param OptionalFileSizeOptionsType $options */
    public static function fromGigabytes(int|float $value, array $options = []): static
    {
        return static::initiate($options)->gigabytes($value);
    }

    /** @param OptionalFileSizeOptionsType $options */
    public static function fromTerabytes(int|float $value, array $options = []): static
    {
        return static::initiate($options)->terabytes($value);
    }

    /** @param OptionalFileSizeOptionsType $options */
    public static function fromPetabytes(int|float $value, array $options = []): static
    {
        return static::initiate($options)->petabytes($value);
    }

    /** @param OptionalFileSizeOptionsType $options */
    public static function zero(array $options = []): static
    {
        return static::initiate($options)->bytes(0);
    }

    /** @param OptionalFileSizeOptionsType $options */
    protected static function initiate(array $options = []): static
    {
        return new static(options: $options);
    }
}
