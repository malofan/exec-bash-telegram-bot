<?php

    namespace App\Console\Commands;

    use Illuminate\Console\Command;
    use Psr\Log\InvalidArgumentException;
    use Telegram\Bot\Laravel\Facades\Telegram;
    use Cache;
    use Log;
    use Mockery\Exception;

    class RunBotPoller extends Command
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
         * Execute the console command
         */
        public function handle()
        {
            if (Cache::store()->get('telegramBotIsRunning', false)) {
                print 'Bot is already running' . PHP_EOL;
                exit;
            }
            try {
                Cache::forever('telegramBotIsRunning', true);
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
            $shell_file = dirname(__FILE__) . '/linux_json_api.sh';

            while (Cache::store()->get('telegramBotIsRunning', false)) {
//                Telegram::commandsHandler();
                foreach (Telegram::commandsHandler() as $update) {
                    $message = $update->getMessage();

                    if ($message !== null && $message->has('text')) {
                        $text = trim($message->getText());
                        if (strpos($text, '/') !== 0) {
                            /**
                            * @var int|string $params ['chat_id']
                            * @var string     $params ['text']
                            * @var string     $params ['parse_mode']
                            * @var bool       $params ['disable_web_page_preview']
                            * @var int        $params ['reply_to_message_id']
                            * @var string     $params ['reply_markup']
                            */
                            $module = 'cpu_temp';//escapeshellcmd($_GET['module']);
                            $text = stripcslashes(shell_exec( $shell_file . " " . $text ));
                            Telegram::sendMessage([
                                'chat_id' => $message->getChat()->getId(),
                                'text' => $text ? $text : 'ne vushlo',
                            ]);
                        }
                    }
//                $username = null;
//                $parts = explode(' ' , $text);
//                if (count($parts) === 2) { // ['/start', '<uniqueCode>']
//                    $uniqueCode = $parts[1];
//                    $username = Telegram::getUsernameByUniqueCode($uniqueCode);
//                }
//                if ($username !== null) {
//                    println('Received message from known user "'.$username.'"!');
//                    Telegram::sendMessage($chatId, 'Hello there, '.$username.'! I am glad you made it!');
//                } else {
//                    println('Received message from an unknown user :(');
//                    Telegram::sendMessage($chatId, 'Hello! Unfortunately I do not know you :(');
//                }
                }
            }
        }
    }
