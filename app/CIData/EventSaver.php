<?php

/**
 * Created by PhpStorm.
 * User: canghai
 * Date: 28/06/2017
 * Time: 17:56
 */
namespace App\CIData;

class EventSaver
{
    const TMP_FILE = "/tmp/cidata-pending-events.txt";
    const SENDING_FILE = "/tmp/cidata-pending-events.txt.sending";

    public static function saveEvent($event)
    {
        $str = json_encode($event);
        file_put_contents(self::TMP_FILE, $str . "\n", FILE_APPEND | LOCK_EX);
    }

    public static function fetchEvents()
    {
        if (!file_exists(self::TMP_FILE)) {
            Utils::log("tmp file not exist");
            return array();
        }

        if (!self::renameFile()) {
            return array();
        }

        $contents = file_get_contents(self::SENDING_FILE);

        unlink(self::SENDING_FILE);

        $str_arr = explode("\n", $contents);

        $events = array();
        foreach ($str_arr as $str) {
            if (empty($str)) {
                continue;
            }
            $events[] = json_decode($str, true);
        }


        return $events;
    }

    private static function renameFile()
    {
        $f = fopen(self::TMP_FILE, "a");
        $success = false;
        $try_lock_times = 0;
        do {
            $try_lock_times++;
            $success = flock($f, LOCK_EX | LOCK_NB);

            if ($try_lock_times >= 3) {
                break;
            }
        } while ($success);

        if (!$success) {
            Utils::log("lock file failed");
            fclose($f);
            return false;
        }
        $success = rename(self::TMP_FILE, self::SENDING_FILE);

        flock($f, LOCK_UN);

        fclose($f);

        return $success;
    }
}