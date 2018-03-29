<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/28
 * Time: 上午11:11
 */

namespace App\Http\Controllers\Wyeth;

use Illuminate\Http\Request;
use App\Repositories\AppConfigRepository;
use App\Repositories\CourseRepository;
use App\Repositories\CourseReviewRepository;
use App\Repositories\SearchRepository;


use Illuminate\Support\Facades\Session; // cat
use View;

class SearchController extends WyethBaseController{

    protected $searchRepository;

    public function __construct()
    {
        parent::__construct();
        $this->searchRepository = new SearchRepository();
    }

    public function getSearchResult(Request $request){
        $keyword = $request->input('keyword');
        $page = $request->input('page');
        if (env('APP_ENV') == 'local') {
            $data = $this->searchRepository->getNewSearchResult($keyword, $page);
        }else{
            $data = $this->searchRepository->getSearchResult($keyword, $page);
        }
        return $data;
    }

    public function getCourseSearch(Request $request){
        $keyword = $request->input('keyword');
        $page = $request->input('page');
        $data = $this->searchRepository->getSearchResult($keyword, $page);
        return $data;
    }

    public function getQuestionSearch(Request $request){
        $keyword = $request->input('keyword');
        $page = $request->input('page');
        $data = $this->searchRepository->getQuestionSearch($keyword, $page);
        return $data;
    }

    public function getSearchTag(){
        $data = $this->searchRepository->getSearchTag();
        return $data;
    }
}