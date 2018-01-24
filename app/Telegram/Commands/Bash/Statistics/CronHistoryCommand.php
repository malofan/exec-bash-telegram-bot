<?php

namespace App\Telegram\Commands\Bash\Statistics;


class CronHistoryCommand extends BaseCommand
{
    protected $commandBody = 'cron_history() {
      local cronLog=\'/var/log/syslog\'
      local numberOfLines=\'50\'
    
      # Month, Day, Time, Hostname, tag, user,
    
      result=$($GREP -m $numberOfLines CRON $cronLog \
        | $AWK \'{ s = ""; for (i = 6; i <= NF; i++) s = s $i " "; \
            print "{\"time\" : \"" $1" "$2" "$3 "\"," \
                "\"user\" : \"" $6 "\"," \
                "\"message\" : \"" $5" "gensub("\"", "\\\\\"", "g", s) "\"" \
              "},"
            }\'
        )
    
      $ECHO {${result%?}} | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'cron_history';

    /**
     * @var string Command Description
     */
    protected $description = 'Get Cron History';

    protected function formatOutput(): string
    {
        return $this->textTableOutput();
    }
}

