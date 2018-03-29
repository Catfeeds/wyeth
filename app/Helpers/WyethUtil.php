<?php

/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/8/8
 * Time: 上午10:49
 */

namespace App\Helpers;

class WyethUtil
{
    /**
     * 随机bool
     * @param float $percent 随机概率
     * @param int $total 随机总数
     * @return bool
     */
    public static function randomBool($percent = 0.2, $total = 10000){
        $num = rand(1, $total);
        if ($num <= $percent * $total){
            return true;
        }
        return false;
    }

    /**
     * 根据配置文件获取静态html路径,有就返回文件内容
     * @param $test 测试使用
     * @return bool|string
     */
    public static function getHomeContent($test = null){
        $path = base_path('indexConfig.json');
        if ($test){
            $path = base_path("indexConfig{$test}.json");
        }
        if (file_exists($path)){
            $content = json_decode(file_get_contents($path));
            if ($content && isset($content[0])){
                $url = $content[0];
                $home = file_get_contents($url);
                if ($home){
                    return $home;
                }
            }
        }

        return false;
    }
    
    public static function getManifestName($test){
        if(config('oneitfarm.web_config_path')) {
            $path = config('oneitfarm.web_config_path');
        }else{
            $path = "/opt/ci123/www/alicdn/web{$test}/config.json";
        }
        $content = json_decode(@file_get_contents($path),true);
        if($content){
            return $content;
        }

        return false;
    }


    /**
     * 获取统计外链地址
     * @param $url string 跳转url
     * @param $action string 事件action
     * @param $params array 事件参数
     * @return string
     */
    public static function getParamsLink($url, $action, $params){
        $url = urlencode($url);
        $params = base64_encode(json_encode($params));
        return config('app.url') . "/params_link?url={$url}&action={$action}&params={$params}";
    }

    public static function generateTradeId () {
        list($usec, $sec) = explode(" ", microtime());
        $millisecond = round($usec * 1000);
        $millisecond = str_pad($millisecond, 3, '0', STR_PAD_LEFT);
        $millisecond = substr($millisecond, 0, 2);

        return date("YmdHis") .$millisecond . rand(1000, 9999);
    }

    public static function exportCsv($data = [], $header_data = [], $file_path = '', $has_boom = true){
        @unlink($file_path);
        $fp = fopen($file_path, 'a+');
        if ($has_boom){
            //写入utf-8的bom头
            fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        }
        if (!empty($header_data)) {
            fputcsv($fp, $header_data);
        }

        $count = count($data);
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $row = $data[$i];
                fputcsv($fp, $row);
            }
        }
        fclose($fp);
    }
}