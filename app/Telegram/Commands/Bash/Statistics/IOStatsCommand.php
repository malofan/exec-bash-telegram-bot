<?php

namespace App\Telegram\Commands\Bash\Statistics;


class IOStatsCommand extends BaseCommand
{
    protected $commandBody = '
    io_stats() {
      result=$($CAT /proc/diskstats | $AWK \
              \'{ if($4==0 && $8==0 && $12==0 && $13==0) next } \
              {print "{ \"device\": \"" $3 "\", \"reads\": \""$4"\", \"writes\": \"" $8 "\", \"in_prog.\": \"" $12 "\", \"time\": \"" $13 "\"},"}\'
          )
    
      $ECHO [ ${result%?} ] | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'io_stats';

    /**
     * @var string Command Description
     */
    protected $description = 'Get IO Statistics';

    protected function formatOutput(): string
    {
        return $this->textTableOutput();
    }
}

