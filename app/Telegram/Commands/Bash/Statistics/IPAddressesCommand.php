<?php

namespace App\Telegram\Commands\Bash\Statistics;


class IPAddressesCommand extends BaseCommand
{
    protected $commandBody = '
    ip_addresses() {

      local ifconfigCmd=$(type -P ifconfig)
      local digCmd=$(type -P dig)
    
      externalIp=$($digCmd +short myip.opendns.com @resolver1.opendns.com)
    
      $ECHO -n "["
    
      for item in $($ifconfigCmd | $GREP -oP "^[a-zA-Z0-9:]*(?=:)")
      do
          $ECHO -n "{\"interface\" : \""$item"\", \"ip\" : \"$( $ifconfigCmd $item | $GREP "inet" | $AWK \'{match($0,"inet (addr:)?([0-9.]*)",a)}END{ if (NR != 0){print a[2]; exit}{print "none"}}\')\"}, "
      done
    
      $ECHO "{ \"interface\": \"external\", \"ip\": \"$externalIp\" } ]" | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'ip_addresses';

    /**
     * @var string Command Description
     */
    protected $description = 'Get IP addresses Statistics';

    protected function formatOutput(): string
    {
        return $this->textTableOutput();
    }
}

