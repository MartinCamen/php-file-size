<?php

namespace MartinCamen\PhpFileSize\Enums;

use MartinCamen\PhpFileSize\Configuration\FileSizeOptions;

enum Unit: int
{
    case Byte = 0;
    case KiloByte = 1;
    case MegaByte = 2;
    case GigaByte = 3;
    case TeraByte = 4;
    case PetaByte = 5;

    public function toBytes(float $value, FileSizeOptions $options): float
    {
        return $value * $options->byteBase()->multiply($this->value);
    }

    public function fromBytes(float $bytes, ByteBase $base): float
    {
        return $bytes / $base->multiply($this->value);
    }

    public function label(?ByteBase $base = null, bool $short = false): string
    {
        if ($this->getByteBase($base) === ByteBase::Binary) {
            return $this->getBinaryLabel($short);
        }

        return $this->getDecimalLabel($short);
    }

    public function getBinaryLabel(bool $short = false): string
    {
        if ($short) {
            return match ($this) {
                self::Byte     => 'B',
                self::KiloByte => 'KiB',
                self::MegaByte => 'MiB',
                self::GigaByte => 'GiB',
                self::TeraByte => 'TiB',
                self::PetaByte => 'PiB',
            };
        }

        return match ($this) {
            self::Byte     => 'Bytes',
            self::KiloByte => 'Kibibytes',
            self::MegaByte => 'Mebibytes',
            self::GigaByte => 'Gibibytes',
            self::TeraByte => 'Tebibytes',
            self::PetaByte => 'Pebibytes',
        };
    }

    public function getDecimalLabel(bool $short = false): string
    {
        if ($short) {
            return match ($this) {
                self::Byte     => 'B',
                self::KiloByte => 'KB',
                self::MegaByte => 'MB',
                self::GigaByte => 'GB',
                self::TeraByte => 'TB',
                self::PetaByte => 'PB',
            };
        }

        return match ($this) {
            self::Byte     => 'Bytes',
            self::KiloByte => 'Kilobytes',
            self::MegaByte => 'Megabytes',
            self::GigaByte => 'Gigabytes',
            self::TeraByte => 'Terabytes',
            self::PetaByte => 'Petabytes',
        };
    }

    private function getByteBase(?ByteBase $base = null): ByteBase
    {
        return $base ?? (new FileSizeOptions())->byteBase();
    }
}
