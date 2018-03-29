<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2018/2/26
 * Time: 上午10:18
 */

namespace App\Http\Controllers\Wyeth;

use App\CIData\Cidata;
use App\Helpers\SessionKey;
use App\Models\CourseReview;
use App\Repositories\CourseListenRepository;
use App\Repositories\MoLiDataRepository;
use Illuminate\Http\Request;
use App\Repositories\AppConfigRepository;
use App\Repositories\CourseRepository;
use App\Repositories\CourseReviewRepository;
use App\Repositories\TagRepository;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; // cat
use View;

class MoLiDataController extends WyethBaseController{

    protected $moLiDataRepository;

    public function __construct()
    {
        $this->moLiDataRepository = new MoLiDataRepository();
    }

    public function getMoLiData(Request $request){
        $uid = Auth::id();
        $platform = $request->input('platform');
        if(!$platform){
            $platform = 0;
        }
        $data = $this->moLiDataRepository->getMoLiData($uid, $platform);
        return $data;
    }
}