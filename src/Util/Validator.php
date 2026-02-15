<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Util;

final class Validator
{
    public static function assertNotEmpty(string $value, string $fieldName): void
    {
        if ($value === '') {
            throw new \InvalidArgumentException($fieldName . ' must not be empty.');
        }
    }

    public static function assertE164(string $value, string $fieldName): void
    {
        self::assertNotEmpty($value, $fieldName);
        if (!preg_match('/^\+?[1-9]\d{7,14}$/', $value)) {
            throw new \InvalidArgumentException($fieldName . ' must be a valid E.164 phone number.');
        }
    }

    public static function assertMaxLength(string $value, int $maxLength, string $fieldName): void
    {
        if (strlen($value) > $maxLength) {
            throw new \InvalidArgumentException($fieldName . ' exceeds the maximum length of ' . $maxLength . '.');
        }
    }

    public static function assertRange(float $value, float $min, float $max, string $fieldName): void
    {
        if ($value < $min || $value > $max) {
            throw new \InvalidArgumentException($fieldName . ' must be between ' . $min . ' and ' . $max . '.');
        }
    }
}
