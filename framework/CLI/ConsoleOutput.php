<?php
declare(strict_types=1);

namespace framework\CLI;

class ConsoleOutput
{
    public const  COLOR_BLACK           = '30';
    public const  COLOR_WHITE           = '97';
    public const  COLOR_GREEN           = '32';
    public const  COLOR_RED             = '31';
    public const  COLOR_YELLOW          = '33';
    public const  COLOR_BLUE            = '34';
    public const  BACKGROUND_BLACK      = '40';
    public const  BACKGROUND_RED        = '41';
    public const  BACKGROUND_GREEN      = '42';
    public const  BACKGROUND_YELLOW     = '43';
    public const  BACKGROUND_BLUE       = '44';
    public const  BACKGROUND_MAGENTA    = '45';
    public const  BACKGROUND_CYAN       = '46';
    public const  BACKGROUND_LIGHT_GRAY = '47';
    private const COLORS
                                        = [
            self::COLOR_BLACK  => self::COLOR_BLACK,
            self::COLOR_WHITE  => self::COLOR_WHITE,
            self::COLOR_GREEN  => self::COLOR_GREEN,
            self::COLOR_RED    => self::COLOR_RED,
            self::COLOR_YELLOW => self::COLOR_YELLOW,
            self::COLOR_BLUE   => self::COLOR_BLUE,
        ];
    private const BACKGROUNDS
                                        = [
            self::BACKGROUND_BLACK      => self::BACKGROUND_BLACK,
            self::BACKGROUND_RED        => self::BACKGROUND_RED,
            self::BACKGROUND_GREEN      => self::BACKGROUND_GREEN,
            self::BACKGROUND_YELLOW     => self::BACKGROUND_YELLOW,
            self::BACKGROUND_BLUE       => self::BACKGROUND_BLUE,
            self::BACKGROUND_MAGENTA    => self::BACKGROUND_MAGENTA,
            self::BACKGROUND_CYAN       => self::BACKGROUND_CYAN,
            self::BACKGROUND_LIGHT_GRAY => self::BACKGROUND_LIGHT_GRAY,
        ];

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

    public static function output(string $string, ?string $color = null, ?string $background = null): void
    {
        $output = "";

        if (isset(self::COLORS[$color])) {
            $output .= "\e[".self::COLORS[$color]."m";
        }
        if (isset(self::BACKGROUNDS[$background])) {
            $output .= "\e[".self::BACKGROUNDS[$background]."m";
        }

        $output .= "   $string   \033[0m";

        echo($output.PHP_EOL);
    }
}