<?php

declare(strict_types=1);

namespace papi\CLI;

class ConsoleOutput extends Console
{
    public static function success(string $string): void
    {
        self::output($string, self::COLOR_BLACK, self::BACKGROUND_GREEN);
    }

    public static function error(string $string): void
    {
        self::output($string, self::COLOR_WHITE, self::BACKGROUND_RED);
    }

    public static function errorDie(string $string): void
    {
        self::error($string);
        die();
    }

    public static function warning(string $string): void
    {
        self::output($string, self::COLOR_BLACK, self::BACKGROUND_YELLOW);
    }

    public static function info(string $string): void
    {
        self::output($string, self::COLOR_BLACK, self::BACKGROUND_BLUE);
    }

    public static function output(
        string $string,
        ?string $color = null,
        ?string $background = null,
        bool $emptyLineAtTheEnd = true
    ): void {
        $output = "\n";

        if (isset(self::COLORS[$color])) {
            $output .= "\e[".self::COLORS[$color]."m";
        }
        if (isset(self::BACKGROUNDS[$background])) {
            $output .= "\e[".self::BACKGROUNDS[$background]."m";
        }

        $output .= "   $string   \033[0m";

        if ($emptyLineAtTheEnd === true) {
            $output .= PHP_EOL;
        }
        echo($output);
    }
}
