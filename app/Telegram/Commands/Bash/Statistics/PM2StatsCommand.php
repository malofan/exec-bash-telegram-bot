<?php

namespace App\Telegram\Commands\Bash\Statistics;


class PM2StatsCommand extends BaseCommand
{
    protected $commandBody = '
    pm2_stats() {
        #get data
        local data="$(pm2 list)"
        local tailCommand=$(type -P tail)
        #only process data if variable has a length
        #this should handle cases where pm2 is not installed
        if [ -n "$data" ]; then
            #start processing data on line 4
            #don\'t process last 2 lines
            json=$( $ECHO "$data" | $tailCommand -n +4 | $HEAD -n +2 \
            | $AWK 	\'{print "{"}\
                {print "\"appName\":\"" $2 "\","} \
                {print "\"id\":\"" $4 "\","} \
                {print "\"mode\":\"" $6 "\","} \
                {print "\"pid\":\"" $8 "\","}\
                {print "\"status\":\"" $10 "\","}\
                {print "\"restart\":\"" $12 "\","}\
                {print "\"uptime\":\"" $14 "\","}\
                {print "\"memory\":\"" $16 $17 "\","}\
                {print "\"watching\":\"" $19 "\""}\
                {print "},"}\')
            #make sure to remove last comma and print in array
            $ECHO "[" ${json%?} "]" | _parseAndPrint
        else
            #no data found
            $ECHO "[]" | _parseAndPrint
        fi
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'pm2_stats';

    /**
     * @var string Command Description
     */
    protected $description = 'Get PM2 Statistics';

    protected function formatOutput(): string
    {
        return $this->textTableOutput();
    }
}

