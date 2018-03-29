<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCounter extends BaseModel
{
    protected $fillable = ['item_id', 'item_type'];
    protected $table = 'course_counters';
}
