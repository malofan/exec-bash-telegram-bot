<?php

namespace App\Telegram\Commands\Bash\Statistics;


class NumberOfCPUCoresCommand extends BaseCommand
{
    protected $commandBody = '
    number_of_cpu_cores() {
      local numberOfCPUCores=$($GREP -c \'model name\' /proc/cpuinfo)
    
      if [ -z $numberOfCPUCores ]; then
        echo "Cores cannot be found";
      else
        echo $numberOfCPUCores;
      fi
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'number_of_cpu_cores';

    /**
     * @var string Command Description
     */
    protected $description = 'Get CPU Core number';

    protected function formatOutput()
    {
        return '<b>' . $this->output . '</b>';
    }
}

