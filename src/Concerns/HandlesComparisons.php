<?php

namespace MartinCamen\PhpFileSize\Concerns;

use MartinCamen\PhpFileSize\Enums\Unit;
use MartinCamen\PhpFileSize\FileSize;

trait HandlesComparisons
{
    /** @param array<string, mixed> $options */
    public function equals(int|float $value, Unit $unit, array $options = []): bool
    {
        return $this->compare($value, $unit, $options) === 0;
    }

    /** @param array<string, mixed> $options */
    public function notEquals(int|float $value, Unit $unit, array $options = []): bool
    {
        return ! $this->equals($value, $unit, $options);
    }

    /** @param array<string, mixed> $options */
    public function greaterThan(int|float $value, Unit $unit, array $options = []): bool
    {
        return $this->compare($value, $unit, $options) > 0;
    }

    /** @param array<string, mixed> $options */
    public function greaterThanOrEqual(int|float $value, Unit $unit, array $options = []): bool
    {
        return $this->compare($value, $unit, $options) >= 0;
    }

    /** @param array<string, mixed> $options */
    public function lessThan(int|float $value, Unit $unit, array $options = []): bool
    {
        return ! $this->greaterThanOrEqual($value, $unit, $options);
    }

    /** @param array<string, mixed> $options */
    public function lessThanOrEqual(int|float $value, Unit $unit, array $options = []): bool
    {
        return $this->compare($value, $unit, $options) <= 0;
    }

    /** @param array<string, mixed> $options */
    public function between(int|float $min, int|float $max, Unit $unit, array $options = []): bool
    {
        return $this->greaterThanOrEqual($min, $unit, $options)
            && $this->lessThanOrEqual($max, $unit, $options);
    }

    public function min(FileSize $other): self
    {
        return $this->resolveBytes() <= $other->getBytes() ? $this : $other;
    }

    public function max(FileSize $other): self
    {
        return $this->resolveBytes() >= $other->getBytes() ? $this : $other;
    }

    /** @param array<string, mixed> $options */
    public function isZero(array $options = []): bool
    {
        return $this->equals(0, Unit::Byte, $options);
    }

    public function isPositive(): bool
    {
        return $this->resolveBytes() > 0;
    }

    public function isNegative(): bool
    {
        return ! $this->isPositive();
    }

    /** @param array<string, mixed> $options */
    private function compare(int|float $value, Unit $unit, array $options = []): int
    {
        $this->mergeOptions($options);

        $bytes = $this->resolveBytes();
        $thisValue = round($bytes, $this->options->precision);
        $compareValue = round($unit->toBytes($value, $this->options), $this->options->precision);

        return $thisValue <=> $compareValue;
    }
}
