<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/9/21
 * Time: 上午10:22
 */
namespace App\Repositories;

use App\Models\AppConfig;
use App\Models\CiAppConfig;
use App\Models\User;
use App\Models\Advertise;
use App\Services\Crm;
use Auth;

class AdvertiseRepository extends BaseRepository{
    public function getAd($position, $brand){
        if(!$brand){
            $brand = 0;
        }
        return $this->returnData(Advertise::getAdvertise($position, $brand));
    }
}