<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/8/23
 * Time: 下午2:34
 */

namespace App\Services;


use App\CIService\BaseCIService;
use GuzzleHttp\Client;

class Pregnotice extends BaseCIService
{
    //孕期提醒相关接口


    public function __construct()
    {
        $this->domain = 'http://api.ladybirdedu.com';

        parent::__construct();
    }

    /**
     * 注册孕期提醒返回preg_id
     * @param $uid
     * @param $nickname
     * @param $avatar
     * @param $date
     * @param null $status
     * @return int|mixed
     */
    public function getPregId($uid, $nickname, $avatar, $date, $status = null){
        $uri = '/app/v1/user/token/login';

        //date默认为今天
        $date_time = strtotime($date);
        if (!$date_time || $date == '0000-00-00'){
            $date = date('Y-m-d');
            $status = 1;
        }
        if ($status === null){
            if ($date_time > time()){
                $status = 2; //宝宝
            }else{
                $status = 1; //孕期
            }
        }
        $params = [
            'user_id' => $uid,
            'nickname' => $nickname,
            'avatar' => $avatar,
            'date' => $date,
            'status' => $status,
        ];
        $res = $this->post($uri, $params, false);
        if (isset($res['status']) && $res['status'] == 'success'){
            if (isset($res['data']['userInfo']['id'])){
                return $res['data']['userInfo']['id'];
            }
        }
        return 0;
    }

    public function getHome($pregdate){
        $uri = '/v1/home';

        if (!$pregdate || $pregdate == '0000-00-00'){
            $pregdate = date('Y-m-d');
        }
        //当天算孕期
        if (strtotime($pregdate) >= strtotime(date('Y-m-d'))){
            $is_baby = 0;
        }else{
            $is_baby = 1;
        }

        $params = [
            'pregdate' => $pregdate,
            'is_baby' => $is_baby
        ];
        $res = $this->get($uri, $params, false);
        $data = $res['data'];
        $baby = &$data['baby'];
        if ($is_baby){
            //将育儿版的数据整合孕期版一样的格式
            $baby['weight'] = $baby['one'];
            $baby['height'] = $baby['three'];
            $baby['pic'] = 'http://wyeth-uploadsites.nibaguai.com/weex/images/default/default_baby_avatar.png';
        }
        return $data;
    }

}