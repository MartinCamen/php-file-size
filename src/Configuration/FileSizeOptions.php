<?php

namespace MartinCamen\PhpFileSize\Configuration;

use InvalidArgumentException;
use MartinCamen\PhpFileSize\Enums\ByteBase;
use MartinCamen\PhpFileSize\Enums\ConfigurationOption;

class FileSizeOptions
{
    public function __construct(
        public ?string $byteBase = null,
        public int $precision = 2,
        public ?string $labelStyle = null,
        public string $decimalSeparator = '.',
        public string $thousandsSeparator = ',',
        public bool $spaceBetweenValueAndUnit = true,
        public bool $validationThrowOnNegativeResult = false,
        public bool $validationAllowNegativeInput = false,
    ) {
        $this->byteBase ??= ByteBase::default()->value;
    }

    /** @param array<string, mixed> $options */
    public static function fromArray(array $options = []): self
    {
        $formattedOptions = [];

        foreach ($options as $option => $value) {
            foreach (ConfigurationOption::cases() as $configurationOption) {
                if ($option === $configurationOption->value) {
                    if ($value instanceof ByteBase) {
                        $value = $value->value;
                    }

                    $formattedOptions[$configurationOption->optionKey()] = $value;

                    continue 2;
                }
            }

            throw new InvalidArgumentException(sprintf('Unknown option: %s', $option));
        }

        return new self(...$formattedOptions);
    }

    /** @return array<string, mixed> */
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
        return ByteBase::tryFrom($this->byteBase ?? '') ?? ByteBase::default();
    }

    public function labelByteBase(): ByteBase
    {
        return ByteBase::tryFrom($this->labelStyle ?? '') ?? $this->byteBase();
    }
}
