<?php

declare(strict_types=1);

use MartinCamen\PhpFileSize\Exceptions\InvalidFileException;
use MartinCamen\PhpFileSize\FileSize;

describe('handles file instances', function (): void {
    it('gets size from real file', function (): void {
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'test-file.md';
        $fileSize = FileSize::fromFile($filePath);

        expect($fileSize->toBytes())->toBeFloat();
    })->throwsNoExceptions();

    it('throws exception for non-existent file', function (): void {
        $filePath = 'non-existing-file.md';

        expect(is_file($filePath))->toBeFalse();

        FileSize::fromFile($filePath);
    })->throws(InvalidFileException::class);
});
