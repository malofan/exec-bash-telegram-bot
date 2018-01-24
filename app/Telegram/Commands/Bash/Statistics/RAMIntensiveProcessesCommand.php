<?php

namespace App\Telegram\Commands\Bash\Statistics;


class RAMIntensiveProcessesCommand extends BaseCommand
{
    protected $commandBody = '
    ram_intensive_processes() {

      local psCommand=$(type -P ps)
    
      result=$($psCommand axo pid,user,pmem,rss,vsz,comm --sort -pmem,-rss,-vsz \
        | $HEAD -n 15 \
        | $AWK \'NR>1 {print "{ \"pid\": " $1 \
                      ", \"user\": \"" $2 \
                      "\", \"mem%\": " $3 \
                      ", \"rss\": " $4 \
                      ", \"vsz\": " $5 \
                      ", \"cmd\": \"" $6 \
                      "\"},"}\')
    
      $ECHO [ ${result%?} ] | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'ram_intensive_processes';

    /**
     * @var string Command Description
     */
    protected $description = 'Show RAM Intensive Processes';

    protected function formatOutput(): string
    {
        return $this->textTableOutput();
    }
}

