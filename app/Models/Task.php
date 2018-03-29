<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //任务类型

    //签到
    const TYPE_SIGN = 'sign';

    //浏览课程页
    const TYPE_SCAN = 'scan';

    //分享
    const TYPE_SHARE = 'share';

    //分享图文
    const TYPE_SHARE_CMS = 'share_cms';


    protected $table = 'task';
}
