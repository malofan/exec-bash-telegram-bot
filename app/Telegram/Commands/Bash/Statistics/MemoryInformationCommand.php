<?php

namespace App\Telegram\Commands\Bash\Statistics;


class MemoryInformationCommand extends BaseCommand
{

    protected $commandBody = '
    memory_info() {
      $CAT /proc/meminfo \
        | $AWK -F: \'BEGIN {print "{"} {print "\"" $1 "\": \"" $2 "\"," } END {print "}"}\' \
        | $SED \'N;$s/,\n/\n/;P;D\' \
        | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'memory_info';

    /**
     * @var string Command Description
     */
    protected $description = 'Show Memory Information';

    protected function formatOutput()
    {
        return $this->listOutput();
    }
}

