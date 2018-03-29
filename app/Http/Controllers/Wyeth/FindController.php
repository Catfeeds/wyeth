<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/21
 * Time: 上午10:06
 */

namespace App\Http\Controllers\Wyeth;

use Illuminate\Http\Request;
use App\Repositories\AppConfigRepository;
use App\Repositories\CourseRepository;
use App\Repositories\TagRepository;
use App\Repositories\FindRepository;
use Illuminate\Support\Facades\Session; // cat
use Auth;
use View;

class FindController extends WyethBaseController{

    protected $courseRepository;
    protected $tagRepository;
    protected $appConfigRepository;
    protected $findRepository;

    public function __construct()
    {
        parent::__construct();
        $this->courseRepository = new CourseRepository();
        $this->tagRepository = new TagRepository();
        $this->appConfigRepository = new AppConfigRepository();
        $this->findRepository = new FindRepository();
    }

    public function getNewestArticle(Request $request, $appkey, $channel, $page, $limit, $substrcount){
        $data = $this->findRepository->getFindArticle($page, $limit, 1);
        return $data;
    }

    public function getArticle(Request $request){
        $page = $request->input('page');
        $limit = $request->input('page_size');
        $data = $this->findRepository->getFindArticle($page, $limit, 1);
        return $data;
    }

    public function getArticleDetail(Request $request){
        $article_id = $request->input('article_id');
        $data = $this->findRepository->getArticleDetail($article_id);
        return $data;
    }

    public function like(Request $request){
        $id = $request->input('article_id');
        $is_cancel = $request->input('is_cancel');
        $data = $this->findRepository->like($id, $is_cancel);
        return $data;
    }

    public function comment(Request $request){
        $data = $this->findRepository->comment();
        return $data;
    }

    public function save(Request $request){
        $id = $request->input('article_id');
        $is_cancel = $request->input('is_cancel');
        $data = $this->findRepository->save($id, $is_cancel);
        return $data;
    }

    public function share(Request $request){
        $uid = Auth::id();
        $article_id = $request->input('article_id');
        $data = $this->findRepository->share($uid, $article_id);
        return $data;
    }

    public function getSaveArticles(Request $request){
        $page = $request->input('page');
        $page_size = $request->input('page_size');
        $data = $this->findRepository->getSaveArticles($page, $page_size);
        return $data;
    }

    public function getDynamicAndArticles(Request $request){
        $uid = Auth::id();
        $page = $request->input('page');
        $data = $this->findRepository->getDynamicAndArticles($uid, $page);
        return $data;
    }
}