<?php

namespace Tests\Unit;

use Tests\TestCase;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotApiKeyIsValidTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = Telegram::getMe();

        $botId = $response->getId();

        $this->assertNotEmpty($botId);
    }
}
