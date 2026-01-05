<?php

namespace MartinCamen\PhpFileSize\Concerns;

trait HandlesSyntheticInitiation
{
    /** @param array<string, mixed> $options */
    public static function fromBytes(int|float $value, array $options = []): static
    {
        return static::initiate($options)->bytes($value);
    }

    /** @param array<string, mixed> $options */
    public static function fromKilobytes(int|float $value, array $options = []): static
    {
        return static::initiate($options)->kilobytes($value);
    }

    /** @param array<string, mixed> $options */
    public static function fromMegabytes(int|float $value, array $options = []): static
    {
        return static::initiate($options)->megabytes($value);
    }

    /** @param array<string, mixed> $options */
    public static function fromGigabytes(int|float $value, array $options = []): static
    {
        return static::initiate($options)->gigabytes($value);
    }

    /** @param array<string, mixed> $options */
    public static function fromTerabytes(int|float $value, array $options = []): static
    {
        return static::initiate($options)->terabytes($value);
    }

    /** @param array<string, mixed> $options */
    public static function fromPetabytes(int|float $value, array $options = []): static
    {
        return static::initiate($options)->petabytes($value);
    }

    /** @param array<string, mixed> $options */
    public static function zero(array $options = []): static
    {
        return static::initiate($options)->bytes(0);
    }

    /** @param array<string, mixed> $options */
    private static function initiate(array $options = []): static
    {
        return new static(options: $options);
    }
}
