<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTag extends BaseModel
{
    protected $table = 'course_tags';

    protected $fillable = ['cid', 'tid', 'type', 'weight'];

}
