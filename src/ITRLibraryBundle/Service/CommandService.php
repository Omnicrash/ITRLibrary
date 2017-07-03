<?php

namespace ITRLibraryBundle\Service;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

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
     *
     * @return CommandResponse
     */
    public function __construct($settings)
    {
        $this->$settings = $settings;
    }

    public function processCommand(string $command, string $line = '', $token = false)
    {
        $command = strtolower($command);

        // If a token is provided, verify it
        if ($token !== false && $token !== $this->settings[$command][$token]) {
            throw new AuthenticationException("Authentication failed");
        }

        $args = $this->splitArgs($line);

        // Show help if requested, or used without parameters
        if (($this->settings[$command]['requires_args'] && count($args) === 0)
            || strtolower($args[0]) === 'help') {
            return new CommandResponse(
                $this->getInstructions($command),
                false
            );
        }

        // Execute the command
        $function = $command.'Command';
        if (!method_exists($this, $function)) {
            throw new \BadFunctionCallException('Method '.$command.' not found');
        }

        return $this->$function($args);
    }


    private function getInstructions($command)
    {
        $settings = $this->settings[$command];

        $instructions = 'Usage: /'.$command.' '.$settings['syntax'];
        if (!empty($settings['instructions'])) {
            $instructions .="\n".$settings['example'];
        }
        if (!empty($settings['example'])) {
            $instructions .="\n\n/Example: ".$settings['example'];
        }

        return $instructions;
    }

    /**
     * Split & trim arguments in a command by spaces or possibly quotes.
     * @param string $line
     *
     * @return array
     */
    private function splitArgs(string $line)
    {
        $args = [];
        $lastPos = strlen($line) - 1;
        $prevPos = -1;
        $char = ' ';
        $quotes = false;
        for ($pos = 0; $pos <= $lastPos; $pos++) {
            // Search for quotes instead of spaces when an opening quote is detected
            if (!$quotes && ($line[$pos] === '"' || $line[$pos] === "'")) {
                $char = $line[$pos];
                $quotes = true;
                $prevPos = $pos;
            } else if ($line[$pos] === $char || $pos === $lastPos) {
                // Allow adding of empty argument if encased with quotes
                if ($quotes || $pos > $prevPos + 1) {
                    $fragment = substr($line, $prevPos + 1, $pos - $prevPos);
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
