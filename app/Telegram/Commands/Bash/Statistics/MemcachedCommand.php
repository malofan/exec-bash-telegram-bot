<?php

namespace App\Telegram\Commands\Bash\Statistics;


class MemcachedCommand extends BaseCommand
{

    protected $commandBody = '
    memcached() {
      local ncCommand=$(type -P nc)
    
      $ECHO "stats" \
        | $ncCommand -w 1 127.0.0.1 11211 \
        | $GREP \'bytes\' \
        | $AWK \'BEGIN {print "{"} {print "\"" $2 "\": " $3 } END {print "}"}\' \
        | $TR \'\r\' \',\' \
        | $SED \'N;$s/,\n/\n/;P;D\' \
        | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'memcached';

    /**
     * @var string Command Description
     */
    protected $description = 'Show Memcached';

    protected function formatOutput()
    {
        return $this->listOutput();
    }
}

