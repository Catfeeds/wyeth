<?php

namespace App\Services;

use App\Models\AppConfig;
use App\Models\User;
use Cache;

/**
 * 系统配置类
 */
class AppConfigService
{
    public static function carousels1($userType = User::USERTYPE_NN, $flush = false)
    {
        $type = 'flashPics1' . $userType;
        $currentTypePics = Cache::get($type);
        if($currentTypePics == null || $flush == true){
            $pics = AppConfig::where('module', 'index')->where('key', 'carousels1')->orderBy('displayorder')->get()->pluck('data')->toArray();
            $reorderPics = [];
            foreach($pics as $pic){
                $tempAttr = isset($pic['attr']) ? $pic['attr'] : User::USERTYPE_NN;
                $reorderPics[$tempAttr][] = $pic;
            }
            //

            $currentTypePics = [];
            do{
                //
                if(isset($reorderPics[$userType])){
                    $currentTypePics = $reorderPics[$userType];
                    break;
                }
                $userType--;
            }
            while($userType > 0);
            Cache::put($type, $currentTypePics, 43200);
        }

        return $currentTypePics;
    }

    //
    public static function carousels2($userType = User::USERTYPE_NN, $flush = false)
    {
        $type = 'flashPics2' . $userType;
        $currentTypePics = Cache::get($type);
        if($currentTypePics == null || $flush == true){
            $pics = AppConfig::where('module', 'index')->where('key', 'carousels2')->orderBy('displayorder')->get()->pluck('data')->toArray();

            $reorderPics = [];
            foreach($pics as $pic){
                $tempAttr = isset($pic['attr']) ? $pic['attr'] : User::USERTYPE_NN;
                $reorderPics[$tempAttr][] = $pic;
            }
            //
            $currentTypePics = [];
            do{
                //
                if(isset($reorderPics[$userType])){
                    $currentTypePics = $reorderPics[$userType];
                    break;
                }
                $userType--;
            }
            while($userType > 0);

            Cache::put($type, $currentTypePics, 43200);
        }

        return $currentTypePics;
    }

    public static function carouselsAlipay($userType = User::USERTYPE_NN, $flush = false)
    {
        $type = 'flashPics3' . $userType;
        $currentTypePics = Cache::get($type);
        if($currentTypePics == null || $flush == true){
            $pics = AppConfig::where('module', 'index')->where('key', 'carousels_alipay')->orderBy('displayorder')->get()->pluck('data')->toArray();
            $reorderPics = [];
            foreach($pics as $pic){
                $tempAttr = isset($pic['attr']) ? $pic['attr'] : User::USERTYPE_NN;
                $reorderPics[$tempAttr][] = $pic;
            }
            //

            $currentTypePics = [];
            do{
                //
                if(isset($reorderPics[$userType])){
                    $currentTypePics = $reorderPics[$userType];
                    break;
                }
                $userType--;
            }
            while($userType > 0);
            Cache::put($type, $currentTypePics, 43200);
        }

        return $currentTypePics;
    }
}
