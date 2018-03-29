<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/9/22
 * Time: 下午3:30
 */

namespace App\Http\Controllers\Wyeth;


use App\Repositories\HdRepository;
use Illuminate\Http\Request;

class HdController extends WyethBaseController
{
    protected $hdRepository;

    public function __construct()
    {
        parent::__construct();
        $this->hdRepository = new HdRepository();
    }

    public function addChance(Request $request){
        return $this->hdRepository->addChance($request->input('num'));
    }

    public function getChance(Request $request){
        return $this->hdRepository->getChance();
    }

    public function getActivity(Request $request){
        return $this->hdRepository->getActivity();
    }
}