<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/21
 * Time: 上午10:07
 */

namespace App\Repositories;

use App\Helpers\CacheKey;
use App\Models\Course;
use App\Models\CourseReview;
use App\Models\CourseTag;
use App\Models\Materiel;
use App\Models\UserCourse;
use App\Models\Tag;
use App\Models\Teacher;
use App\Models\UserMq;
use App\Models\UserTag;
use App\Services\Crm;
use App\Services\MqService;
use App\Services\Qnupload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\CIService\CMS;

use App\Models\AppConfig;
use App\Services\CourseService;
use App\Services\CounterService;
use Cache;

class FindRepository extends BaseRepository{
    protected $cms;

    protected $jinzhuang = ['S-26', 'S26', 'S26-MMF', '金装妈妈', 'S-26妈妈'];

    protected $qifu = ['启赋', '启韵'];

    protected $jinzhuang_brand = [4, 5, 8];

    protected $qifu_brand = [10, 11, 12];

    protected $page_size = 5;

    protected $time;

    public function __construct()
    {
        $this->cms = new CMS();
        $this->time = '2017-08-25';
    }

    public function getFindArticle($page, $limit, $showhtml){
        $data = $this->cms->getFindArticle($page, $limit, $showhtml);
        return $this->returnData($data);
    }

    public function getArticleDetail($article_id){
        $data = $this->cms->getArticleDetail($article_id);
        return $data;
    }

    public function like($id, $isCancel){
        $data = $this->cms->like($id, $isCancel);
        return $data;
    }

    public function comment(){
        $data = $this->cms->comment();
        return $data;
    }

    public function save($id, $isCancel){
        $uid = Auth::id();
        $data = $this->cms->save($id, $isCancel);
        //判断今天收藏了多少次
        $count = UserMq::where('uid', $uid)
            ->where('type', MqService::ADD_TYPE_SAVE)
            ->where('created_at', '>', date('Y-m-d'))
            ->count();
        if($count < 5){
            MqService::increase($uid, MqService::ADD_TYPE_SAVE);
        }
        if(is_array($data)){
            $data['mq'] = $count < 5 ? MqService::getTypeArray()[MqService::ADD_TYPE_SAVE][0] : 0;
        }
        return $data;
    }

    public function share($uid, $article_id){
        $ret = (new TaskRepository())->shareCms($uid, $article_id);
        if(!$ret){
            return [
                'ret' => 1,
                'mq' => 0
            ];
        }else{
            return [
                'ret' => 1,
                'mq' => $ret
            ];
        }
    }

    public function getSaveArticles($page, $page_size){
        $data = $this->cms->getSaveArticles($page, $page_size);
        return $this->returnData($data);
    }

    public function upload($link, $name, $head_pic, $author_name, $author_avatar){
        if ($link) {
            $html = file_get_html($link);
            $imgs = $html->find('img');
            $title = $name;
            $names[] = $title;
            foreach ($imgs as $count=>$img) {
                if (array_key_exists('data-src', $img->attr)) {
                    $pic = file_get_contents($img->attr['data-src']);
                    file_put_contents("/tmp/temp_tiny.jpg", $pic);
                    $url = Qnupload::uploadTmp("/tmp/temp_tiny.jpg", 'materiel/image/', $count);
                    $img->src = $url;
                    @unlink("/tmp/temp_tiny.jpg");
                }
            }
            $html = $html->save();
            $html = $this->cutStr($html);
            return (new CMS())->addArticle('', $title, $author_name, $html, $head_pic, $author_avatar);
        }
    }

    public function updateArticle($id, $link, $name, $head_pic, $author_name, $author_avatar){
        if ($link) {
            $html = file_get_html($link);
            $imgs = $html->find('img');
            foreach ($imgs as $count=>$img) {
                if (array_key_exists('data-src', $img->attr)) {
                    $pic = file_get_contents($img->attr['data-src']);
                    file_put_contents("/tmp/temp_tiny.jpg", $pic);
                    $url = Qnupload::uploadTmp("/tmp/temp_tiny.jpg", 'materiel/image/', $count);
                    $img->src = $url;
                    @unlink("/tmp/temp_tiny.jpg");
                }
            }
            $html = $html->save();
            $html = $this->cutStr($html);
            return (new CMS())->updateArticle($id, $name, $author_name, $html, $head_pic, $author_avatar);
        }
    }

