<?php

namespace App\Telegram\Commands\Bash\Statistics;


class BandwidthCommand extends BaseCommand
{

    protected $commandBody = 'bandwidth() {
      $CAT /proc/net/dev \
      | $AWK \'BEGIN {print "["} NR>2 {print "{ \"interface\": \"" $1 "\"," \
                " \"tx\": " $2 "," \
                " \"rx\": " $10 " }," } END {print "]"}\' \
      | $SED \'N;$s/,\n/\n/;P;D\' \
      | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'bandwidth';

    /**
     * @var string Command Description
     */
    protected $description = 'Get bandwidth information';

    protected function formatOutput()
    {
        return $this->textTableOutput();
    }
}

