<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRelation extends Model
{
    protected $table = 'user_relations';

    //课程无效
    const OPENID_TYPE_WX = 1;

    //课程报名中
    const OPENID_TYPE_SQ = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uid', 'openid', 'type'];
}
