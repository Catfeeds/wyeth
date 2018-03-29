<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/12/28
 * Time: 下午2:40
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEnrollPush extends Model
{
    protected $table = 'user_enroll_push';

    protected $fillable = ['uid', 'openid', 'cid', 'push_time', 'status'];
}