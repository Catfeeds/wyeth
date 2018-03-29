<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/10
 * Time: 下午5:19
 */
namespace App\Repositories;

use App\Models\AppConfig;
use App\Models\CiAppConfig;
use App\Models\User;
use App\Models\Advertise;
use App\Services\Crm;
use Auth;

class AppConfigRepository
{
    public function getHomePlayback1(){
            return Advertise::getAdvertise(Advertise::POSITION_INDEX_TOP);
    }

    public function getHomePlayback2(){
            return Advertise::getAdvertise(Advertise::POSITION_INDEX_MID);
    }

    public function getHomeActivity(){
        $userType = (new UserRepository())->getUserType(Auth::id());
        $data = CiAppConfig::ci_cat_activity($userType, true);
        return $data;
    }

    public function getMiniHomeActivity(){
        $userType = (new UserRepository())->getUserType(Auth::id());
        $data = CiAppConfig::program_cat_activity($userType, true);
        return $data;
    }
}