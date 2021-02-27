<?php
declare(strict_types=1);

namespace papi\CLI;

class ConsoleInput extends Console
{
    public static function getInput(string $prompt): string
    {
        $output = "\n";

        $output .= "\e[".self::COLORS[self::COLOR_BLACK]."m";
        $output .= "\e[".self::BACKGROUNDS[self::BACKGROUND_BLUE]."m";

        $output .= "   $prompt   \033[0m  ";
        echo "$output\n";
        return readline();
    }
}