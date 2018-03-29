<?php
namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\UserEvent;

/**
 * 用户事件
 * Class UserEventsService
 * @package App\Services
 */
class UserEventService
{

    /**
     * 计数器 回顾页点赞 key
     */
    const COUNTER_REVIEW_LIKE = 'userEventReviewLike';

    /**
     * @param User $user
     * @param Course $course
     * @return mixed
     */
    public static function giveAReviewLike(User $user, Course $course)
    {
        $uid = $user->id;
        $userType = $user->type;
        $cid = $course->id;
        $type = 'review_like';
        $data = 'give';
        $userEvent = UserEvent::where('uid', $uid)->where('cid', $cid)->where('type', $type)->first();
        if (!$userEvent) {
            $userEvent = new UserEvent();
            $userEvent->uid = $uid;
            $userEvent->user_type = $userType;
            $userEvent->cid = $cid;
            $userEvent->type = $type;
            $userEvent->data = $data;
            $userEvent->save();
            $res = $userEvent;
            self::countReviewLikeAllIncrement($cid);
        } else if ($userEvent->data != 'give') {
            $res = $userEvent->update([
                'data' => $data,
            ]);
            self::countReviewLikeAllIncrement($cid);
        } else {
            $res = $userEvent;
        }
        return $res;
    }

    /**
     * @param User $user
     * @param Course $course
     * @return bool
     */
    public static function cancelAReviewLike(User $user, Course $course)
    {
        $uid = $user->id;
        $cid = $course->id;
        $type = 'review_like';
        $data = 'cancel';
        $userEvent = UserEvent::where('uid', $uid)->where('cid', $cid)->where('type', $type)->first();
        if (!$userEvent) {
            return false;
        }
        if ($userEvent->data == 'cancel') {
            return true;
        }
        $res = $userEvent->update([
            'data' => $data,
        ]);
        self::countReviewLikeAllDecrement($cid);
        return $res;
    }

    public static function isLikeAReview($uid, $cid){
        $type = 'review_like';
        $userEvent = UserEvent::where('uid', $uid)->where('cid', $cid)->where('type', $type)->first();
        if(!$userEvent){
            return 0;
        }
        if($userEvent->data == 'cancel'){
            return 0;
        }
        return 1;
    }

    /**
     * @param Course $course
     * @return mixed
     */
    public static function reviewLikesNum(Course $course)
    {
        // $reviewLikesNum = UserEvent::where('cid', $cid)->where('type', $type)->where('data', $data)->count();
        return self::countReviewLikeAllGet($course->id);
    }

    /**
     * 回顾点赞数获取
     * @param $cid
     * @return bool|int
     */
    public static function countReviewLikeAllGet($cid)
    {
        return CounterService::get(self::COUNTER_REVIEW_LIKE, ['all', $cid]);
    }

    /**
     * 回顾点赞数加一
     * @param $cid
     * @param int $num
     * @return bool
     */
    public static function countReviewLikeAllIncrement($cid, $num = 1)
    {
        return CounterService::increment(self::COUNTER_REVIEW_LIKE, ['all', $cid], $num);
    }

    /**
     * 回顾点赞数减一
     * @param $cid
     * @param int $num
     * @return bool
     */
    public static function countReviewLikeAllDecrement($cid, $num = 1)
    {
        return CounterService::decrement(self::COUNTER_REVIEW_LIKE, ['all', $cid], $num);
    }

    /**
     * 计数器 回顾页收藏 key
     */
    const COUNTER_REVIEW_SAVE = 'userEventReviewSave';

    /**
     * @param User $user
     * @param Course $course
     * @return mixed
     */
    public static function saveAReview(User $user, Course $course)
    {
        $uid = $user->id;
        $userType = $user->type;
        $cid = $course->id;
        $type = 'review_save';
        $userEvent = UserEvent::where('uid', $uid)->where('cid', $cid)->where('type', $type)->first();
        if (!$userEvent) {
            $userEvent = new UserEvent();
            $userEvent->uid = $uid;
            $userEvent->user_type = $userType;
            $userEvent->cid = $cid;
            $userEvent->type = $type;
            $userEvent->save();
            $res = $userEvent;
            self::countReviewSaveAllIncrement($cid);
        }else {
            $res = $userEvent;
        }
        return $res;
    }

