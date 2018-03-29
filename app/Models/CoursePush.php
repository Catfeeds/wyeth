<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/12
 * Time: 下午3:29
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CoursePush extends Model
{
    protected $table = 'course_push';

    protected $fillable = ['cid', 'type', 'push_time', 'status', 'push_num', 'remark', 'ext'];
    
    //课程推送状态
    //待推送
    const COURSE_PUSH_WAIT = 0;
    //已推送
    const COURSE_PUSH_SEND = 1;

    //推送类型

    const TYPE_FULI_DRAW = 10;
    const TYPE_FULI_DTC = 11;
    const TYPE_FULI_HEMA = 12;  //上过河马课的人推福利
    const TYPE_TJHG = 14;
    const TYPE_LOSE = 15;
    const TYPE_NEW_USER = 16;
    const TYPE_NEW_NOT_WEIKETANG = 17;
    const TYPE_SPECIAL = 18;    //特殊推送，Stella想一出是一出的推送
    const TYPE_ALL_USER = 19;    //2018-03-27 给定1700w openid推送，实际只有900w可以推
    const TYPE_ENROLL_PUSH = 20;    //慧摇报名推送


    public static function getTypeArray()
    {
        return [
            self::TYPE_FULI_DRAW => '前天上课用户推福利转转乐',
            self::TYPE_FULI_DTC => '前天上课用户推福利DTC',
            self::TYPE_FULI_HEMA => '前天上河马课用户推河马模板消息',
            self::TYPE_TJHG => '前天上课的用户',
            self::TYPE_LOSE => '30天内未上课的老用户',
            self::TYPE_NEW_USER => '30天内未上课的新用户',
            self::TYPE_NEW_NOT_WEIKETANG => '非微课堂渠道注册用户推送课程',
            self::TYPE_SPECIAL => '特殊推送',
            self::TYPE_ALL_USER => '1700w推送',
            self::TYPE_ENROLL_PUSH => '慧摇报名推送',
        ];
    }

    public static function getTypeChannel()
    {
        return [
            self::TYPE_FULI_DRAW => 'wxtpl_fuli_draw',
            self::TYPE_FULI_DTC => 'wxtpl_fuli_dtc',
            self::TYPE_FULI_HEMA => 'wxtpl_fuli_hema',
            self::TYPE_LOSE => 'wxtpl_lose',
            self::TYPE_TJHG => 'wxtpl_night',
            self::TYPE_NEW_USER => 'wxtpl_new',
            self::TYPE_NEW_NOT_WEIKETANG => 'wxtpl_not_weiketang',
            self::TYPE_SPECIAL => 'wxtpl_special',
            self::TYPE_ALL_USER => 'wxtpl_all_user',
            self::TYPE_ENROLL_PUSH => 'wxtpl_huiyao',
        ];
    }
}