<?php

namespace MartinCamen\PhpFileSize\ValueObjects;

use MartinCamen\PhpFileSize\Exceptions\InvalidFileException;
use SplFileInfo;

class FileInspector
{
    protected string $path;

    public function __construct(string|SplFileInfo $file)
    {
        $this->path = $file instanceof SplFileInfo
            ? $file->getPathname()
            : $file;

        $this->validateFilePath();
    }

    protected function validateFilePath(): void
    {
        if (! is_file($this->path)) {
            throw new InvalidFileException("Not a valid file: {$this->path}");
        }
    }

    public function sizeInBytes(): int
    {
        $size = filesize($this->path);

        if ($size === false) {
            throw new InvalidFileException("Unable to read file size: {$this->path}");
        }

        return $size;
    }
}
