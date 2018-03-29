<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/9/25
 * Time: 下午5:12
 */

namespace App\Http\Controllers\Wyeth;

use App\Repositories\PlayListRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // cat
use Auth;
use View;

class PlayListController extends WyethBaseController{

    protected $playListRepository;

    public function __construct()
    {
        parent::__construct();
        $this->playListRepository = new PlayListRepository();
    }

    public function getPlayList(Request $request){
        $uid = Auth::id();
        try{
            $ids = json_decode($request->input('id_array'));
        }catch (\Exception $e){
            return [
                'ret' => -1,
                'msg' => '参数格式不正确'
            ];
        }
        $data = $this->playListRepository->getPlayList($uid, $ids);
        return $data;
    }
}