<?php

namespace MartinCamen\PhpFileSize\Concerns;

use MartinCamen\PhpFileSize\Configuration\FileSizeOptions;
use MartinCamen\PhpFileSize\Enums\Unit;
use MartinCamen\PhpFileSize\Exceptions\InvalidValueException;
use MartinCamen\PhpFileSize\Exceptions\NegativeValueException;
use MartinCamen\PhpFileSize\FileSize;

trait HandlesArithmetic
{
    public function add(int|float $value, Unit $unit): self
    {
        $bytes = $this->bytes + $unit->toBytes($value, $this->options);

        return $this->toFileSize($bytes);
    }

    public function sub(int|float $value, Unit $unit): self
    {
        $bytes = $this->bytes - $unit->toBytes($value, $this->options);

        if ($bytes < 0 && (new FileSizeOptions())->validationThrowOnNegativeResult) {
            throw new NegativeValueException('Subtraction resulted in negative value.');
        }

        return $this->toFileSize($bytes);
    }

    public function multiply(int|float $multiplier): self
    {
        $bytes = $this->bytes * $multiplier;

        return $this->toFileSize($bytes);
    }

    public function divide(int|float $divisor): self
    {
        if ((int) $divisor === 0) {
            throw new InvalidValueException('Cannot divide by zero.');
        }

        $bytes = $this->bytes / $divisor;

        return $this->toFileSize($bytes);
    }

    // Convenience methods for each unit
    public function addBytes(int|float $value): self
    {
        return $this->add($value, Unit::Byte);
    }

    public function subBytes(int|float $value): self
    {
        return $this->sub($value, Unit::Byte);
    }

    public function addKilobytes(int|float $value): self
    {
        return $this->add($value, Unit::KiloByte);
    }

    public function subKilobytes(int|float $value): self
    {
        return $this->sub($value, Unit::KiloByte);
    }

    public function addMegabytes(int|float $value): self
    {
        return $this->add($value, Unit::MegaByte);
    }

    public function subMegabytes(int|float $value): self
    {
        return $this->sub($value, Unit::MegaByte);
    }

    public function addGigabytes(int|float $value): self
    {
        return $this->add($value, Unit::GigaByte);
    }

    public function subGigabytes(int|float $value): self
    {
        return $this->sub($value, Unit::GigaByte);
    }

    public function addTerabytes(int|float $value): self
    {
        return $this->add($value, Unit::TeraByte);
    }

    public function subTerabytes(int|float $value): self
    {
        return $this->sub($value, Unit::TeraByte);
    }

    public function addPetabytes(int|float $value): self
    {
        return $this->add($value, Unit::PetaByte);
    }

    public function subPetabytes(int|float $value): self
    {
        return $this->sub($value, Unit::PetaByte);
    }

    // Absolute value
    public function abs(): self
    {
        return $this->toFileSize(abs($this->bytes));
    }

    public function toFileSize(float $bytes): FileSize
    {
        return new FileSize($bytes, $this->options->toArray());
    }
}
