<?php

namespace App\Http\Controllers\Mobile;

use App\CIService\Account;
use App\CIService\BaseCIService;
use App\CIService\CIDataQuery;
use App\CIService\Hd;
use App\CIService\Pay;
use App\Helpers\CacheKey;
use App\Helpers\WyethUtil;
use App\Jobs\SendTemplateMessage;
use App\Models\AppConfig;
use App\Models\Course;
use App\Models\CourseCat;
use App\Models\CourseReview;
use App\Models\CourseReviewQuestions;
use App\Models\CourseStat;
use App\Models\Courseware;
use App\Models\Tag;
use App\Models\CourseTag;
use App\Models\User;
use App\Models\SearchRecord;
use App\Models\UserEvent;
use App\Models\UserCourse;
use App\Models\RecommendCourse;
use App\Models\WoaapQrcode;
use App\Repositories\CourseListenRepository;
use App\Repositories\TagRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Services\CounterService;
use App\Services\CourseReviewService;
use App\Services\CourseService;
use App\Services\Email;
use App\Services\Leqee;
use App\Services\WoaapQrcodeService;
use App\Services\WoaapService;
use App\Services\WxWyeth;
use App\Services\UserEventService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;
use Cache;
use App\Jobs\SendTemplateMessageBySignUp;
use Log;
use Orzcc\Opensearch\Sdk\CloudsearchClient;
use Orzcc\Opensearch\Sdk\CloudsearchSearch;
use Orzcc\Opensearch\Sdk\CloudsearchDoc;

use App\Services\Crm;

class TestController extends Controller
{

    public function test(Request $request)
    {
        $user = Auth::user();
        $wx = new WxWyeth();
        $crm = new Crm();
        $qrcode = new WoaapQrcodeService();

        $openid = 'owtN6jt1yDZh0Tvfpgtd1rzR6Ekk';
        $user = User::where('openid', $openid)->first();

        $res1 = $crm->getMemberStatus($user->unionid);
        $res2 = $crm->searchMemberInfo($openid);
        var_dump($res1);
        var_dump($res2);
        die();

        $params = [
            'title' => "已成功邀请1名好友，离解锁课程还有一步之遥，加油",
            'content' => '邀请成功',
            'odate' => date('Y-m-d'),
            'address' => '',
            'remark' => '',
            'url' => config('app.url') . "/mobile/hd?aid=2",
//            'openid' => 'owtN6jgs-xifl5tKD1NyUldYtlKw',
            'openid' => 'owtN6jkVHmd-ctim1pBNtWSqmXkU'
        ];
        return $wx->pushpushCustomMessage($params, 6, false);
    }

    public function addChance(Request $request)
    {
        $account_id = (new Account())->getAccountId();
        $num = $request->input('num', 1);
        $aid = $request->input('aid', config('oneitfarm.draw_aid'));
        return (new Hd())->addChance($aid, $account_id, $num);
    }

    function getBindUrl($other_id){
        $params = [
            'other_id' => $other_id,
            'time' => time()
        ];
        ksort($params);
        $tmp = '';
        foreach ($params as $key => $value){
            $tmp .= $key . $value;
        }
        $tmp .= 'yCj8w0I13uNqm4VUyZHATjUKFdfQvS9W';
        $token = md5($tmp);
        $params['token'] = $token;
        return 'http://royf5fzo8i3cghm6qqbwstaky2hexpk4.oneitfarm.com/mobile/userBind?'.http_build_query($params);
    }

    public function action(Request $request, $action)
    {
        $this->$action($request);
    }

    public function clearAccount(Request $request){
        //5201284 许锦, 5863461 徐志祥, 5903280 靳金源
        $uids = [5201284, 5863461, 5903280];
        foreach ($uids as $uid){
            $user = User::find($uid);
            if ($user){
                $user->account_id = '';
                $user->crm_status = 0;
                $user->save();
                echo "清除$uid 的account_id<br>";
            }
        }
    }

    public function clearCache(Request $request){
        $uid = Auth::id();
        echo('清除uid:' . $uid . '的缓存');
        //首页数据
        Cache::pull(CacheKey::HOMEPAGE_DATA . $uid);
        //全部页面
        Cache::tags(CacheKey::ALL_PAGE_DATA . $uid)->flush();
        //清除用户品牌缓存
        Cache::pull(CacheKey::CACHE_KEY_USER_BRAND . $uid);

    }
}
