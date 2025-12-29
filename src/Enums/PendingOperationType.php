<?php

namespace MartinCamen\PhpFileSize\Enums;

enum PendingOperationType
{
    case Set;
    case Add;
    case Subtract;
    case Multiply;
    case Divide;
    case Default;

    public function executeCalculation(float|int $initialValue, float|int $newValue): float|int
    {
        return match ($this) {
            self::Set      => $newValue,
            self::Add      => $initialValue + $newValue,
            self::Subtract => $initialValue - $newValue,
            self::Multiply => $initialValue * $newValue,
            self::Divide   => $initialValue / $newValue,
            default        => $initialValue,
        };
    }
}
