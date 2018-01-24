<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class SetBotToken extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:set-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Telegram\'s Bot Access Token.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $token = $this->ask('Your Telegram\'s Bot Access Token. Example: 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11');

        if (! $this->setTokenInEnvironmentFile($token)) {
            return;
        }

        $this->laravel['config']['telegram.bot_token'] = $token;

        $this->info("Telegram's Bot Access Token [$token] set successfully.");
    }

    /**
     * Set Telegram's Bot Access Token in the environment file.
     *
     * @param  string  $token
     * @return bool
     */
    protected function setTokenInEnvironmentFile($token)
    {
        $currentToken = $this->laravel['config']['telegram.bot_token'];

        if (strlen($currentToken) !== 0 && (! $this->confirmToProceed())) {
            return false;
        }

        $this->writeNewEnvironmentFileWith($token);

        return true;
    }

    /**
     * Write a new environment file with the given Telegram's Bot Access Token.
     *
     * @param  string  $token
     * @return void
     */
    protected function writeNewEnvironmentFileWith($token)
    {
        file_put_contents($this->laravel->environmentFilePath(), preg_replace(
            $this->keyReplacementPattern(),
            'TELEGRAM_BOT_TOKEN='.$token,
            file_get_contents($this->laravel->environmentFilePath())
        ));
    }

    /**
     * Get a regex pattern that will match env TELEGRAM_BOT_TOKEN with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern()
    {
        $escaped = preg_quote('='.$this->laravel['config']['telegram.bot_token'], '/');

        return "/^TELEGRAM_BOT_TOKEN{$escaped}/m";
    }
}
