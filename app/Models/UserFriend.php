<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFriend extends Model
{
    protected $table = 'user_friend';

    protected $fillable = ['from_uid', 'to_uid'];

}
