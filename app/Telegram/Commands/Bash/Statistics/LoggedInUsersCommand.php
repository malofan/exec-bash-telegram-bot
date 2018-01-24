<?php

namespace App\Telegram\Commands\Bash\Statistics;


class LoggedInUsersCommand extends BaseCommand
{
    protected $commandBody = '
    logged_in_users() {
      local whoCommand=$(type -P w)
    
      result=$(COLUMNS=300 $whoCommand -h | $AWK \'{print "{\"user\": \"" $1 "\", \"from\": \"" $3 "\", \"when\": \"" $4 "\"},"}\')
    
      $ECHO [ ${result%?} ] | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'logged_in_users';

    /**
     * @var string Command Description
     */
    protected $description = 'Show Logged In Users';

    protected function formatOutput(): string
    {
        return $this->textTableOutput();
    }
}

