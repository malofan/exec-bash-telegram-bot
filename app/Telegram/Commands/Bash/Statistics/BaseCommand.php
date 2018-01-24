<?php

namespace App\Telegram\Commands\Bash\Statistics;

use App\Helper\ArrayToTextTable;
use Telegram\Bot\Commands\Command;

/**
 * Class HelpCommand.
 */
abstract class BaseCommand extends Command
{
    protected $output;
    protected $base = 'ECHO=$(type -P echo)
        SED=$(type -P sed)
        GREP=$(type -P grep)
        TR=$(type -P tr)
        AWK=$(type -P awk)
        CAT=$(type -P cat)
        HEAD=$(type -P head)
        CUT=$(type -P cut)
        PS=$(type -P ps)
        
        _parseAndPrint() {
          while read data; do
            $ECHO -n "$data" | $SED -e "s/\"/\\\\\"/g" | $TR -d "\n";
          done;
        }' . PHP_EOL;

    protected $commandBody = '';

    /**
     * {@inheritdoc}
     */
    final public function handle($arguments)
    {
        var_dump($arguments);

        $this->output = stripcslashes(shell_exec($this->base . $this->commandBody . PHP_EOL . " " . $this->name . PHP_EOL));

        $this->replyWithMessage([
            'text' => $this->formatOutput() ?: 'Command was executed with ERROR',
            'parse_mode' => 'HTML',
        ]);
    }

    protected function textTableOutput()
    {
        return '<code>' .
                (string)((new ArrayToTextTable((array)\json_decode($this->output, true)))->render(true)) .
            '</code>';
    }

    protected function listOutput()
    {
        $text = '';
        foreach ((array)\json_decode($this->output, true) as $key => $value) {
            $text .= '<b>' . $key . '</b>: ' . $value . PHP_EOL;
        }

        return $text;
    }

    abstract protected function formatOutput();
}

