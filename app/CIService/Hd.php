<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/26
 * Time: 下午3:36
 */

namespace App\CIService;

use App\Http\Middleware\VerifyToken;

class Hd extends BaseCIService
{
    //session存的key值
    const SESSION_CI_HD_AID = 'ci_hd_aid';
    const SESSION_CI_HD_HD = 'ci_hd_hd';

    //返回活动的链接
    public function getHdUrl($aid, $token = null){
        if (!$token){
            $account = new Account();
            $token = $account->login(true);
        }
        return $this->domain . "/hd/main.php?action=hs_login.html&aid=$aid&token=$token";
    }

    //活动助力成功接口
    public function registBack($aid, $hd, $account_id){
        $params = [
            'aid' => $aid,
            'hd' => $hd,
            'account_id' => $account_id
        ];
        //签名token
        $token = (new VerifyToken())->getToken($params);
        $params['token'] = $token;
        $res = $this->post('/hd/main.php/act/hs/registBack.json', $params, false);

        //记录日志
        $this->log->info('Hd:'.__FUNCTION__, $res);
        return $res;
    }

    //查询活动用户
    public function actResult($aid, $type = '', $page = 1, $limit = 25){
        $params = [
            'aid' => $aid,
            'type' => $type,
            'page' => $page,
            'limit' => $limit
        ];
        //签名token
        $token = (new VerifyToken())->getToken($params);
        $params['token'] = $token;
        $res = $this->post('/hd/main.php/act/hs/actResult.json', $params, false);
        return $res;
    }

    //获取参团成功的用户
    public function joinAccounts($aid, $start, $end, $page = 1, $limit = 1000){
        $params = [
            'aid' => $aid,
            'start_time' => $start,
            'end_time' => $end,
            'page' => $page,
            'limit' => $limit
        ];
        //签名token
        $token = (new VerifyToken())->getToken($params);
        $params['token'] = $token;
        $res = $this->post('/hd/main.php/act/hs/joinAccounts.json', $params, false);
        return $res;
    }

    /**
     * 增加用户抽奖次数
     * @param $aid
     * @param $account_id
     * @param $num
     * @return array|mixed
     */
    public function addChance($aid, $account_id, $num){
        $params = [
            'aid' => $aid,
            'account_id' => $account_id,
            'num' => $num
        ];
        $params['token'] = (new VerifyToken())->getToken($params);
        $res = $this->post('/hd/main.php/act/hsdraw/addChance.json', $params, false);
        return $res;
    }

    /**
     * 获取用户抽奖活动信息
     * @param $aid
     * @param $account_id
     * @return array|mixed
     */
    public function getChance($aid, $account_id){
        $params = [
            'aid' => $aid,
            'account_id' => $account_id
        ];
        $params['token'] = (new VerifyToken())->getToken($params);
        $res = $this->post('/hd/main.php/act/hsdraw/getChance.json', $params, false);
        return $res;
    }

    public function query($table, $params)
    {
        $params['table'] = $table;
        return $this->get('/hd/main.php/act/hs/queryHsTable.json', $params, false);
    }
}