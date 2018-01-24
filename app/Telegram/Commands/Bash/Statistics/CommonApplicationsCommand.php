<?php

namespace App\Telegram\Commands\Bash\Statistics;


use App\Helper\ArrayToTextTable;

class CommonApplicationsCommand extends BaseCommand
{

    protected $commandBody = 'common_applications() {
      result=$(whereis php node mysql mongo vim python ruby java apache2 nginx openssl vsftpd make \
      | $AWK -F: \'{if(length($2)==0) { installed="NO"; } else { installed="YES"; } \
        print \
        "{ \
          \"binary\": \""$1"\", \
          \"location\": \""$2"\", \
          \"installed\": \""installed"\" \
        },"}\')
    
      $ECHO "[" ${result%?} "]" | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'common_applications';

    /**
     * @var string Command Description
     */
    protected $description = 'Get info about most common applications';

    protected function formatOutput()
    {
        $resultingArray = [];
        foreach ((array)\json_decode($this->output, true) as $line) {
            $resultingArray[] = &$line;

            $location = preg_replace('/(\s+)/', ' ', trim($line['location']));
            $paths = explode(' ', $location);
            if (!empty($paths)) {
                $line['location'] = array_shift($paths);
                foreach ($paths as $path) {
                    $resultingArray[] = [
                        'binary' => '',
                        'location' => $path,
                        'installed' => ''
                    ];
                }
            }
            unset($line);
        }

        return '<code>' .
            (string)((new ArrayToTextTable($resultingArray))->render(true)) .
            '</code>';
    }
}

