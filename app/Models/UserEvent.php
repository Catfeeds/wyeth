<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{
    protected $table = 'user_events';

    protected $fillable = ['uid', 'user_type', 'cid', 'type', 'data'];
}
