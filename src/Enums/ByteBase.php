<?php

namespace MartinCamen\PhpFileSize\Enums;

enum ByteBase: string
{
    case Binary = 'binary';  // IEC standard
    case Decimal = 'decimal'; // SI standard

    public static function default(): self
    {
        return self::Binary;
    }

    public function multiplier(): float
    {
        return match ($this) {
            self::Binary  => 1024,
            self::Decimal => 1000,
        };
    }

    public function multiply(int $exponent): float
    {
        return $this->multiplier() ** $exponent;
    }
}
