<?php

namespace MartinCamen\PhpFileSize\Configuration;

use InvalidArgumentException;
use MartinCamen\PhpFileSize\Enums\ByteBase;
use MartinCamen\PhpFileSize\Enums\ConfigurationOption;

/**
 * @phpstan-type OptionalFileSizeOptionsType array{
 *   byte_base?: string|null|ByteBase,
 *   precision?: int|null,
 *   label_style?: string|null|ByteBase,
 *   decimal_separator?: string|null,
 *   thousands_separator?: string|null,
 *   space_between_value_and_unit?: bool|null,
 *   validation_throw_on_negative_result?: bool|null,
 *   validation_allow_negative_input?: bool|null
 * }|array<'byte_base'|'decimal_separator'|'label_style'|'precision'|'thousands_separator'|'space_between_value_and_unit'|'validation_throw_on_negative_result'|'validation_allow_negative_input', bool|int|ByteBase|string|null>
 * @phpstan-type RequiredFileSizeOptionsType array{
 *    byte_base: string|null|ByteBase,
 *    precision: int|null,
 *    label_style: string|null|ByteBase,
 *    decimal_separator: string|null,
 *    thousands_separator: string|null,
 *    space_between_value_and_unit: bool|null,
 *    validation_throw_on_negative_result: bool|null,
 *    validation_allow_negative_input: bool|null
 * }
 **/
class FileSizeOptions
{
    public string $byteBase;

    public function __construct(
        string|null|ByteBase $byteBase = null,
        public int $precision = 2,
        public ?string $labelStyle = null,
        public string $decimalSeparator = '.',
        public string $thousandsSeparator = ',',
        public bool $spaceBetweenValueAndUnit = true,
        public bool $validationThrowOnNegativeResult = false,
        public bool $validationAllowNegativeInput = false,
    ) {
        if ($byteBase instanceof ByteBase) {
            $byteBase = $byteBase->value;
        }

        $this->byteBase = $byteBase ?? ByteBase::default()->value;
    }

    /** @param OptionalFileSizeOptionsType $options */
    public static function fromArray(array $options = []): self
    {
        $formattedOptions = [];

        foreach ($options as $option => $value) {
            foreach (ConfigurationOption::cases() as $configurationOption) {
                if ($option === $configurationOption->value) {
                    if ($value instanceof ByteBase) {
                        $value = $value->value;
                    }

                    if ($configurationOption->isBooleanType()) {
                        $value = (bool) $value;
                    }

                    $formattedOptions[$configurationOption->optionKey()] = $value;

                    continue 2;
                }
            }

            throw new InvalidArgumentException(sprintf('Unknown option: %s', $option));
        }

        return new self(...$formattedOptions);
    }

    /** @return RequiredFileSizeOptionsType */
    public function toArray(): array
    {
        return [
            ConfigurationOption::ByteBase->value                        => $this->byteBase,
            ConfigurationOption::Precision->value                       => $this->precision,
            ConfigurationOption::LabelStyle->value                      => $this->labelStyle,
            ConfigurationOption::DecimalSeparator->value                => $this->decimalSeparator,
            ConfigurationOption::ThousandsSeparator->value              => $this->thousandsSeparator,
            ConfigurationOption::SpaceBetweenValueAndUnit->value        => $this->spaceBetweenValueAndUnit,
            ConfigurationOption::ValidationThrowOnNegativeResult->value => $this->validationThrowOnNegativeResult,
            ConfigurationOption::ValidationAllowNegativeInput->value    => $this->validationAllowNegativeInput,
        ];
    }

    public function byteBase(): ByteBase
    {
        return ByteBase::tryFrom($this->byteBase) ?? ByteBase::default();
    }

    public function labelByteBase(): ByteBase
    {
        return ByteBase::tryFrom($this->labelStyle ?? '') ?? $this->byteBase();
    }
}
