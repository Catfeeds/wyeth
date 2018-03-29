<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';

    const IDT_ADMIN = 0;
    const IDT_CONTENT = 2;
    const IDT_CHANNEL = 3;
    const IDT_MATERIEL = 4;

    public function getTypeAttribute()
    {
        switch($this->attributes['user_type']){
            case 0 : $status = '超级管理员';break;
            case 1 : $status = '运营人员';break;
            case 2 : $status = '下载课件';break;
            case 3 : $status = '上传课件';break;
            case 4 : $status = '大平台物料管理';break;
            case 5 : $status = '奖品管理';break;
            default : $status = '管理员';break;
        }
        return $status;
    }

    public function getCidsAttribute($row)
    {
        if (empty($row)) return [];
        return json_decode($row, true);
    }
}
