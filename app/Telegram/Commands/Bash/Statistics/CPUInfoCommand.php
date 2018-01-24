<?php

namespace App\Telegram\Commands\Bash\Statistics;


class CPUInfoCommand extends BaseCommand
{

    protected $commandBody = 'cpu_info() {
      local lscpuCommand=$(type -P lscpu)
    
      result=$($lscpuCommand \
          | $AWK -F: \'{print "\""$1"\": \""$2"\"," }  \'\
          )
    
      $ECHO "{" ${result%?} "}" | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'cpu_info';

    /**
     * @var string Command Description
     */
    protected $description = 'Get CPU information';

    protected function formatOutput()
    {
        return $this->listOutput();
    }
}

