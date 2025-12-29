<?php

namespace MartinCamen\PhpFileSize\Concerns;

use MartinCamen\PhpFileSize\Enums\ByteBase;
use MartinCamen\PhpFileSize\Enums\Unit;

trait HandlesFormatting
{
    /** @param array<string, mixed> $options */
    public function forHumans(
        bool $short = false,
        array $options = [],
    ): string {
        $this->mergeOptions($options);

        $unit = $this->guessUnit();
        $value = $unit->fromBytes($this->bytes, $this->options->byteBase());

        $formattedValue = number_format(
            round($value, $this->options->precision),
            $this->options->precision,
            $this->options->decimalSeparator,
            $this->options->thousandsSeparator,
        );

        return $formattedValue
            . ($this->options->spaceBetweenValueAndUnit ? ' ' : '')
            . $unit->label($this->options->labelByteBase(), $short);
    }

    /** @param array<string, mixed> $options */
    public function format(array $options = []): string
    {
        return $this->forHumans(false, $options);
    }

    /** @param array<string, mixed> $options */
    public function formatShort(array $options = []): string
    {
        return $this->forHumans(true, $options);
    }

    public function inBinaryFormat(): self
    {
        return $this->withTypeBaseFormat(ByteBase::Binary);
    }

    public function inDecimalFormat(): self
    {
        return $this->withTypeBaseFormat(ByteBase::Decimal);
    }

    public function withTypeBaseFormat(ByteBase $byteBase): self
    {
        $this->options->byteBase = $byteBase->value;

        return $this;
    }

    public function withBinaryLabel(): self
    {
        return $this->withLabelStyle(ByteBase::Binary);
    }

    public function withDecimalLabel(): self
    {
        return $this->withLabelStyle(ByteBase::Decimal);
    }

    public function withLabelStyle(ByteBase $byteBase): self
    {
        $this->options->labelStyle = $byteBase->value;

        return $this;
    }

    private function guessUnit(): Unit
    {
        $absBytes = abs($this->bytes);

        foreach ([Unit::PetaByte, Unit::TeraByte, Unit::GigaByte, Unit::MegaByte, Unit::KiloByte] as $unit) {
            if ($absBytes >= $unit->toBytes(1, $this->options)) {
                return $unit;
            }
        }

        return Unit::Byte;
    }
}
