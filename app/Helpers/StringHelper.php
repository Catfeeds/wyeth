<?php

if (!function_exists('bc_base_convert')) {
    function bc_base_convert($value, $quellformat, $zielformat)
    {
        $vorrat = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if (max($quellformat, $zielformat) > strlen($vorrat)) {
            trigger_error('Bad Format max: ' . strlen($vorrat), E_USER_ERROR);
        }

        if (min($quellformat, $zielformat) < 2) {
            trigger_error('Bad Format min: 2', E_USER_ERROR);
        }

        $dezi = '0';
        $level = 0;
        $result = '';
        $value = trim((string) $value, "\r\n\t +");
        $vorzeichen = '-' === $value{0} ? '-' : '';
        $value = ltrim($value, "-0");
        $len = strlen($value);
        for ($i = 0; $i < $len; $i++) {
            $wert = strpos($vorrat, $value{$len - 1 - $i});
            if (false === $wert) {
                trigger_error('Bad Char in input 1', E_USER_ERROR);
            }

            if ($wert >= $quellformat) {
                trigger_error('Bad Char in input 2', E_USER_ERROR);
            }

            $dezi = bcadd($dezi, bcmul(bcpow($quellformat, $i), $wert));
        }
        if (10 == $zielformat) {
            return $vorzeichen . $dezi;
        }
        while (1 !== bccomp(bcpow($zielformat, $level++), $dezi));
        for ($i = $level - 2; $i >= 0; $i--) {
            $factor = bcpow($zielformat, $i);
            $zahl = bcdiv($dezi, $factor, 0);
            $dezi = bcmod($dezi, $factor);
            $result .= $vorrat{$zahl};
        }
        $result = empty($result) ? '0' : $result;
        return $vorzeichen . $result;
    }
}


if (!function_exists('get_room_id')){
    /**
     * 获取聊天室的真实room_id
     * @param  [type] $channel [description]
     * @param  [type] $id      [description]
     * @return [type]          [description]
     */
    function get_room_id($channel, $id) {
        if (!$channel) {
            $channel = 'CHAT_DEFAULT';
        }
        return $channel . ':' . $id;
    }
}

if (!function_exists('get_chat_uid')){
    /**
     * 获取聊天室的真实uid
     * @param  [type] $channel [description]
     * @param  [type] $uid     [description]
     * @return [type]          [description]
     */
    function get_chat_uid($channel, $uid) {
        if (!$channel) {
            $channel = 'CHAT_DEFAULT';
        }
        return $channel . '-UID:' . $uid;
    }
}

if (!function_exists('replaceUploadURL')){
    /**
     * 替换图片域名
     * @param $value
     * @return mixed
     */
    function replaceUploadURL($value){
        $value = str_replace('http://wyethup.img.apicase.io', env('QINIIU_UPLOAD_URL','http://wyethup.img.apicase.io'), $value);
        $value = str_replace('http://7xk3aj.com1.z0.glb.clouddn.com', env('QINIIU_UPLOAD_URL','http://7xk3aj.com1.z0.glb.clouddn.com'), $value);
        return $value;
    }
}

if (!function_exists('replaceUrlParams')){
    /**
     * 替换url中的参数
     * @param $url
     * @param $key
     * @param $value
     * @return string
     */
    function replaceUrlParams($url, $key, $value){
        $parse_params = parse_url($url);
        if (isset($parse_params['query'])) {
            parse_str($parse_params['query'], $query_params);
            $query_params[$key] = $value;

            $url = $parse_params['scheme'] . "://" . $parse_params['host'] . (isset($parse_params['path'])?$parse_params['path']:'') . '?' . http_build_query($query_params);
        } else {
            $url .= "?{$key}={$value}";
        }

        return $url;
    }
}

if (!function_exists('issetKey')){
    /**
     * $params[$key]存在的话返回$params[$key],否则返回''
     * @param $params
     * @param $key
     * @return string
     */
    function issetKey($params, $key){
        return isset($params[$key]) ? $params[$key] : '';
    }
}


if (!function_exists('rpx750')){
    /**
     * @param size $
     */
    function rpx750($size){
        return ($size/750*100).'vw';
    }
}

if (!function_exists('is_https')) {
    function is_https() {
        if (!isset($_SERVER['HTTP_HOST'])) {
            return false;
        }
        return strpos($_SERVER['HTTP_HOST'], '443') !== false;
    }
}

if (!function_exists('get_http')) {
    function get_http() {
        return is_https() ? 'https://' : 'http://';
    }
}