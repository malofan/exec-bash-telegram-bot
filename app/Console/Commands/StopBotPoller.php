<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Cache;
use Log;
use Psr\SimpleCache\InvalidArgumentException;

class StopBotPoller extends Command
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
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            Cache::store()->delete('telegramBotIsRunning');
            if (!Cache::store()->get('telegramBotIsRunning', false)) {
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
