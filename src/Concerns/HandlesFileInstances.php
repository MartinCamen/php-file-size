<?php

namespace MartinCamen\PhpFileSize\Concerns;

use MartinCamen\PhpFileSize\Configuration\FileSizeOptions;
use MartinCamen\PhpFileSize\ValueObjects\FileInspector;
use SplFileInfo;

/** @phpstan-import-type OptionalFileSizeOptionsType from FileSizeOptions */
trait HandlesFileInstances
{
    /** @param OptionalFileSizeOptionsType $options */
    public static function fromFile(string|SplFileInfo $file, array $options = []): static
    {
        $fileSizeInBytes = (new FileInspector($file))->sizeInBytes();

        return new static($fileSizeInBytes, $options);
    }
}
