<?php

namespace App\Console\Commands;

use Cache;
use Log;
use App;
use Psr\SimpleCache\InvalidArgumentException;


class StopBotPoller extends BotPoller
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:stop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop Telegram Bot Polling Service';

    /**
     * @inheritdoc
     */
    public function handle()
    {
        try {
            Cache::forget(self::BOT_POLLER_CACHE_ID);
            if (!Cache::has(self::BOT_POLLER_CACHE_ID)) {
                print 'Bot was stopped' . PHP_EOL;
                Log::info('Bot is stopped');
            } else {
                print 'Bot not stopped!' . PHP_EOL;
                Log::alert('Bot not stopped!');
            }
        }
        catch (InvalidArgumentException $exception) {
            Log::alert($exception->getMessage());
            exit;
        }
    }
}
