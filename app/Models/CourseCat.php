<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCat extends Model
{
    protected $table = 'course_cat';
    public $timestamps = false;

    /**
     * 如果是空的，读取缓存，并不检查过期时间。
     * @return [type] [description]
     */
    public static function alls()
    {
        $courseCats = self::get();
        return $courseCats;
    }

    private static function getCourseCountByCid($cid)
    {
        return Course::where('cid', $cid)->count();
    }

    //替换图片域名
    public function getImgAttribute($value){
        return replaceUploadURL($value);
    }

//    public function getShowTypeAttribute () {
//        switch ($this->attributes['show_type']) {
//            case 0: return '系列型';
//            case 1: return '专家型';
//            case 2: return '多图型';
//            case 3: return '介绍型';
//        }
//    }
}
