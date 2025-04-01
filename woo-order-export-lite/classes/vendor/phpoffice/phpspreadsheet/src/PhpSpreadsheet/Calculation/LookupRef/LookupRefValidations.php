<?php

namespace WOE\PhpOffice\PhpSpreadsheet\Calculation\LookupRef;

use WOE\PhpOffice\PhpSpreadsheet\Calculation\Exception;
use WOE\PhpOffice\PhpSpreadsheet\Calculation\Information\ErrorValue;
use WOE\PhpOffice\PhpSpreadsheet\Calculation\Information\ExcelError;

class LookupRefValidations
{
    public static function validateInt(mixed $value): int
    {
        if (!is_numeric($value)) {
            if (ErrorValue::isError($value)) {
                throw new Exception($value);
            }

            throw new Exception(ExcelError::VALUE());
        }

        return (int) floor((float) $value);
    }

    public static function validatePositiveInt(mixed $value, bool $allowZero = true): int
    {
        $value = self::validateInt($value);

        if (($allowZero === false && $value <= 0) || $value < 0) {
            throw new Exception(ExcelError::VALUE());
        }

        return $value;
    }
}
