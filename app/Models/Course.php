<?php

namespace App\Models;

use App\Models\CourseTag;
use App\Services\Search;
use App\Services\CourseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    protected $table = 'course';

    protected $casts = [
        'id' => 'integer',
        'extend' => 'object',
    ];

    //课程无效
    const COURSE_INVALID = 0;

    //课程报名中
    const COURE_REG_STATUS = 1;

    //课程直播中
    const COURSE_LIVING_STATUS = 2;

    //课程已结束
    const COURSE_END_STATUS = 3;

    // status排序
    const COURSE_STATUS_ORDER = '2,1,3';

    /**
     * 关联标签
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'course_tags', 'cid', 'tid');
    }

    /**
     * 获取hot
     *
     * @param  string  $value
     * @return string
     */
    public function getHotAttribute($value)
    {
        if ($this->id) {
            return CourseService::hot($this->id);
        } else {
            return 0;
        }
    }

    /**
     * @param $value
     * @return string
     */
    public function getPlayStatusAttribute($value)
    {
        if ($value == 0) {
            return 'living';
        } else if ($value == 1) {
            return 'qAndA';
        }
    }

    /**
     * @param $value
     * @return string
     */
    public function getTypeAttribute($value) {
        if ($value == 0) {
            return 'live';
        } else if ($value == 1) {
            return 'recorded';
        }
    }

    /**
     * @param $value
     */
    public function setPlayStatusAttribute($value)
    {
        if ($value == 'living') {
            $this->attributes['play_status'] = 0;
        } else if ($value == 'qAndA') {
            $this->attributes['play_status'] = 1;
        }
    }

    /**
     * @param $value
     */
    public function setTypeAttribute($value) {
        if ($value == 'live') {
            $this->attributes['type'] = 0;
        } else if ($value == 'recorded') {
            $this->attributes['type'] = 1;
        }
    }

    //替换图片域名
    public function getImgAttribute($value){
        return replaceUploadURL($value);
    }

    public function getTeacherAvatarAttribute($value){
        return replaceUploadURL($value);
    }
}
