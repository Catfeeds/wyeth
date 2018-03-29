<?php
namespace App\Services;

use App\Models\Course;
use App\Models\User;
use DateTime;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * 用户相关的
 * Class UserService
 * @package App\Services
 */
class UserService
{
    /**
     * @注意  这里返回的$user对象不能执行save操作
     * @param $uid
     * @param $course
     * @return User
     */
    public static function getChatUser($uid, Course $course)
    {
        if ($uid == User::CHAT_UID_ANCHOR) {
            $user = new User();
            $user->id = $uid;
            $user->nickname = 'Miss惠';
            $user->avatar = env('STATIC_URL') . '/mobile/img/anchor_avatar.png';
        } elseif ($uid == User::CHAT_UID_TEACHER) {
            $user = new User();
            $user->id = $uid;
            $user->nickname = $course->teacher_name;
            $user->avatar = $course->teacher_avatar;
        } else {
            $user = User::find($uid);
        }
        return $user;
    }

    /**
     * 获取用户的userType
     * @param User $user
     * @return int
     */
    public static function getUserType(User $user)
    {
        $userType = User::TYPE_USER;
        if ($user->id == User::CHAT_UID_ANCHOR) {
            $userType = User::TYPE_ANCHOR;
        } elseif ($user->id == User::CHAT_UID_TEACHER) {
            $userType = User::TYPE_TEACHER;
        }
        return $userType;
    }

    /**
     * 功能：根据宝宝生日计算出宝宝年龄或者预产期
     * @param $user User
     * @return string
     */
    public static function babyAgeOrExpected(User $user)
    {
        $baby_birthday = $user->baby_birthday;
        $today = date('Y-m-d', time());
        $date1 = new DateTime("$today");
        $date2 = new DateTime("$baby_birthday");
        $diff = $date1->diff($date2);
        if (strtotime($baby_birthday) < strtotime($today)) {
            $y = $diff->y ? $diff->y . '岁' : '';
            $m = $diff->m ? $diff->m . '个月' : '';
            $d = $diff->d ? $diff->d . '天' : '';
            $d = $diff->y >= 1 ? '' : $d;
            $remark = '（宝宝' . $y . $m . $d . '）';
            $remark = strtotime($baby_birthday) <= strtotime('2012-01-01') ? '' : $remark;
        } else if (strtotime($baby_birthday) == strtotime($today)) {
            $remark = '（预产期是今天）';
        } else {
            $m = $diff->m ? $diff->m . '个月' : '';
            $d = $diff->d ? $diff->d . '天' : '';
            $remark = '（距离预产期还有' . $m . $d . '）';
            $remark = $diff->y ? '' : $remark;
        }
        return $remark;
    }

    /**
     * 通过奇数的接口来创建新用户
     * 创建前需要自行判断用户是否存在
     * @param string $openid
     * @param string $channel 来源
     * @return User
     */
    public static function createByOpenId($openid, $channel = '')
    {
        try {
            $user = new User();
            $user->openid = $openid;
            $user->channel = $channel;
            //查询用户的crm用户信息
            $crm = new Crm();
            $memberInfo = $crm->searchMemberInfo($openid);
            if ($memberInfo['Flag']) {
                $user->crm_province = $memberInfo['Province'];
                $user->crm_city = $memberInfo['City'];
                $user->baby_birthday = $memberInfo['MemberEDC'];
                $user->crm_status = ($memberInfo['Member'] == 1) ? 1 : 0;
                $user->crm_hasShop = $memberInfo['IsHaveShop'];
                $user->crm_NeverBuyIMF = $memberInfo['NeverBuyIMF'];
            }
            //查询用户关注状态
            $WxWyeth = new WxWyeth();
            $user->subscribe_status = $WxWyeth->getSubscribeStatus($openid);
            $user->save();
            return $user;
        } catch (QueryException $e) {
            // 保存失败时, 再能过openid查一次
            $user = User::where('openid',  $openid)->first();
            if (!$user) {
                throw $e;
            }
            return $user;
        }
    }
}
