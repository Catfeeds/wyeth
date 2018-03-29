<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseApply extends Model
{
    protected $table = 'course_apply';

    protected $primaryKey = 'id';

    public function admin()
    {
        return $this->hasOne('App\Models\Admin', 'id', 'account_id');
    }

    public function getStatusAttribute($value)
    {
        switch($value){
            case 0 : $status = '待审核';break;
            case 1 : $status = '通过';break;
            case 2 : $status = '驳回，'.$this->attributes['refuse_reason'];break;
            default : $status = '待审核';break;
        }
        return $status;
    }

    public function getActionAttribute()
    {
        switch($this->attributes['status']){
            case 0 : $action = '<a href="/admin/course/applyVerify?type=1&id='.$this->attributes['id'].'" class="btn btn-primary btn-xs" style="margin-right:10px;">'.
                                '通过'.
                                '</a>'.
                                '<a class="btn btn-danger btn-xs" data-toggle="modal" href="#myModal'.$this->attributes['id'].'">驳回</a>';break;
            case 1 : $action = '已通过';break;
            case 2 : $action = '已驳回，'.$this->attributes['refuse_reason'];break;
            default : $action = '';break;
        }
        return $action;
    }
}