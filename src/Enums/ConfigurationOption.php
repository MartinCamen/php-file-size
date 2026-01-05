<?php

namespace MartinCamen\PhpFileSize\Enums;

enum ConfigurationOption: string
{
    case ByteBase = 'byte_base';
    case Precision = 'precision';
    case LabelStyle = 'label_style';
    case DecimalSeparator = 'decimal_separator';
    case ThousandsSeparator = 'thousands_separator';
    case SpaceBetweenValueAndUnit = 'space_between_value_and_unit';
    case ValidationThrowOnNegativeResult = 'validation_throw_on_negative_result';
    case ValidationAllowNegativeInput = 'validation_allow_negative_input';

    public function optionKey(): string
    {
        $withSpace = str_replace('_', ' ', $this->value);
        $ucWords = ucwords($withSpace);

        $withoutSpace = str_replace(' ', '', $ucWords);

        return mb_lcfirst($withoutSpace);
    }

    public function isBooleanType(): bool
    {
        return match ($this) {
            self::SpaceBetweenValueAndUnit,
            self::ValidationThrowOnNegativeResult,
            self::ValidationAllowNegativeInput => true,
            default                            => false,
        };
    }
}
