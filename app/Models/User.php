<?php
namespace App\Models;

use App\Models\Qrcode;
use App\Models\UserInQrcode;
use App\Services\Crm;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use DateTime;

class User extends Model implements AuthenticatableContract,
AuthorizableContract,
CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user';

    protected $primaryKey = 'id';

    //微信用户
    const OPENID_TYPE_WX = 1;

    //手Q用户
    const OPENID_TYPE_SQ = 2;

    /**
     * CRM 有主 ALL
     */
    const SHOP_ALL = 1;

    /**
     * CRM 有主 有
     */
    const SHOP_HAS = 2;

    /**
     * CRM 有主 无
     */
    const SHOP_NO = 3;

    /**
     * 用户类型 普通用户
     */
    const TYPE_USER = 1;

    /**
     * 用户类型 主持人
     */
    const TYPE_ANCHOR = 2;

    /**
     * 用户类型 讲师
     */
    const TYPE_TEACHER = 3;

    /**
     * 关注状态 是
     */
    const WX_SUBSCRIBE_STATUS_YES = 1;

    /**
     * 关注状态 否
     */
    const WX_SUBSCRIBE_STATUS_NO = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['openid', 'nickname', 'mobile', 'type', 'mini_openid', 'unionid'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
//    protected $hidden = ['remember_token'];

    // public $timestamps = false;

    /**
     * 没有关注，没有hasShop
     */
    const  USERTYPE_NN= 1;

    /**
     * 有关注，没有hasShop
     */
    const  USERTYPE_SN= 2;

    /**
     * 没有关注，有hasShop
     */
    const  USERTYPE_NH= 3;

    /**
     * 有关注，有hasShop
     */
    const  USERTYPE_SH= 4;

    /**
     * 聊天特殊用户的uid 主持人
     */
    const CHAT_UID_ANCHOR = 101;

    /**
     * 聊天特殊用户的uid 讲师
     */
    const CHAT_UID_TEACHER = 102;

    /**
     * 无主非孕期
*/
    const USER_CI_NN = 0;

    /**
     * 有主非孕期
     */
    const USER_CI_ON = 1;

    /**
     * 无主孕期
     */
    const USER_CI_NP = 2;

    /**
     * 有主孕期
     */
    const USER_CI_OP = 3;

    /**
     * 非孕期
     */
    const USER_NPRE = 0;

    /**
     * 孕期
     */
    const USER_PRE = 1;


    public static function analysisUserType($user){
        if(!$user->subscribe_status){
            if(!$user->crm_hasShop){
                return self::USERTYPE_NN;
            }else{
                return self::USERTYPE_NH;
            }
        }else{
            if(!$user->crm_hasShop){
                return self::USERTYPE_SN;
            }else{
                return self::USERTYPE_SH;
            }
        }
    }

    public function setPrimaryKey()
    {

        $this->primaryKey = 'id';
    }

    private function updateOrCreateUserInQrcode ($user, $user_qrcode)
    {
        $day = strtotime($user->baby_birthday);
        $now = time();
        if ($day <= $now) {
             $qrcodes = Qrcode::where('stage', Qrcode::STAGET_BORN)->get()->toArray();
         } else {
             $qrcodes = Qrcode::where('stage', Qrcode::STAGET_PREGNANT)->get()->toArray();
         }
         foreach ($qrcodes as $k => $v) {
             if (!(in_array($user->type, $v['display_channel']))) {
                 unset($qrcodes[$k]);
             }
         }
         $qrcodes = array_values($qrcodes);
         $count = count($qrcodes);
         if ($count) {
             $key = $user->id % $count;
             $user->imgurl = $qrcodes[$key]['imgurl'];
             if (!$user_qrcode) {
                 $user_qrcode = new UserInQrcode();
             }
             $user_qrcode->uid = $user->id;
             $user_qrcode->qid = $qrcodes[$key]['id'];
             $user_qrcode->save();
             return $user;
         } else {
             $user->imgurl = '';
             return $user;
         }
    }

    public function getUserInfo($user)
    {
        if (!$user) {
            return false;
        }

        if (!(in_array($user->type, [User::OPENID_TYPE_WX, User::OPENID_TYPE_SQ]))) {
            $user->imgurl = '';
            return $user;
        }

        if ($user->type == User::OPENID_TYPE_WX) {
            $crm = new Crm();
            $memberInfo = $crm->searchMemberInfo($user->openid);
            if ($memberInfo['Flag'] && ($user->baby_birthday != $memberInfo['MemberEDC'] || $user->crm_NeverBuyIMF != $memberInfo['NeverBuyIMF'])) {
                $user->baby_birthday = $memberInfo['MemberEDC'];
                $user->crm_NeverBuyIMF = $memberInfo['NeverBuyIMF'];
                $user->save();
            }
        }

        $now = time();
        $day = strtotime($user->baby_birthday);
        if (!$day) {
            $user->display = 0;
        } else {
            // 生日为预产期 或 已出生三个月内的
            if (($day >= $now || ($now - $day) / 86400 <= 180) && $user->crm_NeverBuyIMF) {
                $user->display = 1;
            } else {
                $user->display = 0;
            }
        }

        if ($user->display == 0) {
            $user->imgurl = '';
            return $user;
        }

        $user_qrcode = UserInQrcode::where('uid', $user->id)->first();
        if (!$user_qrcode) {
              $user = $this->updateOrCreateUserInQrcode ($user, $user_qrcode);
              return $user;
        }

        $qrcode = Qrcode::find($user_qrcode->qid);
        if (!$qrcode) {
            $user = $this->updateOrCreateUserInQrcode ($user, $user_qrcode);
            return $user;
        }

        if (!is_array($qrcode->display_channel)) {
            $qrcode->display_channel = [];
        }

        if (!in_array($user->type, $qrcode->display_channel)) {
            $user = $this->updateOrCreateUserInQrcode ($user, $user_qrcode);
            return $user;
        }

        if ($day <= $now) {
            $stage = Qrcode::STAGET_BORN;
        } else {
            $stage = Qrcode::STAGET_PREGNANT;
        }

        if ($stage != $qrcode->stage) {
            $user = $this->updateOrCreateUserInQrcode ($user, $user_qrcode);
            return $user;
        }

        $user->imgurl = $qrcode->imgurl;
        $user->link = $qrcode->link;
        return $user;
    }

    /**
     +-------------------------------------------------------------------------
     * 功能：根据宝宝生日计算出宝宝年龄或者预产期
     +-------------------------------------------------------------------------
     * @param $baby_birthday    宝宝生日 格式：Y-m-d
     +-------------------------------------------------------------------------
     * @return string
     */
    public function babyAgeOrExpected ($baby_birthday) {
        $today = date('Y-m-d',time());
        $date1 = new DateTime("$today");
        $date2 = new DateTime("$baby_birthday");
        $diff = $date1->diff($date2);
        if (strtotime($baby_birthday) < strtotime($today)) {
            $y = $diff->y ? $diff->y.'岁' : '';
            $m = $diff->m ? $diff->m.'个月' : '';
            $d = $diff->d ? $diff->d.'天' : '';
            $d = $diff->y >= 1 ? '' :  $d;
            $remark = '（宝宝'.$y.$m.$d.'）';
            $remark = strtotime($baby_birthday) <= strtotime('2012-01-01') ? '': $remark;
        } else if (strtotime($baby_birthday) == strtotime($today)) {
            $remark = '（预产期是今天）';
        } else {
            $m = $diff->m ? $diff->m.'个月' : '';
            $d = $diff->d ? $diff->d.'天' : '';
            $remark = '（距离预产期还有'.$m.$d.'）';
            $remark = $diff->y ? '' : $remark;
        }
        return $remark;
    }

    //用于cidata统计用户属性
    public function getUserProperties($user){
        //用户edc属性只记录年月的时间戳
        if ($user->baby_birthday == '0000-00-00 00:00:00'){
            $user_edc = 0;
        }else{
            $user_edc = strtotime(date('Y-m-01', strtotime($user->baby_birthday)));
        }
        
        return [
            'edc' => $user_edc,
            'has_shop' => $user->youzhu,
            'brand' => (new Crm())->getMemberBrand()
        ];
    }
}
