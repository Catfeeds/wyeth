<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courseware extends Model
{
    protected $table = 'courseware';

    //替换图片域名
    public function getImgAttribute($value){
        return replaceUploadURL($value);
    }
}
