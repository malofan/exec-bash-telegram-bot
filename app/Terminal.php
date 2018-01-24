<?php

namespace App;


use App;
use Cache;

class Terminal
{
    const TERMINAL_CACHE_ID = 'TERMINAL_CACHE_ID';
    /**
     * @var int Minutes before TERMINAL_CACHE_ID will be deleted
     */
    const TERMINAL_TIMEOUT = 10;
    
    public static function start(): void
    {
        Cache::add(self::TERMINAL_CACHE_ID, ['disposition' => ['cd ' . App::basePath()]], self::TERMINAL_TIMEOUT);
    }

    public static function stop(): void
    {
        Cache::forget(self::TERMINAL_CACHE_ID);
    }

    public static function loaded(): bool
    {
        return Cache::has(self::TERMINAL_CACHE_ID);
    }

    public static function execute(string $command): string
    {
        $command = preg_replace('/(\s+)/', ' ', trim($command));
        $cache = Cache::get(self::TERMINAL_CACHE_ID, []);
        $disposition = $cache['disposition'] ?? ['cd ' . App::basePath()];
        if (strpos($command, 'cd') === 0) {
            $disposition[] = $command;
            $command = '';
        }
        Cache::put(self::TERMINAL_CACHE_ID, \array_merge($cache, ['disposition' => $disposition]), self::TERMINAL_TIMEOUT);

        return stripcslashes(shell_exec( \implode(PHP_EOL, $disposition) . PHP_EOL . escapeshellcmd($command) . PHP_EOL));
    }
}