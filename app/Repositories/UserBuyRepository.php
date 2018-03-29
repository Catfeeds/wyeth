<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/9/13
 * Time: 16:35
 */

namespace App\Repositories;

use App\Helpers\WyethUtil;
use App\Models\Course;
use App\Models\CourseCat;
use App\Models\UserBuyCourses;
use App\Services\MqService;
use Illuminate\Support\Facades\Auth;

class UserBuyRepository extends BaseRepository {

    public function buyCourses ($type, $cid) {
        $uid = Auth::id();

        $userBuy = new UserBuyCourses();

        if ($type == UserBuyCourses::TYPE_CAT) {
            $content = CourseCat::find($cid);
        } else if ($type == UserBuyCourses::TYPE_COURSE) {
            $content = Course::find($cid);
        } else {
            return $this->error->INVALID_TYPE;
        }

        if ($content) {
            $userBuy->mq =$content->price;
            $userBuy->detail = json_encode($content);
        } else {
            return $this->error->NO_COURSE_OR_CAT;
        }

        $bought = UserBuyCourses::where('uid', $uid)->where('cid', $cid)->where('type', $type)->where('trade_status', 1)->first();
        if ($bought) {
            return $this->error->BOUGHT_BEFORE;
        } else {
            $userBuy->trade_id = WyethUtil::generateTradeId();
            $userBuy->uid = $uid;
            $userBuy->type = $type;
            $userBuy->cid = $cid;
            $userBuy->trade_status = 0;

            if ($userBuy->save()) {
                return $this->returnData([
                    'trade_id' => $userBuy->trade_id
                ]);
            } else {
                return $this->error->UNKNOWN_ERROR;
            }
        }


    }

    public function paySuccess ($trade_id) {
        $trade = UserBuyCourses::where('trade_id', $trade_id)->first();
        if (!$trade) {
            return $this->error->NO_TRADE;
        }

        $ret = MqService::decrease(Auth::id(), MqService::CONSUME_TYPE_PAID_COURSE, $trade->mq);
        if ($ret['ret'] == -1) {
            return $ret;
        } else {
            $trade->trade_status = 1;
            if ($trade->save()) {
                return $ret;
            } else {
                return $this->error->UNKNOWN_ERROR;
            }
        }
    }

    public function getTradeInfo ($trade_id) {
        $trade = UserBuyCourses::where('trade_id', $trade_id)->first();
        if ($trade) {
            $trade->detail = json_decode($trade->detail);
            $trade = $trade->toArray();
        } else {
            return $this->error->NO_TRADE;
        }

        if ($trade['type'] == 1) {
            $course_num = Course::where('cid', $trade['cid'])->where('display_status', 1)->count();
        } else {
            $course_num = 1;
        }

        $mq_left = MqService::getUserMq(Auth::id());

        return $this->returnData(array_merge($trade, array('mq_left' => $mq_left, 'course_num' => $course_num)));
    }

    public function getBoughtCat () {
        $uid = Auth::id();

        $bought_cat = UserBuyCourses::where('uid', $uid)->where('type', UserBuyCourses::TYPE_CAT)->where('trade_status', 1)->get();
        if ($bought_cat) {
            foreach ($bought_cat as $item) {
                $item->bought_num = UserBuyCourses::where('cid', $item->cid)->where('type', UserBuyCourses::TYPE_CAT)->where('trade_status', 1)->count();

                $course = Course::where('cid', $item->cid)->where('display_status', 1)->first();
                if ($course) {
                    $item->banner = $course->img;
                } else {
                    $item->banner = '';
                }
                $item->detail = json_decode($item->detail);
            }

            $bought_cat = $bought_cat->toArray();
        }

        return $this->returnData($bought_cat);
    }

    public function getBoughtCourse () {
        $uid = Auth::id();

        $bought_course = UserBuyCourses::where('uid', $uid)->where('type', UserBuyCourses::TYPE_COURSE)->where('trade_status', 1)->get();
        if ($bought_course) {
            foreach ($bought_course as $item) {
                $item->bought_num = UserBuyCourses::where('cid', $item->cid)->where('type', UserBuyCourses::TYPE_COURSE)->where('trade_status', 1)->count();

                $course = Course::where('id', $item->cid)->first();
                if ($course) {
                    $item->banner = $course->img;
                } else {
                    $item->banner = '';
                }
                $item->detail = json_decode($item->detail);
            }

            $bought_course = $bought_course->toArray();
        }

        return $this->returnData($bought_course);
    }
}