<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/8/2
 * Time: 15:13
 */

namespace App\Services;

use App\CIService\CIDataRecommend;

class TagService {
    public static function updateTag() {
        $ciRec = new CIDataRecommend();
        $ciRec->updateTag();
    }

    public static function updateItem ($course_id, $tag_weight) {
        $ciRec = new CIDataRecommend();
        $ciRec->uploadOneItem($course_id, $tag_weight);
    }
}