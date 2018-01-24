<?php

namespace App\Telegram\Commands\Bash\Statistics;


class DownloadTransferRateCommand extends BaseCommand
{

    protected $commandBody = '
    download_transfer_rate() {
        local files=(/sys/class/net/*)
        local pos=$(( ${#files[*]} - 1 ))
        local last=${files[$pos]}
    
        local json_output="{"
    
        for interface in "${files[@]}"
        do
            basename=$(basename "$interface")
    
            # find the number of bytes transfered for this interface
            in1=$($CAT /sys/class/net/"$basename"/statistics/rx_bytes)
    
            # wait a second
            sleep 1
    
            # check same interface again
            in2=$($CAT /sys/class/net/"$basename"/statistics/rx_bytes)
    
            # get the difference (transfer rate)
            in_bytes=$((in2 - in1))
    
            # convert transfer rate to KB
            in_kbytes=$((in_bytes / 1024))
    
            # convert transfer rate to KB
            json_output="$json_output \"$basename\": $in_kbytes"
    
            # if it is not the last line
            if [[ ! $interface == $last ]]
            then
                # add a comma to the line (JSON formatting)
                json_output="$json_output,"
            fi
        done
    
        # close the JSON object & print to screen
        $ECHO "$json_output}" | _parseAndPrint
    }';

    /**
     * @var string Command Name
     */
    protected $name = 'download_transfer_rate';

    /**
     * @var string Command Description
     */
    protected $description = 'Get download transfer rate';

    protected function formatOutput()
    {
        return $this->listOutput();
    }
}

