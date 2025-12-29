<?php

declare(strict_types=1);

use MartinCamen\PhpFileSize\Enums\ConfigurationOption;
use MartinCamen\PhpFileSize\Enums\Unit;
use MartinCamen\PhpFileSize\FileSize;

describe('equals', function (): void {
    it('returns true when values are equal', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->equals(2048, Unit::KiloByte))->toBeTrue();
    });

    it('returns false when values are not equal', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->equals(1000, Unit::KiloByte))->toBeFalse();
    });

    it('compares with precision', function (): void {
        $size = (new FileSize())->bytes(1024);

        expect($size->equals(1, Unit::KiloByte, [ConfigurationOption::Precision->value => 0]))->toBeTrue();
    });
});

describe('notEquals', function (): void {
    it('returns true when values are not equal', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->notEquals(1000, Unit::KiloByte))->toBeTrue();
    });

    it('returns false when values are equal', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->notEquals(2048, Unit::KiloByte))->toBeFalse();
    });
});

describe('greaterThan', function (): void {
    it('returns true when size is greater', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->greaterThan(1, Unit::MegaByte))->toBeTrue();
    });

    it('returns false when size is equal', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->greaterThan(2, Unit::MegaByte))->toBeFalse();
    });

    it('returns false when size is less', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->greaterThan(3, Unit::MegaByte))->toBeFalse();
    });
});

describe('greaterThanOrEqual', function (): void {
    it('returns true when size is greater', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->greaterThanOrEqual(1, Unit::MegaByte))->toBeTrue();
    });

    it('returns true when size is equal', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->greaterThanOrEqual(2, Unit::MegaByte))->toBeTrue();
    });

    it('returns false when size is less', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->greaterThanOrEqual(3, Unit::MegaByte))->toBeFalse();
    });
});

describe('lessThan', function (): void {
    it('returns true when size is less', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->lessThan(3, Unit::MegaByte))->toBeTrue();
    });

    it('returns false when size is equal', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->lessThan(2, Unit::MegaByte))->toBeFalse();
    });

    it('returns false when size is greater', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->lessThan(1, Unit::MegaByte))->toBeFalse();
    });
});

describe('lessThanOrEqual', function (): void {
    it('returns true when size is less', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->lessThanOrEqual(3, Unit::MegaByte))->toBeTrue();
    });

    it('returns true when size is equal', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->lessThanOrEqual(2, Unit::MegaByte))->toBeTrue();
    });

    it('returns false when size is greater', function (): void {
        $size = (new FileSize())->megabytes(2);

        expect($size->lessThanOrEqual(1, Unit::MegaByte))->toBeFalse();
    });
});

describe('between', function (): void {
    it('returns true when value is within range', function (): void {
        $size = (new FileSize())->megabytes(5);

        expect($size->between(1, 10, Unit::MegaByte))->toBeTrue();
    });

    it('returns true when value equals lower bound', function (): void {
        $size = (new FileSize())->megabytes(1);

        expect($size->between(1, 10, Unit::MegaByte))->toBeTrue();
    });

    it('returns true when value equals upper bound', function (): void {
        $size = (new FileSize())->megabytes(10);

        expect($size->between(1, 10, Unit::MegaByte))->toBeTrue();
    });

    it('returns false when value is below range', function (): void {
        $size = (new FileSize())->megabytes(0);

        expect($size->between(1, 10, Unit::MegaByte))->toBeFalse();
    });

    it('returns false when value is above range', function (): void {
        $size = (new FileSize())->megabytes(11);

        expect($size->between(1, 10, Unit::MegaByte))->toBeFalse();
    });
});

describe('min and max', function (): void {
    it('returns the smaller size with min', function (): void {
        $size1 = (new FileSize())->megabytes(5);
        $size2 = (new FileSize())->megabytes(10);

        expect($size1->min($size2))->toBe($size1);
        expect($size2->min($size1))->toBe($size1);
    });

    it('returns the larger size with max', function (): void {
        $size1 = (new FileSize())->megabytes(5);
        $size2 = (new FileSize())->megabytes(10);

        expect($size1->max($size2))->toBe($size2);
        expect($size2->max($size1))->toBe($size2);
    });

    it('returns self when sizes are equal', function (): void {
        $size1 = (new FileSize())->megabytes(5);
        $size2 = (new FileSize())->megabytes(5);

        expect($size1->min($size2))->toBe($size1);
        expect($size1->max($size2))->toBe($size1);
    });
});

describe('state checks', function (): void {
    it('isZero returns true for zero size', function (): void {
        $size = (new FileSize())->bytes(0);

        expect($size->isZero())->toBeTrue();
    });

    it('isZero returns false for non-zero size', function (): void {
        $size = (new FileSize())->bytes(1);

        expect($size->isZero())->toBeFalse();
    });

    it('isPositive returns true for positive size', function (): void {
        $size = (new FileSize())->megabytes(5);

        expect($size->isPositive())->toBeTrue();
    });

    it('isPositive returns false for zero size', function (): void {
        $size = (new FileSize())->bytes(0);

        expect($size->isPositive())->toBeFalse();
    });

    it('isNegative returns true for negative size', function (): void {
        $size = (new FileSize(options: [ConfigurationOption::ValidationAllowNegativeInput->value => true]))->megabytes(1)->subMegabytes(2);

        expect($size->isNegative())->toBeTrue();
    });

    it('isNegative returns false for positive size', function (): void {
        $size = (new FileSize())->megabytes(5);

        expect($size->isNegative())->toBeFalse();
    });
});
