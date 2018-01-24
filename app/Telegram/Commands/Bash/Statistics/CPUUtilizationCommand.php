<?php

namespace App\Telegram\Commands\Bash\Statistics;


class CPUUtilizationCommand extends BaseCommand
{
    //by Paul Colby (http://colby.id.au), no rights reserved ;)
    protected $commandBody = '
    cpu_utilization() {
      PREV_TOTAL=0
      PREV_IDLE=0
      iteration=0
    
      while [[ iteration -lt 2 ]]; do
        # Get the total CPU statistics, discarding the \'cpu \' prefix.
        CPU=(`$SED -n \'s/^cpu\s//p\' /proc/stat`)
        IDLE=${CPU[3]} # Just the idle CPU time.
    
        # Calculate the total CPU time.
        TOTAL=0
        for VALUE in "${CPU[@]}"; do
          let "TOTAL=$TOTAL+$VALUE"
        done
    
        # Calculate the CPU usage since we last checked.
        let "DIFF_IDLE=$IDLE-$PREV_IDLE"
        let "DIFF_TOTAL=$TOTAL-$PREV_TOTAL"
        let "DIFF_USAGE=(1000*($DIFF_TOTAL-$DIFF_IDLE)/$DIFF_TOTAL+5)/10"
        #echo -en "\rCPU: $DIFF_USAGE%  \b\b"
    
        # Remember the total and idle CPU times for the next check.
        PREV_TOTAL="$TOTAL"
        PREV_IDLE="$IDLE"
    
        # Wait before checking again.
        sleep 1
        iteration="$iteration+1"
      done
      $ECHO -en "$DIFF_USAGE"
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'cpu_utilization';

    /**
     * @var string Command Description
     */
    protected $description = 'Get CPU Utilization information';

    protected function formatOutput()
    {
        return $this->output . '%';
    }
}

