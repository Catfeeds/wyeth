<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/9/21
 * Time: 上午10:21
 */

namespace App\Http\Controllers\Wyeth;


use App\Helpers\CacheKey;
use App\Models\Advertise;
use App\Models\User;
use App\Repositories\AdvertiseRepository;
use App\Repositories\CourseListenRepository;
use App\Repositories\TaskRepository;
use App\Repositories\CourseRepository;
use App\Repositories\SearchRepository;
use App\Repositories\TagRepository;
use App\Repositories\AppConfigRepository;
use App\Repositories\UserRepository;
use App\Services\MqService;
use Illuminate\Http\Request;
use Auth;
use Cache;

class AdvertiseController extends WyethBaseController{
    protected $advertiseRepository;

    public function __construct()
    {
        parent::__construct();
        $this->advertiseRepository = new AdvertiseRepository();
    }

    public function getAd(Request $request){
        $position = $request->input('position');
        $brand = $request->input('brand');
        $data = $this->advertiseRepository->getAd($position, $brand);
        return $data;
    }
}