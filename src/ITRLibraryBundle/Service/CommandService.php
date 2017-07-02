<?php

namespace ITRLibraryBundle\Service;

/**
 * Processes and executes command-line commands.
 *
 * @package ITRLibraryBundle\Service
 */
class CommandService
{
    private $settings;

    /**
     * CommandService constructor.
     *
     * @param $settings
     */
    public function __construct($settings)
    {
        $this->$settings = $settings;
    }

    /**
     * Split & trim arguments in a command by spaces or possibly quotes.
     * @param string $command
     *
     * @return array
     */
    private function splitArgs(string $command)
    {
        $args = [];
        $lastPos = strlen($command) - 1;
        $prevPos = -1;
        $char = ' ';
        $quotes = false;
        for ($pos = 0; $pos <= $lastPos; $pos++) {
            // Search for quotes instead of spaces when an opening quote is detected
            if (!$quotes && ($command[$pos] === '"' || $command[$pos] === "'")) {
                $char = $command[$pos];
                $quotes = true;
                $prevPos = $pos;
            } else if ($command[$pos] === $char || $pos === $lastPos) {
                // Allow adding of empty argument if encased with quotes
                if ($quotes || $pos > $prevPos + 1) {
                    $fragment = substr($command, $prevPos + 1, $pos - $prevPos);
                    $args[] = $fragment;
                }
                if ($quotes) {
                    $char = ' ';
                    $quotes = false;
                }
                $prevPos = $pos;
            }
        }

        return $args;
    }

}
