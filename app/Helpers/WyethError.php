<?php

/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/11
 * Time: 上午9:18
 */

namespace App\Helpers;

/**
 * Class WyethError
 * @package App\Helpers
 *
 * @property array UNKNOWN_ERROR
 * @property array INVALID_PARAM
 * user_token相关
 * @property array NO_USER_TOKEN
 * @property array USER_TOKEN_INVALID
 * @property array USER_TOKEN_EXPIRED
 *
 * user相关
 * @property array NO_USER
 * @property array NO_LOGIN
 *
 * course相关
 * @property array NO_COURSE
 * 
 * task相关
 * @property array TASK_TYPE_INVALID
 * @property array TASK_NOT_COMPLETE
 *
 * crm积分相关
 * @property array NO_UNIONID
 * @property array NO_SUFFICIENT_FUNDS
 *
 * 课程购买相关
 * @property array INVALID_TYPE
 * @property array NO_COURSE_OR_CAT
 * @property array BOUGHT_BEFORE
 * @property array NO_TRADE
 *
 * 订单相关
 * @property array ORDER_INVALID_PRICE
 * @property array ORDER_NOT_EXIST
 *
 * action相关
 * @property array ACTION_FAILED
 *
 * 讲师相关
 * @property array TEACHER_NOT_EXIST
 *
 * 标签相关
 * @property array TAG_NOT_EXIST
 *
 */

class WyethError
{
    private $properties = [
        'UNKNOWN_ERROR'     => [-1, '未知错误'],
        'INVALID_PARAM'     => [-2, '参数错误'],

        //user_token相关
        'NO_USER_TOKEN'             => [1000, 'user_token not provided'],
        'USER_TOKEN_INVALID'        => [1001, 'user_token invalid'],
        'USER_TOKEN_EXPIRED'        => [1002, 'user_token expired'],

        //user相关
        'NO_USER'                   => [2000, '用户不存在'],
        'NO_LOGIN'                  => [2001, '用户未登录'],

        //course相关
        'NO_COURSE'                 => [3000, '课程不存在'],
        
        //task相关
        'TASK_TYPE_INVALID'         => [4000, '任务类型不合法'],
        'TASK_NOT_COMPLETE'         => [4001, '任务未完成'],

        //crm积分相关
        'NO_UNIONID'                => [5000, 'union_id不存在'],
        'NO_SUFFICIENT_FUNDS'       => [5001, '余额不足'],

        //购买课程相关
        'INVALID_TYPE'              => [6000, '购买商品类型错误'],
        'NO_COURSE_OR_CAT'          => [6001, '课程或套课不存在'],
        'BOUGHT_BEFORE'             => [6002, '已购买过该课程'],
        'NO_TRADE'                  => [6003, '订单不存在'],

        //购买mq订单相关
        'ORDER_INVALID_PRICE'       => [6000, '价格不合法'],
        'ORDER_NOT_EXIST'           => [6001, '订单不存在'],

        //action相关
        'ACTION_FAILED'             => [7000, '操作失败'],

        //讲师相关
        'TEACHER_NOT_EXIST'         => [8000, '讲师不存在'],

        //标签相关
        'TAG_NOT_EXIST'             => [9000, '标签不存在']

    ];

    public function __get($key){
        if (array_key_exists($key, $this->properties)) {
            $property = $this->properties[$key];
            return array('ret' => -1, 'code' => $property[0], 'msg' => $property[1]);
        }
    }

    public function returnError($msg = '', $code = -1){
        return [
            'ret' => -1,
            'code' => $code,
            'msg' => $msg
        ];
    }
}