    public function delete($id){
        return (new CMS())->deleteArticle($id);
    }

    public function addAuthor($author_name, $author_avatar){
        $data = (new CMS())->addAuthor($author_name, $author_avatar);
        return $data;
    }

    public function updateAuthor($id, $old_name,  $author_name, $new_avatar = ''){
        $data = (new CMS())->updateAuthor($id, $old_name,  $author_name, $new_avatar);
        return $data;
    }

    public function deleteAuthor($id){
        $data = (new CMS())->deleteAuthor($id);
        return $data;
    }

    public function getAuthorByPage($page = 1, $page_size = 6){
        $data = (new CMS())->getAuthorByPage($page, $page_size);
        return $data;
    }

    public function getAuthorById($id){
        $data = (new CMS())->getAuthorById($id);
        return $data;
    }

    public function getArticleByAuthor($page = 1, $page_size = 6, $author_name){
        $data = (new CMS())->getArticleByAuthor($page, $page_size, $author_name);
        return $data;
    }

    public function cutStr($str){
        while(strpos($str, '<script')){
            $s = strstr($str, '<script', true);
            $s1 = strstr($str, '</script>');
            $length = mb_strlen($s1);
            $s2 = mb_substr($s1, 9, $length - 9, 'utf-8');
            $str = $s . $s2;
        }
        return $str;
    }

    public function getBrandArticle($page, $limit, $showhtml){
        $jinzhuang_materiels = Cache::get(CacheKey::CACHE_KEY_FIND_JINZHUANG);
        $qifu_materiels = Cache::get(CacheKey::CACHE_KEY_FIND_QIFU);
        $ganhuo_materiels = Cache::get(CacheKey::CACHE_KEY_FIND_GANHUO);
        if(!($jinzhuang_materiels && $qifu_materiels && $ganhuo_materiels)){
            $jinzhuang_materiels = Materiel::where('cms_id', '>', 0)->whereNotIn('brand', $this->qifu)->orderBy('id', 'desc')->get();
            $qifu_materiels = Materiel::where('cms_id', '>', 0)->whereNotIn('brand', $this->jinzhuang)->orderBy('id', 'desc')->get();
            $ganhuo_materiels = Materiel::where('cms_id', '>', 0)->whereNotIn('brand', array_merge($this->jinzhuang, $this->qifu))->orderBy('id', 'desc')->get();
            Cache::put(CacheKey::CACHE_KEY_FIND_JINZHUANG, $jinzhuang_materiels, 60);
            Cache::put(CacheKey::CACHE_KEY_FIND_QIFU, $qifu_materiels, 60);
            Cache::put(CacheKey::CACHE_KEY_FIND_GANHUO, $ganhuo_materiels, 60);
        }
        $data = $this->dealWithBrandArticle($page, $limit, $jinzhuang_materiels, $qifu_materiels, $ganhuo_materiels);
        return $this->returnData($data);
    }

