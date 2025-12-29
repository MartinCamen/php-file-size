<?php

namespace MartinCamen\PhpFileSize\Concerns;

use MartinCamen\PhpFileSize\Enums\PendingOperationType;
use MartinCamen\PhpFileSize\Enums\Unit;
use MartinCamen\PhpFileSize\Exceptions\InvalidValueException;
use MartinCamen\PhpFileSize\FileSize;
use MartinCamen\PhpFileSize\ValueObjects\PendingOperation;

trait HandlesArithmetic
{
    public function add(int|float $value, Unit $unit): self
    {
        return $this->cloneWithPendingOperation(PendingOperationType::Add, $value, $unit);
    }

    public function sub(int|float $value, Unit $unit): self
    {
        return $this->cloneWithPendingOperation(PendingOperationType::Subtract, $value, $unit);
    }

    public function multiply(int|float $multiplier): self
    {
        return $this->cloneWithPendingOperation(PendingOperationType::Multiply, $multiplier);
    }

    public function divide(int|float $divisor): self
    {
        if ((int) $divisor === 0) {
            throw new InvalidValueException('Cannot divide by zero.');
        }

        return $this->cloneWithPendingOperation(PendingOperationType::Divide, $divisor);
    }

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

    public function abs(): self
    {
        $bytes = abs($this->resolveBytes());

        $options = $this->assertOptions();

        return new FileSize($bytes, $options->toArray());
    }

    private function cloneWithPendingOperation(
        PendingOperationType $type,
        int|float $value,
        ?Unit $unit = null,
    ): self {
        $clone = clone $this;

        $clone->pendingOperations[] = new PendingOperation(
            type: $type,
            value: (float) $value,
            unit: $unit,
        );

        return $clone;
    }
}
