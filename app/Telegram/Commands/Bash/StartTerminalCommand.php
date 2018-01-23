<?php

namespace App\Telegram\Commands\Bash;

use App\Terminal;
use Telegram\Bot\Commands\Command;

/**
 * Class HelpCommand.
 */
class StartTerminalCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'start_terminal';

    /**
     * @var string Command Description
     */
    protected $description = 'Start Bash Terminal session';
    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        Terminal::start();

        $text = 'You have problems with your Cache storage settings';
        if (Terminal::loaded()) {
            $text = 'Welcome, ' . str_replace("\n", '', `whoami`) . '!!' . PHP_EOL .
                'Terminal is STARTED.' . PHP_EOL .
                'Call /stop_terminal command to STOP it else it will be STOPPED automatically after ' . Terminal::TERMINAL_TIMEOUT . 'min of non-activity';
        }

        $this->replyWithMessage(compact('text'));
    }
}
