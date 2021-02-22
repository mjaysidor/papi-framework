<?php
declare(strict_types=1);

namespace papi\Utils;

class ArrayDiff
{
    public static function removeArrayCommonElements(array &$arr1, array &$arr2): void
    {
        foreach ($arr1 as $key => $value) {
            if (isset($arr2[$key]) && $arr2[$key] === $arr1[$key]) {
                unset($arr2[$key], $arr1[$key]);
            }
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if (isset($arr2[$key][$k]) && $arr2[$key][$k] === $arr1[$key][$k]) {
                        unset($arr2[$key][$k], $arr1[$key][$k]);
                    }
                }
            }
        }
    }
}