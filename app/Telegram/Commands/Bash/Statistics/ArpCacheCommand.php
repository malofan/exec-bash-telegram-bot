<?php

namespace App\Telegram\Commands\Bash\Statistics;


class ArpCacheCommand extends BaseCommand
{
    protected $commandBody = 'arp_cache() {
      local arpCommand=$(type -P arp)
    
      result=$($arpCommand | $AWK \'BEGIN {print "["} NR>1 \
                  {print "{ \"IP\": \"" $1 "\", " \
                        "\"Interface Type\": \"" $2 "\", " \
                        "\"MAC\": \"" $3 "\", " \
                        "\"Mask\": \"" $5 "\" }, " \
                        } \
                END {print "]"}\' \
            | $SED \'N;$s/},/}/;P;D\')
    
      if [ -z "$result" ]; then
        $ECHO {}
      else
        $ECHO $result | _parseAndPrint
      fi
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'arp_cache';

    /**
     * @var string Command Description
     */
    protected $description = 'Get ARP Table';

    protected function formatOutput(): string
    {
        return $this->textTableOutput();
    }
}

