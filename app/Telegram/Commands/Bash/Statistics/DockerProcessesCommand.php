<?php

namespace App\Telegram\Commands\Bash\Statistics;


class DockerProcessesCommand extends BaseCommand
{

    protected $commandBody = '
    docker_processes() {
      local result=""
      local dockerCommand=$(type -P docker)
      local containers="$($dockerCommand ps | $AWK \'{if(NR>1) print $NF}\')"
    
      for i in $containers; do
      result="$result $($dockerCommand top $i axo pid,user,pcpu,pmem,comm --sort -pcpu,-pmem \
        | $HEAD -n 15 \
        | $AWK -v cnt="$i" \'BEGIN{OFS=":"} NR>1 {print "{ \"cname\": \"" cnt \
            "\", \"pid\": " $1 \
            ", \"user\": \"" $2 "\"" \
            ", \"cpu%\": " $3 \
            ", \"mem%\": " $4 \
            ", \"cmd\": \"" $5 "\"" "},"\
          }\')"
      done
    
      $ECHO "[" ${result%?} "]" | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'docker_processes';

    /**
     * @var string Command Description
     */
    protected $description = 'Show Docker processes';

    protected function formatOutput()
    {
        return $this->textTableOutput();
    }
}

