# PHP File Size

A fluent PHP library for working with file sizes. Easily create, convert, format, and perform arithmetic operations on file sizes with support for both binary (1024-based) and decimal (1000-based) units.

## Features

- Fluent, chainable API
- Binary (IEC) and Decimal (SI) unit support
- Arithmetic operations (add, subtract, multiply, divide)
- Human-readable formatting
- Comparison methods
- Lazy evaluation for flexible format switching
- Configurable precision and formatting options

## Laravel?

See the [Laravel File Size](https://github.com/MartinCamen/laravel-file-size) port of this library which contains a Laravel facade and more. 

## Requirements

- PHP 8.3 or higher

## Installation

Install via Composer:

```bash
composer require martincamen/php-file-size
```

## Basic Usage

```php
use MartinCamen\PhpFileSize\FileSize;

// Create from various units
$size = (new FileSize())->megabytes(1.5);

// Convert to other units
$size->toKilobytes();  // 1536.0 (binary: 1.5 * 1024)
$size->toBytes();      // 1572864.0

// Format for display
$size->forHumans();    // "1.50 Mebibytes"
$size->formatShort();  // "1.50 MiB"
```

## Creating File Sizes

You can create file sizes from any unit:

```php
$size = (new FileSize())->bytes(1024);
$size = (new FileSize())->kilobytes(512);
$size = (new FileSize())->megabytes(2.5);
$size = (new FileSize())->gigabytes(1);
$size = (new FileSize())->terabytes(0.5);
$size = (new FileSize())->petabytes(1);

// Singular forms for single units
$size = (new FileSize())->kilobyte();  // 1 KB
$size = (new FileSize())->megabyte();  // 1 MB
```

## Binary vs Decimal Units

The library supports both binary (IEC) and decimal (SI) standards:

| Type | Base | Units | Example |
|------|------|-------|---------|
| Binary | 1024 | KiB, MiB, GiB (Kibibyte, Mebibyte, Gibibyte) | 1 MiB = 1,048,576 bytes |
| Decimal | 1000 | KB, MB, GB (Kilobyte, Megabyte, Gigabyte) | 1 MB = 1,000,000 bytes |

**Binary is the default.** To use decimal:

```php
use MartinCamen\PhpFileSize\Enums\ByteBase;

// Using fluent methods
$size = (new FileSize())->inDecimalFormat()->megabytes(2);
$size->toKilobytes();  // 2000.0

// Using constructor options
$size = (new FileSize())->megabytes(2, ['byte_base' => ByteBase::Decimal]);
$size->toKilobytes();  // 2000.0

// Switch formats in a chain (lazy evaluation)
$size = (new FileSize())
    ->megabytes(2)
    ->inDecimalFormat()
    ->toKilobytes();  // 2000.0
```

### Lazy Evaluation

The library uses lazy evaluation, meaning calculations are deferred until you call a terminal method (like `toKilobytes()` or `forHumans()`). This allows you to set the format at any point in the chain:

```php
// The last format set is used for ALL operations
$result = (new FileSize())
    ->inDecimalFormat()        // Initial format
    ->megabytes(2)             // Stored, not computed
    ->subKilobytes(100)        // Stored, not computed
    ->inBinaryFormat()         // Switch to binary
    ->toKilobytes();           // NOW everything is computed using binary

// Force evaluation at a specific point
$evaluated = (new FileSize())
    ->inDecimalFormat()
    ->megabytes(2)
    ->evaluate();              // Returns new FileSize with computed bytes
```

## Arithmetic Operations

Perform arithmetic with any unit:

```php
$size = (new FileSize())->megabytes(10);

// Addition
$size->addBytes(512);
$size->addKilobytes(100);
$size->addMegabytes(5);
$size->add(2, Unit::GigaByte);

// Subtraction
$size->subBytes(256);
$size->subKilobytes(50);
$size->subMegabytes(2);
$size->sub(1, Unit::GigaByte);

// Multiplication and Division
$size->multiply(2);
$size->divide(4);

// Absolute value
$size->abs();

// Chain operations
$result = (new FileSize())
    ->gigabytes(1)
    ->addMegabytes(512)
    ->subKilobytes(1024)
    ->multiply(2)
    ->toMegabytes();
```

## Conversions

Convert to any unit:

```php
$size = (new FileSize())->gigabytes(2.5);

$size->toBytes();      // Raw bytes
$size->toKilobytes();  // 2621440.0
$size->toMegabytes();  // 2560.0
$size->toGigabytes();  // 2.5
$size->toTerabytes();  // 0.0
$size->toPetabytes();  // 0.0

// With custom precision
$size->toMegabytes(4);  // 2560.0000

// Magic property access
$size->bytes;      // 2684354560
$size->kilobytes;  // 2621440.0
$size->megabytes;  // 2560.0
```

## Formatting

Format sizes for human-readable output:

```php
$size = (new FileSize())->megabytes(1536);

// Long format (default)
$size->forHumans();     // "1.50 Gibibytes"
$size->format();        // "1.50 Gibibytes"

// Short format
$size->formatShort();   // "1.50 GiB"
$size->forHumans(true); // "1.50 GiB"

// Decimal labels with binary calculation
$size->inBinaryFormat()->withDecimalLabel()->forHumans();  // "1.50 Gigabytes"

// Custom precision
$size->precision(4)->forHumans();  // "1.5000 Gibibytes"
```

### Formatting Options

```php
use MartinCamen\PhpFileSize\Enums\ConfigurationOption;

$size = (new FileSize())->megabytes(1234.5678, [
    ConfigurationOption::Precision->value => 3,
    ConfigurationOption::DecimalSeparator->value => ',',
    ConfigurationOption::ThousandsSeparator->value => ' ',
    ConfigurationOption::SpaceBetweenValueAndUnit->value => false,
]);

$size->forHumans();  // "1,205GiB" (with binary base)
```

## Comparisons

Compare file sizes:

```php
use MartinCamen\PhpFileSize\Enums\Unit;

$size = (new FileSize())->megabytes(100);

// Equality
$size->equals(100, Unit::MegaByte);     // true
$size->notEquals(50, Unit::MegaByte);   // true

// Ordering
$size->greaterThan(50, Unit::MegaByte);         // true
$size->greaterThanOrEqual(100, Unit::MegaByte); // true
$size->lessThan(200, Unit::MegaByte);           // true
$size->lessThanOrEqual(100, Unit::MegaByte);    // true

// Range
$size->between(50, 150, Unit::MegaByte);  // true

// State checks
$size->isZero();      // false
$size->isPositive();  // true
$size->isNegative();  // false

// Min/Max with another FileSize
$other = (new FileSize())->megabytes(200);
$size->min($other);  // Returns the 100MB FileSize
$size->max($other);  // Returns the 200MB FileSize
```

## Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `byte_base` | `ByteBase` | `Binary` | Use binary (1024) or decimal (1000) calculations |
| `precision` | `int` | `2` | Decimal places for rounding |
| `label_style` | `ByteBase\|null` | `null` | Override label style (binary/decimal labels) |
| `decimal_separator` | `string` | `.` | Character for decimal point |
| `thousands_separator` | `string` | `,` | Character for thousands grouping |
| `space_between_value_and_unit` | `bool` | `true` | Add space between value and unit |
| `validation_throw_on_negative_result` | `bool` | `false` | Throw on negative subtraction results |
| `validation_allow_negative_input` | `bool` | `false` | Allow negative input values |

### Setting Options

```php
use MartinCamen\PhpFileSize\Enums\ByteBase;
use MartinCamen\PhpFileSize\Enums\ConfigurationOption;

// Via constructor
$size = (new FileSize())->megabytes(100, [
    ConfigurationOption::ByteBase->value => ByteBase::Decimal,
    ConfigurationOption::Precision->value => 4,
]);

// Via fluent methods
$size = (new FileSize())
    ->inDecimalFormat()
    ->precision(4)
    ->megabytes(100);
```

## Immutability

All operations return new instances, preserving the original:

```php
$original = (new FileSize())->megabytes(100);
$modified = $original->addMegabytes(50);

$original->toMegabytes();  // 100.0
$modified->toMegabytes();  // 150.0
```

## Error Handling

```php
use MartinCamen\PhpFileSize\Exceptions\InvalidValueException;
use MartinCamen\PhpFileSize\Exceptions\NegativeValueException;

// Division by zero
try {
    $size->divide(0);
} catch (InvalidValueException $e) {
    // "Cannot divide by zero."
}

// Invalid values (INF, NaN)
try {
    (new FileSize())->bytes(INF);
} catch (InvalidValueException $e) {
    // "Value must be a finite number."
}

// Negative input (when not allowed)
try {
    (new FileSize())->megabytes(-5);
} catch (NegativeValueException $e) {
    // "Negative values are not allowed."
}

// Allow negative values
$size = (new FileSize())->megabytes(-5, [
    ConfigurationOption::ValidationAllowNegativeInput->value => true,
]);
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
