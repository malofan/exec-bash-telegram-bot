<?php

namespace App\Telegram\Commands\Bash;

use App\Terminal;
use Telegram\Bot\Commands\Command;

/**
 * Class HelpCommand.
 */
class StopTerminalCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'stop_terminal';

    /**
     * @var string Command Description
     */
    protected $description = 'Stop Bash Terminal session';
    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        Terminal::stop();

        $text = 'Terminal is STOPPED.';
        if (Terminal::loaded()) {
            $text = 'Something went wrong. Please try again!';
        }

        $this->replyWithMessage(compact('text'));
    }
}
