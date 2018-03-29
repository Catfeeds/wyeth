<?php namespace App\Http\Middleware;

use App\Helpers\SessionKey;
use App\Services\MqService;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\UserFriend;
use App\Models\UserFriendLog;
use App\Models\User;
use App\Models\CourseStat;


class Share
{

    public function handle($request, Closure $next)
    {
        //微信分享link
        if ($request->has('from_openid')) {
            $from_openid = $request->input('from_openid');
            $from = $request->input('from', '');
            $cid = $request->input('cid', 0);
            $uid = Auth::id();

            $model = new UserFriendLog();
            $model->from = $from;
            $model->to_uid = $uid ? : 0;
            $model->cid = $cid;
            $model->url = $request->url();
            $from_uid = User::where('openid', $from_openid)->pluck('id');
            $model->from_uid = $from_uid;
            $model->save();

            //生成好友关系
            if (isset($from_uid)) {
                UserFriend::firstOrCreate(['from_uid' => $from_uid, 'to_uid' => $uid ? : 0]);
                UserFriend::firstOrCreate(['from_uid' => $uid ? : 0, 'to_uid' => $from_uid]);
                $courseStat = CourseStat::firstOrCreate(['uid' => $from_uid, 'cid' => $cid]);
            }
            //如果是新用户那么给from_user加积分
            $new_user = Session::pull(SessionKey::NEW_USER, '');
            if($new_user == 'new_user'){
                MqService::increase($from_uid, MqService::ADD_TYPE_INVITE_NEW);
            }
            //记录用户统计日志
            if ($courseStat) {
                $path = $request->path();
                if($path == 'mobile/reg'){
                    $courseStat->share_sign_page_clicks = $courseStat->share_sign_page_clicks+1;
                }else if($path=='mobile/living'){
                    $courseStat->share_living_page_clicks = $courseStat->share_living_page_clicks+1;
                }else if($path=='mobile/end'){
                    $courseStat->share_review_page_clicks = $courseStat->share_review_page_clicks+1;
                }
                $courseStat->save();
            }
        }

        return $next($request);
    }

}