<?php

/**
 * Created by PhpStorm.
 * User: canghai
 * Date: 28/06/2017
 * Time: 17:52
 */

namespace App\CIData;

class Cidata
{
    private static $appkey;
    private static $id = 0;

    public static function init($appKey)
    {
        self::$appkey = $appKey;
    }

    public static function sendEvent($userId, $channel, $userProperties, $eventId, $params)
    {
        if (empty(self::$appkey)) {
            throw new Error("appkey未设置");
        }

        if ($userProperties !== null && !is_array($userProperties)) {
            throw new Error("userProperties必须是array");
        }

        if ($params !== null && !is_array($params)) {
            throw new Error("args必须是array");
        }

        self::$id++;

        $event = array(
            "app_id" => self::$appkey,
            "platform" => "server",
            "id" => self::$id,
            "time" => Utils::currentTime(),
            "user_id" => $userId,
            "channel" => $channel,
            "user_properties" => $userProperties,
            "device_id" => "",
            "type" => "custom",
            "event_id" => $eventId,
            "params" => $params,
        );

        EventSaver::saveEvent($event);
    }
}