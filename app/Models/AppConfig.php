<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Helpers\CacheKey;
use Cache;
use Illuminate\Support\Str;

/**
 * 系统配置类
 */
class AppConfig extends Model
{
    //module和key的常量
    const MODULE_AUTIO_PUSH = 'auto_push'; //自动推送module
    const KEY_FULI_TPL      = 'fuli_tpl';  //福利模板l


    const MODULE_OTHER_INDEX = 'other_index'; //其他配置页面
    const KEY_OTHER_TITLE = 'other_title'; //h5标题
    const KEY_OTHER_COPYRIGHT = 'other_copyright'; //首页版权信息
    const KEY_OTHER_SEARCH_PLACEHOLDER = 'other_search_placeholder'; //搜索placeholder
    const KEY_OTHER_DRAW_BG = 'other_draw_bg'; //抽奖背景图片
    const KEY_OTHER_CRM_TIP = 'other_crm_tip'; //crm未注册提示
    const KEY_OTHER_CRM_REGISTER = 'other_crm_register'; //crm注册地址
    const KEY_OTHER_FULI_TEMPLATE = 'other_fuli_template'; //福利模板消息

    protected $table = 'app_configs';

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
            if ($this->key != 'ci_index_tags_def' && $this->key != 'ci_recommend_def' && $this->key != 'column') {
                if (isset($decoded['img'])){
                    $decoded['img'] = replaceUploadURL($decoded['img']);
                }

                //图片优化
                switch ($this->key){
                    case 'catCourses':
                        if (strpos($decoded['img'], '?imageView2/2/w/355/h/150') === false){
                            $decoded['img'] .= '?imageView2/2/w/355/h/150';
                        }
                        break;
                    case 'carousels1':
                        if (strpos($decoded['img'], '?imageView2/2/w/750/h/360') === false){
                            $decoded['img'] .= '?imageView2/2/w/750/h/360';
                        }
                        break;
                    case 'carousels2':
                        if (strpos($decoded['img'], '?imageView2/2/w/750/h/200') === false){
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

    /**
     * 取得首页显示的标签
     * @param  bool $flush
     * @param  int $number
     * @param  int $length
     * @return collection $tags
     */
    //配合签标通过id搜索
    public static function tags($flush = false, $number = 8, $length = 4)
    {
        $cacheKey = 'index_tags';
        if ($flush) {
            Cache::forget($cacheKey);
        }
        $tags = Cache::remember($cacheKey, 43200, function () use ($number, $length) {
            $item = static::where('module', 'index')->where('key', 'tags')->first();
            if (!$item) {
                $tags = [];
            } else {
                $tagIds = explode(',', $item->data);
                $tags = Tag::whereIn('id', $tagIds)->select('id', 'name')->limit($number)->get();
                foreach($tags as $tag) {
                    $tag->name = Str::substr($tag->name, 0, $length);
                }
            }
            return $tags;
        });

        return $tags;
    }

    public static function courses1($flush = false){
        $ids = Cache::get('courseRecommend1');
        if($ids == null || $flush == true){
            $item = AppConfig::where('module', 'index')->where('key', 'courses1')->first();
            if ($item) {
                $ids = explode(',', $item->data);
            }else{
                $ids = [];
            }
            Cache::put('courseRecommend1', $ids, 43200);
        }

        return $ids;
    }

    public static function courses2($flush = false){
        $ids = Cache::get('courseRecommend2');
        if($ids == null || $flush == true){
            $item = AppConfig::where('module', 'index')->where('key', 'courses2')->first();
            if ($item) {
                $ids = explode(',', $item->data);
            }else{
                $ids = [];
            }
            Cache::put('courseRecommend2', $ids, 43200);
        }

        return $ids;
    }

    //首页顶部广告轮播图 1
    public static function carousels1($userType = User::USERTYPE_NN, $flush = false)
    {
        $type = 'flashPics1' . $userType;
        $currentTypePics = Cache::get($type);
        if($currentTypePics == null || $flush == true){
            $pics = AppConfig::where('module', 'index')->where('key', 'carousels1')->orderBy('displayorder')->get()->pluck('data')->toArray();
            $reorderPics = [];
            foreach($pics as $pic){
                $tempAttr = isset($pic['attr']) ? $pic['attr'] : User::USERTYPE_NN;
                //替换广告url
                $pic['link'] = self::getUrlLink($pic['link'], 'server_ad', 'subject', $pic['subject']);
                $reorderPics[$tempAttr][] = $pic;
            }
            //

            $currentTypePics = [];
            do{
                //
                if(isset($reorderPics[$userType])){
                    $currentTypePics = $reorderPics[$userType];
                    break;
                }
                $userType--;
            }
            while($userType > 0);
            Cache::put($type, $currentTypePics, 43200);
        }

        return $currentTypePics;
    }

    //首页中间广告轮播图 2
    public static function carousels2($userType = User::USERTYPE_NN, $flush = false)
    {
        $type = 'flashPics2' . $userType;
        $currentTypePics = Cache::get($type);
        if($currentTypePics == null || $flush == true){
            $pics = AppConfig::where('module', 'index')->where('key', 'carousels2')->orderBy('displayorder')->get()->pluck('data')->toArray();

            $reorderPics = [];
            foreach($pics as $pic){
                $tempAttr = isset($pic['attr']) ? $pic['attr'] : User::USERTYPE_NN;
                //替换广告url
                $pic['link'] = self::getUrlLink($pic['link'], 'server_ad', 'subject', $pic['subject']);
                $reorderPics[$tempAttr][] = $pic;
            }
            //
            $currentTypePics = [];
            do{
                //
                if(isset($reorderPics[$userType])){
                    $currentTypePics = $reorderPics[$userType];
                    break;
                }
                $userType--;
            }
            while($userType > 0);

            Cache::put($type, $currentTypePics, 43200);
        }

        return $currentTypePics;
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

    // 母乳活动
    public static function breastMilk($flush = false) {
        $pics = Cache::get('breastMilk');
        if ($pics == null || $flush == true) {
            $pics = AppConfig::where('module', 'activity')->where('key', 'breastMilk')->orderBy('displayorder')->get()->pluck('data')->toArray();
            Cache::put('breastMilk', $pics, 43200);
        }
        return $pics;
    }

    public static function s26CardData($flush = false) {
        $cards = Cache::get('s26CardData');
        if ($cards == null || $flush == true) {
            $cards = AppConfig::where('module', 'activity')->where('key', 's26_card')->orderBy('displayorder')->get()->pluck('data')->toArray();
            Cache::put('s26CardData', $cards, 43200);
        }
        return $cards;
    }

    public static function morningData($flush = true) {
        $morning = Cache::get('morningData');
        if ($morning == null || $flush == true) {
            $morning = AppConfig::where('module', 'activity')->where('key', 'good_morning')->orderBy('displayorder')->get()->pluck('data')->toArray();
            Cache::put('morningData', $morning, 43200);
        }
        return $morning;
    }

    public static function getHomeTag($flush = false){
        $ids = Cache::get(CacheKey::HOME_TAGS);
        if($ids == null || $flush == true){
            $item = AppConfig::where('module', 'index')->where('key', 'index_tags')->first();
            if ($item) {
                $ids = explode(',', $item->data);
            }else{
                $ids = [];
            }
            Cache::put(CacheKey::HOME_TAGS, $ids, 43200);
        }

        return $ids;
    }

    public static function getSearchTag($flush = false){
        $ids = Cache::get(CacheKey::SEARCH_TAGS);
        if($ids == null || $flush == true){
            $item = AppConfig::where('module', 'index')->where('key', 'tags')->first();
            if ($item) {
                $ids = explode(',', $item->data);
            }else{
                $ids = [];
            }
            Cache::put(CacheKey::SEARCH_TAGS, $ids, 43200);
        }

        return $ids;
    }

    public static function getHomeHotCourse($flush = false){
        $ids = Cache::get(CacheKey::COURSE_HOT);
        if($ids == null || $flush == true){
            $item = AppConfig::where('module', 'index')->where('key', 'courseHot')->first();
            if ($item) {
                $ids = explode(',', $item->data);
            }else{
                $ids = [];
            }
            Cache::put(CacheKey::COURSE_HOT, $ids, 43200);
        }

        return $ids;
    }

    public static function getHomeNewCourse($flush = false){
        $ids = Cache::get(CacheKey::COURSE_NEW);
        if($ids == null || $flush == true){
            $item = AppConfig::where('module', 'index')->where('key', 'courseNew')->first();
            if ($item) {
                $ids = explode(',', $item->data);
            }else{
                $ids = [];
            }
            Cache::put(CacheKey::COURSE_NEW, $ids, 43200);
        }

        return $ids;
    }

    public static function getHomeActivity($flush = false){
        $data = Cache::get(CacheKey::CLASS_ACTIVITY);
        if($data == null || $flush == true){
            $item = AppConfig::where('module', 'index')->where('key', 'class_activity')->first();
            if($item){
                $data = $item->data;
            }else{
                $data = [];
            }
            Cache::put(CacheKey::CLASS_ACTIVITY, $data, 43200);
        }

        return $data;
    }


    /**
     * 获取配置的data
     * @param $module
     * @param $key
     * @return null
     */
    public static function getModuleKeyData($module, $key){
        $item = AppConfig::where('module', $module)->where('key', $key)->first();
        if($item){
            $data = $item->data;
        }else{
            $data = '';
        }
        return $data;
    }

    /**
     * 获取MODULE_OTHER_INDEX的相应key值的data
     * @param $key
     * @return null
     */
    public static function getOtherModuleDataByKey($key){
        return self::getModuleKeyData(self::MODULE_OTHER_INDEX, $key);
    }

}
