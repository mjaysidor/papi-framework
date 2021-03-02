<?php
declare(strict_types=1);

namespace papi\Utils;

class ArrayDiff
{
    public static function removeArrayCommonElements(array &$array1, array &$array2): void
    {
        foreach ($array1 as $key => $value) {
            if (isset($array2[$key]) && $array2[$key] === $array1[$key]) {
                unset($array2[$key], $array1[$key]);
            }
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if (isset($array2[$key][$k]) && $array2[$key][$k] === $array1[$key][$k]) {
                        unset($array2[$key][$k], $array1[$key][$k]);
                    }
                }
            }
        }
    }
}