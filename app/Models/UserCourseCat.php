<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCourseCat extends BaseModel 
{

    // public $timestamps = false;

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['catid', 'uid'];
}
