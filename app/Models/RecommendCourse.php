<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendCourse extends Model
{
    protected $table = 'recommend_course';

    protected $fillable = ['uid', 'openid', 'sign_up_course_id', 'sign_up_course_stage', 'recommend_course_id', 'recommend_course_stage'];
}
