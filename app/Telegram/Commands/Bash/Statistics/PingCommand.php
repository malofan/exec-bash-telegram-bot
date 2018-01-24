<?php

namespace App\Telegram\Commands\Bash\Statistics;


class PingCommand extends BaseCommand
{
    private $ipPool = [
        '127.0.0.1',
        '127.0.0.2'
    ];
    protected $commandBody = '
    # http://askubuntu.com/questions/413367/ping-multiple-ips-using-bash
    ping() {
        local pingCmd=$(type -P ping)
        local XARGS=$(type -P xargs)
        local numOfLinesInConfig=2
        local result=\'\'
    
        $ECHO \'127.0.0.1 8.8.8.8\' | $XARGS -n1\
        |  while read output
            do
                singlePing=$($pingCmd -qc 2 $output \
                | $AWK -F/ \'BEGIN { endLine="}," } \
                /^rtt/ { \
                  if (\'$numOfLinesInConfig\'==1){\
                    endLine="}"\
                  }\
                  {print "{"}\
                  {print "\"host\":\" \'$output\' \","}\
                  {print "\"ping\": " $5}\
                  {print endLine}\
                  }\' \
                )
                numOfLinesInConfig=$(($numOfLinesInConfig-1))
                result=$result$singlePing
                if [ $numOfLinesInConfig -eq 0 ]
                    then
                        $ECHO [$result]
                fi
            done \
        | $SED \'s/\},]/}]/g\' \
      | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'ping';

    /**
     * @var string Command Description
     */
    protected $description = 'Get IO Statistics';

    protected function formatOutput(): string
    {
        return $this->textTableOutput();
    }
}