    /**
     * @param User $user
     * @param Course $course
     * @return bool
     */
    public static function cancelAReviewSave(User $user, Course $course)
    {
        $uid = $user->id;
        $cid = $course->id;
        $type = 'review_save';
        $userEvent = UserEvent::where('uid', $uid)->where('cid', $cid)->where('type', $type)->first();
        if (!$userEvent) {
            return false;
        }
        $res = $userEvent->delete();
        self::countReviewSaveAllDecrement($cid);
        return $res;
    }

    public static function isSaveAReview($uid, $cid){
        $type = 'review_save';
        $userEvent = UserEvent::where('uid', $uid)->where('cid', $cid)->where('type', $type)->first();
        if(!$userEvent){
            return 0;
        }
        return 1;
    }

    /**
     * @param $cid
     * @return mixed
     */
    public static function reviewSaveNum($cid)
    {
        // $reviewLikesNum = UserEvent::where('cid', $cid)->where('type', $type)->where('data', $data)->count();
        return self::countReviewSaveAllGet($cid);
    }

    /**
     * 回顾收藏数获取
     * @param $cid
     * @return bool|int
     */
    public static function countReviewSaveAllGet($cid)
    {
        return CounterService::get(self::COUNTER_REVIEW_SAVE, ['all', $cid]);
    }

    /**
     * 回顾收藏数加一
     * @param $cid
     * @param int $num
     * @return bool
     */
    public static function countReviewSaveAllIncrement($cid, $num = 1)
    {
        return CounterService::increment(self::COUNTER_REVIEW_SAVE, ['all', $cid], $num);
    }

    /**
     * 回顾收藏数减一
     * @param $cid
     * @param int $num
     * @return bool
     */
    public static function countReviewSaveAllDecrement($cid, $num = 1)
    {
        return CounterService::decrement(self::COUNTER_REVIEW_SAVE, ['all', $cid], $num);
    }



    /**
     * 计数器 套课点赞 key
     */
    const COUNTER_CAT_LIKE = 'userEventCatLike';

    /**
     * @param User $user
     * @param cid
     * @return mixed
     */
    public static function giveACatLike(User $user, $cid)
    {
        $uid = $user->id;
        $userType = $user->type;
        $type = 'cat_like';
        $data = 'give';
        $userEvent = UserEvent::where('uid', $uid)->where('cid', $cid)->where('type', $type)->first();
        if (!$userEvent) {
            $userEvent = new UserEvent();
            $userEvent->uid = $uid;
            $userEvent->user_type = $userType;
            $userEvent->cid = $cid;
            $userEvent->type = $type;
            $userEvent->data = $data;
            $userEvent->save();
            $res = $userEvent;
            self::countCatLikeAllIncrement($cid);
        } else if ($userEvent->data != 'give') {
            $res = $userEvent->update([
                'data' => $data,
            ]);
            self::countCatLikeAllIncrement($cid);
        } else {
            $res = $userEvent;
        }
        return $res;
    }

    /**
     * @param User $user
     * @param cid
     * @return bool
     */
    public static function cancelACatLike(User $user, $cid)
    {
        $uid = $user->id;
        $type = 'cat_like';
        $data = 'cancel';
        $userEvent = UserEvent::where('uid', $uid)->where('cid', $cid)->where('type', $type)->first();
        if (!$userEvent) {
            return false;
        }
        if ($userEvent->data == 'cancel') {
            return true;
        }
        $res = $userEvent->update([
            'data' => $data,
        ]);
        self::countCatLikeAllDecrement($cid);
        return $res;
    }

