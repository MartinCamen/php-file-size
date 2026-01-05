<?php

declare(strict_types=1);

use MartinCamen\PhpFileSize\Enums\ConfigurationOption;
use MartinCamen\PhpFileSize\FileSize;

describe('add operations', function (): void {
    it('static bytes is equal to non-static bytes', function (): void {
        $staticSize = (new FileSize())->bytes(1)->addBytes(120);
        $nonStaticSize = FileSize::fromBytes(1)->addBytes(120);

        expect($staticSize->toBytes())->toBe($nonStaticSize->toBytes());
    });

    it('static kilobytes is equal to non-static kilobytes', function (): void {
        $staticSize = (new FileSize())->kilobytes(1)->addBytes(120);
        $nonStaticSize = FileSize::fromKilobytes(1)->addBytes(120);

        expect($staticSize->toBytes())->toBe($nonStaticSize->toBytes());
    });

    it('static megabytes is equal to non-static megabytes', function (): void {
        $staticSize = (new FileSize())->megabytes(1)->addBytes(120);
        $nonStaticSize = FileSize::fromMegabytes(1)->addBytes(120);

        expect($staticSize->toBytes())->toBe($nonStaticSize->toBytes());
    });

    it('static gigabytes is equal to non-static gigabytes', function (): void {
        $staticSize = (new FileSize())->gigabytes(1)->addBytes(120);
        $nonStaticSize = FileSize::fromGigabytes(1)->addBytes(120);

        expect($staticSize->toBytes())->toBe($nonStaticSize->toBytes());
    });

    it('static terabytes is equal to non-static terabytes', function (): void {
        $staticSize = (new FileSize())->terabytes(1)->addBytes(120);
        $nonStaticSize = FileSize::fromTerabytes(1)->addBytes(120);

        expect($staticSize->toBytes())->toBe($nonStaticSize->toBytes());
    });

    it('static petabytes is equal to non-static petabytes', function (): void {
        $staticSize = (new FileSize())->petabytes(1)->addBytes(120);
        $nonStaticSize = FileSize::fromPetabytes(1)->addBytes(120);

        expect($staticSize->toBytes())->toBe($nonStaticSize->toBytes());
    });

    it('static initiation uses given options', function (): void {
        // Precision `2` is the default
        $staticSizeWithoutOptions = FileSize::fromMegabytes(12)->addMegabytes(2);
        $staticSizeWithOptions = FileSize::fromMegabytes(12, [ConfigurationOption::Precision->value => 4])
            ->addMegabytes(2);

        expect($staticSizeWithoutOptions->toMegabytes())->toBe(14.00);
        expect($staticSizeWithOptions->toMegabytes())->toBe(14.0000);
    });
});
