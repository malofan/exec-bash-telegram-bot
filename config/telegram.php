<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot API Access Token [REQUIRED]
    |--------------------------------------------------------------------------
    |
    | Your Telegram's Bot Access Token.
    | Example: 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11
    |
    | Refer for more details:
    | https://core.telegram.org/bots#botfather
    |
    */
    'bot_token' => env('TELEGRAM_BOT_TOKEN', 'YOUR-BOT-TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Asynchronous Requests [Optional]
    |--------------------------------------------------------------------------
    |
    | When set to True, All the requests would be made non-blocking (Async).
    |
    | Default: false
    | Possible Values: (Boolean) "true" OR "false"
    |
    */
    'async_requests' => env('TELEGRAM_ASYNC_REQUESTS', false),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Handler [Optional]
    |--------------------------------------------------------------------------
    |
    | If you'd like to use a custom HTTP Client Handler.
    | Should be an instance of \Telegram\Bot\HttpClients\HttpClientInterface
    |
    | Default: GuzzlePHP
    |
    */
    'http_client_handler' => null,

    /*
    |--------------------------------------------------------------------------
    | Register Telegram Commands [Optional]
    |--------------------------------------------------------------------------
    |
    | If you'd like to use the SDK's built in command handler system,
    | You can register all the commands here.
    |
    | The command class should extend the \Telegram\Bot\Commands\Command class.
    |
    | Default: The SDK registers, a help command which when a user sends /help
    | will respond with a list of available commands and description.
    |
    */
    'commands' => [
        Telegram\Bot\Commands\HelpCommand::class,
        App\Telegram\Commands\Bash\StartTerminalCommand::class,
        App\Telegram\Commands\Bash\StopTerminalCommand::class,
        App\Telegram\Commands\Bash\Statistics\ArpCacheCommand::class,
        App\Telegram\Commands\Bash\Statistics\BandwidthCommand::class,
        App\Telegram\Commands\Bash\Statistics\CommonApplicationsCommand::class,
        App\Telegram\Commands\Bash\Statistics\CPUInfoCommand::class,
        App\Telegram\Commands\Bash\Statistics\CPUIntensiveProcessesCommand::class,
        App\Telegram\Commands\Bash\Statistics\CPUUtilizationCommand::class,
        App\Telegram\Commands\Bash\Statistics\CronHistoryCommand::class,
        App\Telegram\Commands\Bash\Statistics\CurrentRAMCommand::class,
        App\Telegram\Commands\Bash\Statistics\DiskPartitionsCommand::class,
        App\Telegram\Commands\Bash\Statistics\DockerProcessesCommand::class,
        App\Telegram\Commands\Bash\Statistics\DownloadTransferRateCommand::class,
        App\Telegram\Commands\Bash\Statistics\GeneralInfoCommand::class,
        App\Telegram\Commands\Bash\Statistics\IOStatsCommand::class,
        App\Telegram\Commands\Bash\Statistics\IPAddressesCommand::class,
        App\Telegram\Commands\Bash\Statistics\LastLogInformationCommand::class,
        App\Telegram\Commands\Bash\Statistics\LoadAVGCommand::class,
        App\Telegram\Commands\Bash\Statistics\LoggedInUsersCommand::class,
        App\Telegram\Commands\Bash\Statistics\MemcachedCommand::class,
        App\Telegram\Commands\Bash\Statistics\MemoryInformationCommand::class,
        App\Telegram\Commands\Bash\Statistics\NetworkConnectionsCommand::class,
        App\Telegram\Commands\Bash\Statistics\NumberOfCPUCoresCommand::class,
        App\Telegram\Commands\Bash\Statistics\PM2StatsCommand::class,
        App\Telegram\Commands\Bash\Statistics\RAMIntensiveProcessesCommand::class,
        App\Telegram\Commands\Bash\Statistics\LastLogInformationCommand::class,
    ],
];
