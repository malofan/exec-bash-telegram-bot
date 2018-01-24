<?php

namespace App\Telegram\Commands\Bash\Statistics;


class LoadAVGCommand extends BaseCommand
{

    protected $commandBody = '
    load_avg() {

      local numberOfCores=$($GREP -c \'processor\' /proc/cpuinfo)
    
      if [ $numberOfCores -eq 0 ]; then
        numberOfCores=1
      fi
    
      result=$($CAT /proc/loadavg | $AWK \'{print "{ \"1_min_avg\": " ($1*100)/\'$numberOfCores\' ", \"5_min_avg\": " ($2*100)/\'$numberOfCores\' ", \"15_min_avg\": " ($3*100)/\'$numberOfCores\' "}," }\')
    
      $ECHO ${result%?} | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'load_avg';

    /**
     * @var string Command Description
     */
    protected $description = 'Get load AVG';

    protected function formatOutput()
    {
        return $this->listOutput();
    }
}