    public function dealWithBrandArticle($page, $limit, $jinzhuang_materiels, $qifu_materiels, $ganhuo_materiels){
        $page--;
        $brand = (new Crm())->getMemberBrand();
        if($brand == 4){        //金装
            $data = Cache::get(CacheKey::CACHE_KEY_CMS_JINZHUANG . $page);
            $array_1 = $jinzhuang_materiels;
            $array_2 = $jinzhuang_materiels;
        }elseif($brand == 10){  //启赋
            $data = Cache::get(CacheKey::CACHE_KEY_CMS_QIFU . $page);
            $array_1 = $qifu_materiels;
            $array_2 = $qifu_materiels;
        }else{                  //无主
            $data = Cache::get(CacheKey::CACHE_KEY_CMS_GANHUO . $page);
            $array_1 = $jinzhuang_materiels;
            $array_2 = $qifu_materiels;
        }
        if($data){
            return $data;
        }
        $length1 = (count($array_1) % 2 == 0) ? (count($array_1) / 2) : ((count($array_1) - 1) / 2);
        $length2 = (count($array_2) % 2 == 0) ? (count($array_2) / 2) : ((count($array_2) - 1) / 2);
        $length3 = intval($ganhuo_materiels / 3);
        $length = $length1 > $length2 ? $length1 : $length2;
        $deta = $length1 > $length2 ? ($length1 - $length2) : ($length2 - $length1);
        if($page <= $length1 && $page <= $length2){
            //安全区
            $cms_ids = [
                $array_1[2 * $page]->cms_id,
                $array_2[2 * $page + 1]->cms_id,
                3 * $page < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page]->cms_id : null,
                3 * $page + 1 < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 1]->cms_id : null,
                3 * $page + 2 < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 2]->cms_id : null,
            ];
        }elseif($page > $length1 && $page <= $length2){
            $cms_ids = [
                (2 * $page + $page - $length1) < count($array_1) ?
                    $array_1[2 * $page + $page - $length1]->cms_id :
                    (3 * $page + 4 * ($page - $length1 - 1)) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1)]->cms_id : null,
                $array_2[2 * $page + $page - $length1 - 1]->cms_id,
                (2 * $page + $page - $length1) < count($array_1) ?
                    (3 * $page + 4 * ($page - $length1 - 1)) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1)]->cms_id : null :
                    (3 * $page + 4 * ($page - $length1 - 1) + 1) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1) + 1]->cms_id : null,
                (2 * $page + $page - $length1) < count($array_1) ?
                    (3 * $page + 4 * ($page - $length1 - 1) + 1) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1) + 1]->cms_id : null :
                    (3 * $page + 4 * ($page - $length1 - 1) + 2) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1) + 2]->cms_id : null,
                (2 * $page + $page - $length1) < count($array_1) ?
                    (3 * $page + 4 * ($page - $length1 - 1) + 2) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1) + 2]->cms_id : null :
                    (3 * $page + 4 * ($page - $length1 - 1) + 3) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1) + 3]->cms_id : null,
            ];
        }elseif($page <= $length1 && $page > $length2){
            $cms_ids = [
                $array_1[2 * $page + $page - $length2 - 1]->cms_id,
                (2 * $page + $page - $length2) < count($array_2) ?
                    $array_2[2 * $page + $page - $length2]->cms_id :
                    (3 * $page + 4 * ($page - $length1 - 1)) <  count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1)]->cms_id : null,
                (2 * $page + $page - $length2) < count($array_2) ?
                    (3 * $page + 4 * ($page - $length1 - 1)) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1)]->cms_id : null :
                    (3 * $page + 4 * ($page - $length1 - 1) + 1) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1) + 1]->cms_id : null,
                (2 * $page + $page - $length2) < count($array_2) ?
                    (3 * $page + 4 * ($page - $length1 - 1) + 1) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1) + 1]->cms_id : null :
                    (3 * $page + 4 * ($page - $length1 - 1) + 2) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1) + 2]->cms_id : null,
                (2 * $page + $page - $length2) < count($array_2) ?
                    (3 * $page + 4 * ($page - $length1 - 1) + 2) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1) + 2]->cms_id : null :
                    (3 * $page + 4 * ($page - $length1 - 1) + 3) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * ($page - $length1 - 1) + 3]->cms_id : null,
            ];
        }else{
            $cms_ids = [
                (2 * $page + $page - $length1) < count($array_1) ?
                    $array_1[2 * $page + $page - $length1]->cms_id :
                    (3 * $page + 4 * $deta + 5 * ($page - $length - 1)) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * $deta + 5 * ($page - $length - 1)]->cms_id : null,
                (2 * $page + $page - $length2) < count($array_2) ?
                    $array_2[2 * $page + $page - $length2]->cms_id :
                    (3 * $page + 4 * $deta + 5 * ($page - $length - 1) + 1) < count( $ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * $deta + 5 * ($page - $length - 1) + 1]->cms_id : null,
                (3 * $page + 4 * $deta + 5 * ($page - $length - 1) + 2) <count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * $deta + 5 * ($page - $length - 1) + 2]->cms_id : null,
                (3 * $page + 4 * $deta + 5 * ($page - $length - 1) + 3) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * $deta + 5 * ($page - $length - 1) + 3]->cms_id : null,
                (3 * $page + 4 * $deta + 5 * ($page - $length - 1) + 4) < count($ganhuo_materiels) ? $ganhuo_materiels[3 * $page + 4 * $deta + 5 * ($page - $length - 1) + 4]->cms_id : null,
            ];
        }
        $data = $this->getCMSArticle($cms_ids);
        if($brand == 4){
            Cache::put(CacheKey::CACHE_KEY_CMS_JINZHUANG . $page, $data, 60);
        }elseif($brand == 10){
            Cache::put(CacheKey::CACHE_KEY_CMS_QIFU . $page, $data, 60);
        }else{
            Cache::put(CacheKey::CACHE_KEY_CMS_GANHUO . $page, $data, 60);
        }
        return $data;
    }

    public function getCMSArticle($cms_ids){
        $data = [];
        foreach ($cms_ids as $cms_id){
            if($cms_id){
                $ret = $this->cms->getArticleDetail($cms_id);
                if(array_key_exists('content', $ret['data'])){
                    $ret['data']['content'] = '';
                }
                $data[] = $ret['data'];
            }
        }
        return $data;
    }

    public function getDynamicAndArticles($uid, $page){
        $data = Cache::get(CacheKey::CACHE_KEY_FIND . $uid . ($page - 1));
        if($data){
            return $this->returnData($data);
        }
        $brand = (new Crm())->getMemberBrand();
        $page--;
        $offset = $page * $this->page_size;
        $userTags = UserTag::where('uid', $uid)->get()->toArray();
        $cids = [];
        foreach ($userTags as $userTag){
            $tag = Tag::where('id', $userTag['tid'])->first();
            if($tag){
                $courseTags = CourseTag::where('tid', $userTag['tid'])->get()->toArray();
                if(count($courseTags) > 0){
                    foreach ($courseTags as $courseTag){
                        if(!in_array($courseTag['cid'], $cids)){
                            $cids[] = $courseTag['cid'];
                        }
                    }
                }
            }
        }
        if($brand == 4){
            $courses = Course::whereIn('id', $cids)->where('display_status', 1)->where('created_at', '>', $this->time)->whereNotIn('brand', $this->qifu_brand)->orderBy('created_at', 'desc')->get()->toArray();
            $materiels = Materiel::where('cms_id', '>', 0)->whereNotIn('brand', $this->qifu)->orderBy('id', 'desc')->get()->toArray();
        }elseif($brand == 10){
            $courses = Course::whereIn('id', $cids)->where('display_status', 1)->where('created_at', '>', $this->time)->whereNotIn('brand', $this->jinzhuang_brand)->orderBy('created_at', 'desc')->get()->toArray();
            $materiels = Materiel::where('cms_id', '>', 0)->whereNotIn('brand', $this->jinzhuang)->orderBy('id', 'desc')->get()->toArray();
        }else{
            $courses = Course::whereIn('id', $cids)->where('display_status', 1)->where('created_at', '>', $this->time)->orderBy('created_at', 'desc')->get()->toArray();
            $materiels = Materiel::where('cms_id', '>', 0)->orderBy('id', 'desc')->get()->toArray();
        }
        for($i = 0; $i < count($materiels); $i++){
            $materiels[$i]['start_day'] = $materiels[$i]['date'];
        }
        $articles = array_merge($courses, $materiels);
        array_multisort(array_column($articles, 'start_day'), SORT_DESC, $articles);
        $data = [];
        $cms_ids_array = [];
        $index_array = [];
        for($i = $offset; $i < $offset + $this->page_size; $i++){
            if($i < count($articles) && array_key_exists('cms_id', $articles[$i])){
                $cms_ids_array[] = $articles[$i]['cms_id'];
                $index_array[] = $i;
            }
        }
        $cms_ids = json_encode($cms_ids_array);
        $ret = $this->cms->getArticleDetailByIds($cms_ids);
        if(array_key_exists('data', $ret)){
            $cms_data = $ret['data'];
        }else{
            $cms_data = [];
        }
        $j = 0;
        for($i = $offset; $i < $offset + $this->page_size; $i++){
            if(in_array($i, $index_array) && $j < count($cms_data)){
                if(array_key_exists('content', $cms_data[$j])){
                    $cms_data[$j]['content'] = '';
                }
                $cms_data[$j]['type'] = 1;
                $data[] = $cms_data[$j];
                $j++;
            }else{
                $ret = CourseService::getCourseInfoById($uid, $articles[$i]['id']);
                $ret['type'] = 0;
                $data[] = $ret;
            }
        }
        Cache::put(CacheKey::CACHE_KEY_FIND . $uid . $page, $data, 60);
        return $this->returnData($data);
    }


}