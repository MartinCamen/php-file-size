<?php

namespace MartinCamen\PhpFileSize;

use MartinCamen\PhpFileSize\Concerns\HandlesArithmetic;
use MartinCamen\PhpFileSize\Concerns\HandlesComparisons;
use MartinCamen\PhpFileSize\Concerns\HandlesConversions;
use MartinCamen\PhpFileSize\Concerns\HandlesFormatting;
use MartinCamen\PhpFileSize\Configuration\FileSizeOptions;
use MartinCamen\PhpFileSize\Enums\ByteBase;
use MartinCamen\PhpFileSize\Enums\ConfigurationOption;
use MartinCamen\PhpFileSize\Enums\Unit;
use MartinCamen\PhpFileSize\Exceptions\InvalidValueException;
use MartinCamen\PhpFileSize\Exceptions\NegativeValueException;

class FileSize
{
    use HandlesArithmetic;
    use HandlesComparisons;
    use HandlesConversions;
    use HandlesFormatting;

    private float $bytes = 0;
    public ?FileSizeOptions $options = null;

    /** @param array<string, mixed> $options */
    public function __construct(?float $bytes = null, array $options = [])
    {
        $this->mergeOptions($options);

        if ($bytes !== null) {
            $this->validateValue($bytes);
            $this->bytes = $bytes;
        }
    }

    /** @param array<string, mixed> $options */
    public function bytes(int|float $value, array $options = []): self
    {
        return $this->fromUnit($value, Unit::Byte, $options);
    }

    /** @param array<string, mixed> $options */
    public function kilobytes(int|float $value, array $options = []): self
    {
        return $this->fromUnit($value, Unit::KiloByte, $options);
    }

    /** @param array<string, mixed> $options */
    public function megabytes(int|float $value, array $options = []): self
    {
        return $this->fromUnit($value, Unit::MegaByte, $options);
    }

    /** @param array<string, mixed> $options */
    public function gigabytes(int|float $value, array $options = []): self
    {
        return $this->fromUnit($value, Unit::GigaByte, $options);
    }

    /** @param array<string, mixed> $options */
    public function terabytes(int|float $value, array $options = []): self
    {
        return $this->fromUnit($value, Unit::TeraByte, $options);
    }

    /** @param array<string, mixed> $options */
    public function petabytes(int|float $value, array $options = []): self
    {
        return $this->fromUnit($value, Unit::PetaByte, $options);
    }

    /** @param array<string, mixed> $options */
    public function byte(array $options = []): self
    {
        return $this->bytes(1, $options);
    }

    /** @param array<string, mixed> $options */
    public function kilobyte(array $options = []): self
    {
        return $this->kilobytes(1, $options);
    }

    /** @param array<string, mixed> $options */
    public function megabyte(array $options = []): self
    {
        return $this->megabytes(1, $options);
    }

    /** @param array<string, mixed> $options */
    public function gigabyte(array $options = []): self
    {
        return $this->gigabytes(1, $options);
    }

    /** @param array<string, mixed> $options */
    public function terabyte(array $options = []): self
    {
        return $this->terabytes(1, $options);
    }

    /** @param array<string, mixed> $options */
    public function petabyte(array $options = []): self
    {
        return $this->petabytes(1, $options);
    }

    // Getters via magic method
    public function __get(string $property): float|int
    {
        $options = $this->assertOptions();

        $unit = $this->propertyToUnit($property);
        $value = $unit->fromBytes($this->bytes, $options->byteBase());

        return round($value, $options->precision);
    }

    // Precision configuration
    public function precision(int $precision): self
    {
        $clone = clone $this;

        $clone->mergeOptions([ConfigurationOption::Precision->value => $precision]);

        return $clone;
    }

    public function byteBase(ByteBase $byteBase): self
    {
        $clone = clone $this;

        $clone->mergeOptions([ConfigurationOption::ByteBase->value => $byteBase]);

        return $clone;
    }

    public function getBytes(): float
    {
        return $this->bytes;
    }

    public function getByteBase(): ByteBase
    {
        return ByteBase::tryFrom($this->options->byteBase ?? '') ?? ByteBase::default();
    }

    public function getPrecision(): int
    {
        return $this->options->precision ?? 2;
    }

    private function validateValue(float $value): void
    {
        if ($value < 0 && ! ($this->options->validationAllowNegativeInput ?? false)) {
            throw new NegativeValueException(
                'Negative values are not allowed. Use subtraction methods instead.',
            );
        }

        if (! is_finite($value)) {
            throw new InvalidValueException('Value must be a finite number.');
        }
    }

    private function propertyToUnit(string $property): Unit
    {
        return match (rtrim($property, 's')) {
            'bytes', 'byte'  => Unit::Byte,
            'kilobyte', 'kb' => Unit::KiloByte,
            'megabyte', 'mb' => Unit::MegaByte,
            'gigabyte', 'gb' => Unit::GigaByte,
            'terabyte', 'tb' => Unit::TeraByte,
            'petabyte', 'pb' => Unit::PetaByte,
            default          => throw new InvalidValueException(sprintf('Unknown property: %s', $property)),
        };
    }

    /** @param array<string, mixed> $options */
    private function fromUnit(int|float $value, Unit $unit, array $options = []): self
    {
        $this->mergeOptions($options);

        $fileSizeOptions = $this->assertOptions();

        return new self(
            $unit->toBytes($value, $fileSizeOptions),
            $fileSizeOptions->toArray(),
        );
    }

    /** @param array<string, mixed> $options */
    private function mergeOptions(array $options = []): void
    {
        $this->options = FileSizeOptions::fromArray([
            ...($this->options?->toArray() ?? []),
            ...$options,
        ]);
    }

    private function assertOptions(): FileSizeOptions
    {
        $options = $this->options ??= new FileSizeOptions();

        $this->options = $options;

        return $options;
    }
}
