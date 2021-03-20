<?php

declare(strict_types=1);

namespace papi\Utils;

class CacheStorage
{
    public static function set(string $key, mixed $value, ?int $ttl = null): void
    {
        $value = var_export($value, true);
        $pathName = "var/cache/$key.tmp";
        $content = '$val = '."$value;";

        if ($ttl !== null) {
            $expirationDate = (new \DateTime());
            $expirationDate->modify("+$ttl seconds");
            $content .= '$expirationDate = '.var_export($expirationDate, true).';';
        }

        file_put_contents($pathName, "<?php $content", LOCK_EX);
        touch($pathName, $_SERVER['REQUEST_TIME'] - 5);
        opcache_compile_file($pathName);
    }

    public static function get(string $key): mixed
    {
        $val = $expirationDate = null;
        $filePath = "var/cache/$key.tmp";

        if (file_exists($filePath) === false) {
            return false;
        }

        include $filePath;

        if ($val === null) {
            return false;
        }

        if ($expirationDate !== null) {
            $currentDate = new \DateTime();
            if ($currentDate > $expirationDate) {
                unlink($filePath);

                return false;
            }
        }

        return $val;
    }
}