    public static function isLikeACat($uid, $cid){
        $type = 'cat_like';
        $userEvent = UserEvent::where('uid', $uid)->where('cid', $cid)->where('type', $type)->first();
        if(!$userEvent){
            return 0;
        }
        if($userEvent->data == 'cancel'){
            return 0;
        }
        return 1;
    }

    /**
     * @param cid
     * @return mixed
     */
    public static function catLikesNum($cid)
    {
        // $reviewLikesNum = UserEvent::where('cid', $cid)->where('type', $type)->where('data', $data)->count();
        return self::countCatLikeAllGet($cid);
    }

    /**
     * 套课点赞数获取
     * @param $cid
     * @return bool|int
     */
    public static function countCatLikeAllGet($cid)
    {
        return CounterService::get(self::COUNTER_CAT_LIKE, ['all', $cid]);
    }

    /**
     * 套课点赞数加一
     * @param $cid
     * @param int $num
     * @return bool
     */
    public static function countCatLikeAllIncrement($cid, $num = 1)
    {
        return CounterService::increment(self::COUNTER_CAT_LIKE, ['all', $cid], $num);
    }

    /**
     * 套课点赞数减一
     * @param $cid
     * @param int $num
     * @return bool
     */
    public static function countCatLikeAllDecrement($cid, $num = 1)
    {
        return CounterService::decrement(self::COUNTER_CAT_LIKE, ['all', $cid], $num);
    }

    /**
     * 计数器 套课页收藏 key
     */
    const COUNTER_CAT_SAVE = 'userEventCatSave';

    /**
     * @param User $user
     * @param cid
     * @return mixed
     */
    public static function saveACat(User $user, $cid)
    {
        $uid = $user->id;
        $userType = $user->type;
        $type = 'cat_save';
        $userEvent = UserEvent::where('uid', $uid)->where('cid', $cid)->where('type', $type)->first();
        if (!$userEvent) {
            $userEvent = new UserEvent();
            $userEvent->uid = $uid;
            $userEvent->user_type = $userType;
            $userEvent->cid = $cid;
            $userEvent->type = $type;
            $userEvent->save();
            $res = $userEvent;
            self::countCatSaveAllIncrement($cid);
        }else {
            $res = $userEvent;
        }
        return $res;
    }

    /**
     * @param User $user
     * @param cid
     * @return bool
     */
    public static function cancelACatSave(User $user, $cid)
    {
        $uid = $user->id;
        $type = 'cat_save';
        $userEvent = UserEvent::where('uid', $uid)->where('cid', $cid)->where('type', $type)->first();
        if (!$userEvent) {
            return false;
        }
        $res = $userEvent->delete();
        self::countCatSaveAllDecrement($cid);
        return $res;
    }

    public static function isSaveACat($uid, $cid){
        $type = 'cat_save';
        $userEvent = UserEvent::where('uid', $uid)->where('cid', $cid)->where('type', $type)->first();
        if(!$userEvent){
            return 0;
        }
        return 1;
    }

    /**
     * @param $cid
     * @return mixed
     */
    public static function catSaveNum($cid)
    {
        // $reviewLikesNum = UserEvent::where('cid', $cid)->where('type', $type)->where('data', $data)->count();
        return self::countCatSaveAllGet($cid);
    }

    /**
     * 套课收藏数获取
     * @param $cid
     * @return bool|int
     */
    public static function countCatSaveAllGet($cid)
    {
        return CounterService::get(self::COUNTER_CAT_SAVE, ['all', $cid]);
    }

    /**
     * 套课收藏数加一
     * @param $cid
     * @param int $num
     * @return bool
     */
    public static function countCatSaveAllIncrement($cid, $num = 1)
    {
        return CounterService::increment(self::COUNTER_CAT_SAVE, ['all', $cid], $num);
    }

    /**
     * 套课收藏数减一
     * @param $cid
     * @param int $num
     * @return bool
     */
    public static function countCatSaveAllDecrement($cid, $num = 1)
    {
        return CounterService::decrement(self::COUNTER_CAT_SAVE, ['all', $cid], $num);
    }
}
