<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/9/25
 * Time: 下午1:53
 */


namespace App\Http\Controllers\Wyeth;

use App\Repositories\TagQuestionRepository;
use Illuminate\Http\Request;

class TagQuestionController extends WyethBaseController{
    protected $tagQuestionRepository;

    public function __construct()
    {
        parent::__construct();
        $this->tagQuestionRepository = new TagQuestionRepository();
    }

    public function getTagQuestion(Request $request){
        $tid = $request->input('tid');
        $data = $this->tagQuestionRepository->getTagQuestion($tid);
        return $data;
    }
}