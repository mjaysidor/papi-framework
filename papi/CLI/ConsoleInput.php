<?php

declare(strict_types=1);

namespace papi\CLI;

/**
 * Gets user input via CLI
 */
class ConsoleInput extends Console
{
    /**
     * Display prompt and get user CLI input
     *
     * @param string $prompt
     *
     * @return string
     */
    public static function getInput(string $prompt): string
    {
        $output = "\n\e[".self::COLORS[self::COLOR_BLACK]."m";
        $output .= "\e[".self::BACKGROUNDS[self::BACKGROUND_GREEN]."m";

        $output .= "   $prompt   \033[0m  ";
        echo "$output\n";

        return (string)readline();
    }

    /**
     * Display predefined options and get user's choice
     *
     * @param string $title
     * @param array  $options
     *
     * @return string
     */
    public static function getInputFromChoices(
        string $title,
        array $options
    ): string {
        ConsoleOutput::success($title);
        $choices = [];
        foreach ($options as $key => $value) {
            $choices[] = "[$key] $value";
        }
        ConsoleOutput::output(implode(' || ', $choices));

        readline_completion_function(
            static function () use ($options) {
                return $options;
            }
        );
        $firstKey = array_key_first($options);

        while (! array_key_exists(
            ($result = self::getInput("Choice (ex. $firstKey for $options[$firstKey]):")),
            $options
        )
        ) {
            ConsoleOutput::warning('Invalid input!');
            continue;
        }

        return $options[$result];
    }
}
