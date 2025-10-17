<?php

namespace App\Util;

use NumberFormatter;

class NumberFormatterUtil
{
    const LOCALE = 'en_GB';

    public static function getOrdinalNumberFormatter(): NumberFormatter
    {
        return new NumberFormatter(self::LOCALE, NumberFormatter::ORDINAL);
    }

    public static function formatOrdinal(int|float $value): string
    {
        return self::getOrdinalNumberFormatter()->format($value);
    }
}
