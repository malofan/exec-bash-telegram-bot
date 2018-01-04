<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

/**
 * Class HelpCommand.
 */
class NetworkConnectionsCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'network_connections';

    /**
     * @var string Command Description
     */
    protected $description = 'Get a list of network connections';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $shell_file = dirname(__FILE__) . '/../../linux_json_api.sh';
        $res = stripcslashes(shell_exec($shell_file . " " . $this->name));
        foreach (\array_chunk(\json_decode($res, true), 10) as $items) {
            $text = '';
            foreach ($items as $item) {
                $text .= 'address: ' . $item['address'] . ', connections: ' . $item['connections'] . PHP_EOL;
            }

            $this->replyWithMessage(compact('text'));
        }
    }
}
