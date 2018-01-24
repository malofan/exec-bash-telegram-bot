<?php

namespace App\Telegram\Commands\Bash\Statistics;


class LastLogInformationCommand extends BaseCommand
{
    protected $commandBody = '
    last_logged() {

      local lastLogCommand=$(type -p lastlog)
    
      result=$($lastLogCommand -t 365 \
        | $AWK \'NR>1 {\
          print "{ \
            \"user\": \"" $1 "\", \
            \"ip\": \"" $3 "\","" \
            \"date\": \"" $5" "$6" "$7" "$8" "$9 "\"},"
          }\'
          )
      $ECHO [ ${result%?} ] | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'last_logged';

    /**
     * @var string Command Description
     */
    protected $description = 'Show recently logged accounts';

    protected function formatOutput(): string
    {
        return $this->textTableOutput();
    }
}

