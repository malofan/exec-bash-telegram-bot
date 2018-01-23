<?php

namespace App\Console\Commands;


use App\Terminal;
use Psr\Log\InvalidArgumentException;
use Telegram\Bot\Laravel\Facades\Telegram;
use Cache;
use Log;

class RunBotPoller extends BotPoller
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Telegram Bot Polling Service';

    /**
     * @inheritdoc
     */
    public function handle()
    {
        if (Cache::has(self::BOT_POLLER_CACHE_ID)) {
            print 'Bot is already running' . PHP_EOL;
            exit;
        }
        try {
            Cache::forever(self::BOT_POLLER_CACHE_ID, true);
        }
        catch (InvalidArgumentException $exception) {
            Log::alert($exception->getMessage());
            exit;
        }
        $child_pid = pcntl_fork();
        if ($child_pid) {
            exit;
        }
        posix_setsid();

        while (Cache::has(self::BOT_POLLER_CACHE_ID)) {
            foreach (Telegram::commandsHandler() as $update) {
                $message = $update->getMessage();

                if ($message !== null && $message->has('text')) {
                    $text = trim($message->getText());
                    if (strpos($text, '/') !== 0) {
                        if (Terminal::loaded()) {
                            $text = Terminal::execute($text);
                        }
                        /**
                        * @var int|string $params ['chat_id']
                        * @var string     $params ['text']
                        * @var string     $params ['parse_mode']
                        * @var bool       $params ['disable_web_page_preview']
                        * @var int        $params ['reply_to_message_id']
                        * @var string     $params ['reply_markup']
                        */
                        if ($text) {
                            Telegram::sendMessage([
                                'chat_id' => $message->getChat()->getId(),
                                'text' => $text,
                                'parse_mode' => 'HTML',
                            ]);
                        }
                    }
                }
            }
        }
    }
}
