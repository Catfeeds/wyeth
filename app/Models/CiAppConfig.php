<?php

namespace App\Models;

use App\Services\Crm;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Helpers\CacheKey;
use Cache;
use Illuminate\Support\Str;

class CiAppConfig extends  AppConfig {

    protected $table = 'app_configs';

    const module = 'de_index';

    const module_program = 'program';

    public function setDataAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['data'] = json_encode($value);
        } else {
            $this->attributes['data'] = $value;
        }
    }

    public function getDataAttribute($value)
    {
        $decoded = json_decode($value, true);
        if ($decoded != null) {
            if ($this->key != 'ci_index_tags_def' && $this->key != 'ci_recommend_def') {
                if (isset($decoded['img'])){
                    $decoded['img'] = replaceUploadURL($decoded['img']);
                }

                //图片优化
                switch ($this->key) {
                    case 'catCourses':
                        if (strpos($decoded['img'], '?imageView2/2/w/355/h/150') === false) {
                            $decoded['img'] .= '?imageView2/2/w/355/h/150';
                        }
                        break;
                    case 'carousels1':
                        if (strpos($decoded['img'], '?imageView2/2/w/750/h/360') === false) {
                            $decoded['img'] .= '?imageView2/2/w/750/h/360';
                        }
                        break;
                    case 'carousels2':
                        if (strpos($decoded['img'], '?imageView2/2/w/750/h/200') === false) {
                            $decoded['img'] .= '?imageView2/2/w/750/h/200';
                        }
                        break;

                }
            }
            return $decoded;
        } else {
            return $value;
        }
    }

    //新版首页顶部广告轮播图 1
    public static function ci_carousels1($userType = User::USER_CI_NN, $flush = false)
    {
        $type = 'ci_flashPics1' . $userType;
        $currentTypePics = Cache::get($type);
        if($currentTypePics == null || $flush == true){
//            $module = self::getModule($userType);
            $pics = static::where('module', static::module)->where('key', 'carousels_temp')->orderBy('displayorder')->get()->pluck('data')->toArray();
            $reorderPics = [];
            foreach($pics as $pic){
                $tempAttr = isset($pic['attr']) ? $pic['attr'] : User::USER_CI_NN;
                //替换广告url
                $pic['link'] = self::getUrlLink($pic['link'], 'server_ad', 'subject', $pic['subject']);
                $reorderPics[$tempAttr][] = $pic;
            }

            $currentTypePics = [];
            if(isset($reorderPics[intval($userType / 2)])){
                $currentTypePics = $reorderPics[intval($userType / 2)];
            }
            Cache::put($type, $currentTypePics, 43200);
        }

        return $currentTypePics;
    }

    //首页中间广告轮播图 2
    public static function ci_carousels2($userType = User::USER_CI_NN, $flush = false)
    {
        $type = 'ci_flashPics2' . $userType;
        $currentTypePics = Cache::get($type);
        if($currentTypePics == null || $flush == true){
//            $module = self::getModule($userType);
            $pics = static::where('module', static::module)->where('key', 'ci_carousels2')->orderBy('displayorder')->get()->pluck('data')->toArray();

            $reorderPics = [];
            foreach($pics as $pic){
                $tempAttr = isset($pic['attr']) ? $pic['attr'] : User::USER_CI_NN;
                //替换广告url
                $pic['link'] = self::getUrlLink($pic['link'], 'server_ad', 'subject', $pic['subject']);
                $reorderPics[$tempAttr][] = $pic;
            }

            $currentTypePics = [];
            if(isset($reorderPics[intval($userType / 2)])){
                $currentTypePics = $reorderPics[intval($userType / 2)];
            }

            Cache::put($type, $currentTypePics, 43200);
        }

        return $currentTypePics;
    }

    /**
     * 取得热搜显示的标签
     * @param  bool $flush
     * @param  int $number
     * @param  int $length
     * @return collection $tags
     */
    //配合签标通过id搜索
    public static function ci_hot_tags($flush = false, $number = 8, $length = 4)
    {
        $cacheKey = 'ci_hot_tags';
        if ($flush) {
            Cache::forget($cacheKey);
        }
        $tags = Cache::remember($cacheKey, 43200, function () use ($number, $length) {
            $item = static::where('module', static::module)->where('key', 'ci_hot_tags')->first();
            if (!$item) {
                $tags = [];
            } else {
                $tagIds = explode(',', $item->data);
                $tags = Tag::whereIn('id', $tagIds)->select('id', 'name', 'img', 'type')->limit($number)->get();
                foreach($tags as $tag) {
                    $tag->name = Str::substr($tag->name, 0, $length);
                }
            }
            return $tags;
        });

        return $tags;
    }

    /**
     * 取得首页显示的标签
     * @param  int  $userType
     * @param  bool $flush
     * @param  int $number
     * @param  int $length
     * @return collection $tags
     */
    //配合签标通过id搜索
    public static function ci_index_tags($userType = User::USER_CI_NN, $flush = true, $number = 8, $length = 4)
    {
        $cacheKey = 'ci_index_tags' . $userType;
        if ($flush) {
            Cache::forget($cacheKey);
        }
        $tags = Cache::remember($cacheKey, 43200, function () use ($userType, $number, $length) {
//            $module = self::getModule($userType);
            $items = static::where('module', static::module)->where('key', 'ci_index_tags_def')->get()->toArray();
            if (!$items) {
                $tags = [];
            } else {
                $tags = [];
                $brand = self::getBrand();
                foreach ($items as $item) {
                    if ($item['data']['attr'] == $brand) {
                        $tagIds = explode(',', $item['data']['tags_arr']);
                        foreach ($tagIds as $tagId) {
                            $tag = Tag::where('id', $tagId)->select('id', 'name', 'img', 'type')->limit($number)->first();
                            if ($tag) {
                                $tag->name = Str::substr($tag->name, 0, $length);
                                $tags[] = $tag;
                            }
                        }
                    }
                }
            }
            return $tags;
        });

        return $tags;
    }

    public static function ci_focus_tags($flush = false, $number = 8, $length = 6)
    {
        $cacheKey = 'ci_focus_tags';
        if ($flush) {
            Cache::forget($cacheKey);
        }
        $tags = Cache::remember($cacheKey, 43200, function () use ($number, $length) {
            $item = static::where('module', static::module)->where('key', 'ci_focus_tags')->first();
            if (!$item) {
                $tags = [];
            } else {
                $tagIds = explode(',', $item->data);
                $tags = Tag::whereIn('id', $tagIds)->orderBy('type', 'desc')->select('id', 'name', 'img', 'type', 'interest_img')->get();
                foreach($tags as $tag) {
                    if($tag->type == 1){
                        $tag->name = str_replace('月', '个月', $tag->name);
                    }
                }
            }
            return $tags;
        });
        return $tags;
    }

    // “全部”页面 “热门标签”
    public static function ci_all_hot_tags($flush = false, $number = 3, $length = 6) {
        $cacheKey = 'ci_all_hot_tags';
        if ($flush) {
            Cache::forget($cacheKey);
        }
        $tags = Cache::remember($cacheKey, 43200, function () use ($number, $length) {
            $item = static::where('module', static::module)->where('key', 'ci_all_hot_tags')->first();
            if (!$item) {
                $tags = [];
            } else {
                $tagIds = explode(',', $item->data);
                $tags = Tag::whereIn('id', $tagIds)->where('type', 0)->orderBy('type', 'desc')->select('id', 'name', 'img', 'type', 'interest_img')->limit($number)->get();
            }
            return $tags;
        });

        return $tags;
    }

    // 套课及活动
    public static function ci_cat_activity($userType = User::USER_CI_NN, $flush = true) {
        $pics = Cache::get('ci_cat_activity' . self::getBrand());
        if ($pics == null || $flush == true) {
            $pics = static::where('module', static::module)->where('key', 'ci_cat_activity')->orderBy('displayorder')->get()->pluck('data')->toArray();
            $reorderPics = [];
            $brand = self::getBrand();
            foreach($pics as $pic){
                $tempAttr = isset($pic['attr']) ? $pic['attr'] : User::USER_CI_NN;
                //替换广告url
                if (isset($pic['type']) && $pic['type'] == 1) {
                    $pic['link'] = self::getUrlLink($pic['link'], 'server_ad', 'subject', $pic['subject']);
                }
                $reorderPics[$tempAttr][] = $pic;
            }

            unset($pics);
            $pics = [];
            if(isset($reorderPics[$brand])){
                $pics = $reorderPics[$brand];
            }
            Cache::put('ci_cat_activity' . self::getBrand(), $pics, 43200);
        }
        return $pics;
    }

    // 最热课程
    public static function ci_hot_course($flush = false){
        $ids = Cache::get('ci_hot_course');
        if($ids == null || $flush == true){
            $item = static::where('module', static::module)->where('key', 'ci_hot_course')->first();
            if ($item) {
                $ids = explode(',', $item->data);
            }else{
                $ids = [];
            }
            Cache::put('ci_hot_course', $ids, 43200);
        }

        return $ids;
    }

    // 最新课程
    public static function ci_new_course($flush = false){
        $ids = Cache::get('ci_new_course');
        if($ids == null || $flush == true){
            $item = static::where('module', static::module)->where('key', 'ci_new_course')->first();
            if ($item) {
                $ids = explode(',', $item->data);
            }else{
                $ids = [];
            }
            Cache::put('ci_new_course', $ids, 43200);
        }

        return $ids;
    }

    // 金装最热课程
    public static function ci_jinzhuang_hot_course($flush = false){
        $ids = Cache::get('ci_jinzhuang_hot_course');
        if($ids == null || $flush == true){
            $item = static::where('module', static::module)->where('key', 'ci_jinzhuang_hot_course')->first();
            if ($item) {
                $ids = explode(',', $item->data);
            }else{
                $ids = [];
            }
            Cache::put('ci_jinzhuang_hot_course', $ids, 43200);
        }

        return $ids;
    }

    // 金装最新课程
    public static function ci_jinzhuang_new_course($flush = false){
        $ids = Cache::get('ci_jinzhuang_new_course');
        if($ids == null || $flush == true){
            $item = static::where('module', static::module)->where('key', 'ci_jinzhuang_new_course')->first();
            if ($item) {
                $ids = explode(',', $item->data);
            }else{
                $ids = [];
            }
            Cache::put('ci_jinzhuang_new_course', $ids, 43200);
        }

        return $ids;
    }

    // 启赋最热课程
    public static function ci_qifu_hot_course($flush = false){
        $ids = Cache::get('ci_qifu_hot_course');
        if($ids == null || $flush == true){
            $item = static::where('module', static::module)->where('key', 'ci_qifu_hot_course')->first();
            if ($item) {
                $ids = explode(',', $item->data);
            }else{
                $ids = [];
            }
            Cache::put('ci_qifu_hot_course', $ids, 43200);
        }

        return $ids;
    }

    // 启赋最新课程
    public static function ci_qifu_new_course($flush = false){
        $ids = Cache::get('ci_qifu_new_course');
        if($ids == null || $flush == true){
            $item = static::where('module', static::module)->where('key', 'ci_qifu_new_course')->first();
            if ($item) {
                $ids = explode(',', $item->data);
            }else{
                $ids = [];
            }
            Cache::put('ci_qifu_new_course', $ids, 43200);
        }

        return $ids;
    }

    // 推荐课程
    public static function ci_recommend($userType = User::USER_CI_NN, $flush = false) {
        $cacheKey = 'ci_recommend' . $userType;
        $ids = Cache::get($cacheKey);
        if ($ids == null || $flush == true) {
//            $module = self::getModule($userType);
            $items = static::where('module', static::module)->where('key', 'ci_recommend_def')->get()->toArray();
            $ids = [];
            foreach ($items as $item) {
                if ($item['data']['attr'] == intval($userType / 2)) {
                    $ids = explode(',', $item['data']['courses_arr']);
                }
                Cache::put($cacheKey, $ids, 43200);
            }

        }

        return $ids;
    }

    public static function ci_activity_and_teacher($userType = User::USER_CI_NN, $flush = false) {
        $cacheKey = 'ci_activity_and_teacher' . $userType;
        $data = Cache::get($cacheKey);
        if ($data == null || $flush == true) {
//            $module = self::getModule($userType);
            $items = static::where('module', static::module)->where('key', 'ci_activity_and_teacher')->get()->toArray();

            $reorderPics = [];
            foreach($items as $item){
                $tempAttr = isset($item['attr']) ? $item['attr'] : User::USER_CI_NN;
                //替换url
                $item['link'] = self::getUrlLink($item['link'], 'server_ad', 'subject', $item['subject']);
                $reorderPics[$tempAttr][] = $item;
            }

            $data = [];
            if(isset($reorderPics[intval($userType / 2)])){
                $data = $reorderPics[intval($userType / 2)];
            }

            Cache::put($cacheKey, $data, 43200);
        }

        return $data;
    }

    // 详情页广告1
    public static function ci_detail_advertise_1($userType = User::USER_CI_NN, $flush = false) {
        $type = "ci_detail_advertise_1" . $userType;
        $currentTypePics = Cache::get($type);
        if($currentTypePics == null || $flush == true){
//            $module = self::getModule($userType);
            $pics = static::where('module', 'end')->where('key', 'ci_detail_advertise_1')->orderBy('displayorder')->get()->pluck('data')->toArray();
            $reorderPics = [];
            foreach($pics as $pic){
                $tempAttr = isset($pic['attr']) ? $pic['attr'] : User::USER_CI_NN;
                //替换广告url
                $pic['link'] = self::getUrlLink($pic['link'], 'server_ad', 'subject', $pic['subject']);
                $reorderPics[$tempAttr][] = $pic;
            }

            $currentTypePics = [];
            if(isset($reorderPics[intval($userType / 2)])){
                $currentTypePics = $reorderPics[intval($userType / 2)];
            }
            Cache::put($type, $currentTypePics, 43200);
        }

        return $currentTypePics;
    }

    // 详情页广告2
    public static function ci_detail_advertise_2($userType = User::USER_CI_NN, $flush = false) {
        $type = "ci_detail_advertise_2" . $userType;
        $currentTypePics = Cache::get($type);
        if($currentTypePics == null || $flush == true){
//            $module = self::getModule($userType);
            $pics = static::where('module', 'end')->where('key', 'ci_detail_advertise_2')->orderBy('displayorder')->get()->pluck('data')->toArray();
            $reorderPics = [];
            foreach($pics as $pic){
                $tempAttr = isset($pic['attr']) ? $pic['attr'] : User::USER_CI_NN;
                //替换广告url
                $pic['link'] = self::getUrlLink($pic['link'], 'server_ad', 'subject', $pic['subject']);
                $reorderPics[$tempAttr][] = $pic;
            }

            $currentTypePics = [];
            if(isset($reorderPics[intval($userType / 2)])){
                $currentTypePics = $reorderPics[intval($userType / 2)];
            }
            Cache::put($type, $currentTypePics, 43200);
        }

        return $currentTypePics;
    }

    /**
     * 获取课页闪屏
     * @param bool $flush
     * @return mixed
     */
    public static function ci_course_flash_pic ($flush = true) {
        $type = 'ci_course_flash_pic';
        $pics = Cache::get($type);
        if ($pics == null || $flush) {
            $result = [];
            $pics = static::where('module', static::module)->where('key', $type)->orderBy('displayorder')->get()->pluck('data')->toArray();

            foreach ($pics as $index => $pic) {
                $pics[$index]['link'] = self::getUrlLink($pic['link'], 'course_flash_pic', 'subject', $pic['name']);
                if ($pic['displaystatus'] == 1) {
                    array_push($result, $pics[$index]);
                }
            }

            Cache::put($type, $pics, 8640);
        } else {
            $result = $pics;
        }

        return $result;
    }

    public static function program_cat_activity ($brand = 0, $flush = true) {
        if ($brand == 10) {
            $type = 'ci_qifu_cat_activity';
        } else if ($brand == 4) {
            $type = 'ci_jinzhuang_cat_activity';
        } else {
            $type = 'ci_wuzhu_cat_activity';
        }
        $items = Cache::get($type);
        if ($items == null || $flush) {
            $items = static::where('module', static::module_program)->where('key', $type)->orderBy('displayorder')->get()->pluck('data')->toArray();

            if (count($items) == 0) {
                $items = static::where('module', 'ci_wuzhu_cat_activity')->where('key', $type)->orderBy('displayorder')->get()->pluck('data')->toArray();
            }

//            foreach ($items  as $index => $item) {
//                $items[$index]['link'] = self::getUrlLink($item['link'], 'program_home_cat', 'subject', $item['subject']);
//            }

            Cache::put($type, $items, 8640);
        }

        return $items;
    }

    //给url加监测
    public static function getUrlLink($url, $action = '', $label = '', $value = ''){
        if (!preg_match('/^(http|https):\/\//', $url)){
            $url = 'http://'.$url;
        }
        $params['url'] = $url;
        if ($action){
            $params['action'] = $action;
        }
        if ($label){
            $params['label'] = $label;
        }
        if ($value){
            $params['value'] = $value;
        }
        return config('app.url').'/link?'.http_build_query($params);
    }

    public static function getModule($userType) {
        if ($userType == User::USER_CI_NN || $userType == User::USER_CI_NP) {
            return 'de_index';
        } else {
            return 'ci_index';
        }
    }

    public static function getBrand () {
        $userBrand = (new Crm())->getMemberBrand();
        if ($userBrand == 4) {
            $t = 1;
        } else if ($userBrand == 10) {
            $t = 2;
        } else {
            $t = 0;
        }
        return $t;
    }

}


