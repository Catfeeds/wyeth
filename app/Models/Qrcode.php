<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qrcode extends Model
{

    /**
     * 备孕
     */
    const STAGET_PREGNANT = 1;

    /**
     * 已出来
     */
    const STAGET_BORN = 2;

    protected $table = 'qrcodes';

    protected $casts = [
        'display_channel' => 'array',
    ];

    public function getStageStrAttribute()
    {
        switch($this->attributes['stage']){
            case self::STAGET_PREGNANT: $status = '孕期';break;
            case self::STAGET_BORN: $status = '宝宝已出生';break;
            default : $status = '－－';break;
        }
        return $status;
    }
}
