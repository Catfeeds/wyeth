<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseReview extends Model
{
    protected $table = 'course_review';

    const STATUS_YES = 1;

    const STATUS_NO = 0;

    protected $casts = [
        'q_and_a' => 'array',
        'section' => 'array',
    ];

    //替换图片域名
    public function getTeacherAvatarAttribute($value){
        return replaceUploadURL($value);
    }

    public function getGuideAttribute($value){
        return replaceUploadURL
        ($value);
    }

    public function getVideoCoverAttribute($value){
        return replaceUploadURL($value);
    }

    //替换音视频
    public function getAudioAttribute($value){
        return replaceUploadURL($value);
    }

    public function getVideoAttribute($value){
        return replaceUploadURL($value);
    }
}
