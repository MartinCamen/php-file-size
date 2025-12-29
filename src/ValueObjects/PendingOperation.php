<?php

namespace MartinCamen\PhpFileSize\ValueObjects;

use MartinCamen\PhpFileSize\Enums\PendingOperationType;
use MartinCamen\PhpFileSize\Enums\Unit;

class PendingOperation
{
    public function __construct(
        public PendingOperationType $type,
        public int|float $value,
        public ?Unit $unit = null,
    ) {}
}
