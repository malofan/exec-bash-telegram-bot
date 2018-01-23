<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

abstract class BotPoller extends Command
{
    const BOT_POLLER_CACHE_ID = 'telegramBotIsRunning';
    /**
     * Execute the console command.
     */
    abstract public function handle();
}
