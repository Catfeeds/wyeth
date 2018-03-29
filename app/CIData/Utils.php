<?php

/**
 * Created by PhpStorm.
 * User: canghai
 * Date: 28/06/2017
 * Time: 18:35
 */

namespace App\CIData;

class Utils
{
    const LOG_FILE = "/tmp/cidata-events.log";

    public static function currentTime()
    {
        return intval(microtime(true) * 1000);
    }

    public static function sendToServer($events)
    {

        $data = json_encode(array("events" => $events));
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://cidata-gate.oneitfarm.com/api/raw_event",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain;charset=UTF-8"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            self::log("cURL Error #:" . $err);
        } else {
            self::log("sending event result:" . $response);
        }
    }

    public static function log($content)
    {
        $d = date("Y-m-d H:i:s", time());
        $logContent = $d . " " . $content . "\n";
        file_put_contents(self::LOG_FILE, $logContent, FILE_APPEND);
    }
}

