<?php

namespace App\Telegram\Commands\Bash\Statistics;


class CurrentRAMCommand extends BaseCommand
{

    protected $commandBody = '
    current_ram() {
    
      local memInfoFile="/proc/meminfo"
    
      # References:
      #   Calculations: http://zcentric.com/2012/05/29/mapping-procmeminfo-to-output-of-free-command/
      #   Fields: https://www.kernel.org/doc/Documentation/filesystems/proc.txt
    
      memInfo=$($CAT $memInfoFile | $GREP \'MemTotal\|MemFree\|Buffers\|Cached\')
    
      $ECHO $memInfo | $AWK \'{print "{ \"total\": " ($2/1024) ", \"used\": " ( ($2-($5+$8+$11))/1024 ) ", \"available\": " (($5+$8+$11)/1024) " }"  }\' | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'current_ram';

    /**
     * @var string Command Description
     */
    protected $description = 'Get current RAM information';

    protected function formatOutput()
    {
        return $this->listOutput();
    }
}

