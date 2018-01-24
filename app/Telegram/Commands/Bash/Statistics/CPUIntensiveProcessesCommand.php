<?php

namespace App\Telegram\Commands\Bash\Statistics;


class CPUIntensiveProcessesCommand extends BaseCommand
{

    protected $commandBody = 'cpu_intensive_processes() {
      result=$($PS axo pid,user,pcpu,rss,vsz,comm --sort -pcpu,-rss,-vsz \
        | $HEAD -n 15 \
        | $AWK \'BEGIN{OFS=":"} NR>1 {print "{ \"pid\": " $1 \
                ", \"user\": \"" $2 "\"" \
                ", \"cpu%\": " $3 \
                ", \"rss\": " $4 \
                ", \"vsz\": " $5 \
                ", \"cmd\": \"" $6 "\"" "},"\
              }\')
    
      $ECHO "[" ${result%?} "]" | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'cpu_intensive_processes';

    /**
     * @var string Command Description
     */
    protected $description = 'Show CPU intensive processes';

    protected function formatOutput()
    {
        return $this->textTableOutput();
    }
}

