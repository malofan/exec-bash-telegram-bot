<?php

namespace App\Telegram\Commands\Bash\Statistics;


class DiskPartitionsCommand extends BaseCommand
{
    protected $commandBody = '
    disk_partitions() {
      local dfCommand=$(type -P df)
    
      result=$($dfCommand -Ph | $AWK \'NR>1 {print "{\"file_system\": \"" $1 "\", \"size\": \"" $2 "\", \"used\": \"" $3 "\", \"avail\": \"" $4 "\", \"used%\": \"" $5 "\", \"mounted\": \"" $6 "\"},"}\')
    
      $ECHO [ ${result%?} ] | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'disk_partitions';

    /**
     * @var string Command Description
     */
    protected $description = 'Get disk partitions';

    protected function formatOutput(): string
    {
        return $this->textTableOutput();
    }
}

