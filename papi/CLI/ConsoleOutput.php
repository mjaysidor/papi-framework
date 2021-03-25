<?php

declare(strict_types=1);

namespace papi\CLI;

/**
 * Displays output in CLI
 */
class ConsoleOutput extends Console
{
    /**
     * Displays success message
     *
     * @param string $message
     */
    public static function success(string $message): void
    {
        self::output($message, self::COLOR_BLACK, self::BACKGROUND_GREEN);
    }

    /**
     * Displays error message
     *
     * @param string $message
     */
    public static function error(string $message): void
    {
        self::output($message, self::COLOR_WHITE, self::BACKGROUND_RED);
    }

    /**
     * Displays error message and stops the execution of current script
     *
     * @param string $message
     */
    public static function errorDie(string $message): void
    {
        self::error($message);
        die();
    }

    /**
     * Displays warning message
     *
     * @param string $message
     */
    public static function warning(string $message): void
    {
        self::output($message, self::COLOR_BLACK, self::BACKGROUND_YELLOW);
    }

    /**
     * Displays informational message
     *
     * @param string $message
     */
    public static function info(string $message): void
    {
        self::output($message, self::COLOR_BLACK, self::BACKGROUND_BLUE);
    }

    /**
     * Displays message
     *
     * @param string      $message
     * @param string|null $color             Color from Console class (ex. CONSOLE::COLOR_BLACK)
     * @param string|null $background        Color from Console class (ex. CONSOLE::BACKGROUND_GREEN)
     * @param bool        $emptyLineAtTheEnd Display empty row after message
     */
    public static function output(
        string $message,
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

        $output .= "   $message   \033[0m";

        if ($emptyLineAtTheEnd === true) {
            $output .= PHP_EOL;
        }
        echo($output);
    }
}
