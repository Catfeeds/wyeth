<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/9/30
 * Time: 9:25
 */

namespace App\Repositories;

use App\Models\CiAppConfig;

class FlashPicRepository extends BaseRepository {
    public function getFlashPics () {
        $flashPics = CiAppConfig::where('module', CiAppConfig::module)->where('key', 'ci_flash_pic')->orderBy('displayorder')->get()->pluck('data')->toArray();

        $retPics = [];
        foreach ($flashPics as $flashPic) {
            if ($flashPic['displaystatus']) {
                $retPics[] = $flashPic;
            }
        }

        if (count($retPics) > 0) {
            if (count($retPics) > date("w")) {
                return $this->returnData($retPics[intval(date("w"))]);
            } else {
                return $this->returnData($retPics[0]);
            }
        } else {
            return $this->returnData();
        }
    }
}