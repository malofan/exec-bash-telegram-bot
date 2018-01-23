<?php

namespace App\Telegram\Commands\Bash\Statistics;


class NetworkConnectionsCommand extends BaseCommand
{
    protected $commandBody = '
    network_connections() {
      local netstatCmd=$(type -P netstat)
      local sortCmd=$(type -P sort)
      local uniqCmd=$(type -P uniq)
    
      $netstatCmd -ntu \
      | $AWK \'NR>2 {print $5}\' \
      | $sortCmd \
      | $uniqCmd -c \
      | $AWK \'BEGIN {print "["} {print "{ \"connections\": " $1 ", \"address\": \"" $2 "\" }," } END {print "]"}\' \
      | $SED \'N;$s/},/}/;P;D\' \
      | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'network_connections';

    /**
     * @var string Command Description
     */
    protected $description = 'Show Network Connections';

    protected function formatOutput(): string
    {
        return $this->textTableOutput();
    }
}

