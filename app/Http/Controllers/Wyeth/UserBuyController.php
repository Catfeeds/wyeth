<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/9/13
 * Time: 16:46
 */

namespace App\Http\Controllers\Wyeth;

use App\Repositories\UserBuyRepository;
use Illuminate\Http\Request;

class UserBuyController extends WyethBaseController {

    protected $userBuyRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userBuyRepository = new UserBuyRepository();
    }

    public function buyCourse (Request $request) {
        $type = $request->input('type');
        $cid = $request->input('cid');
        return $this->userBuyRepository->buyCourses($type, $cid);
    }

    public function paySuccess (Request $request) {
        $trade_id = $request->input('trade_id');
        return $this->userBuyRepository->paySuccess($trade_id);
    }

    public function getTradeInfo (Request $request) {
        $trade_id = $request->input('trade_id');
        return $this->userBuyRepository->getTradeInfo($trade_id);
    }

    public function getBoughtCat (Request $request) {
        return $this->userBuyRepository->getBoughtCat();
    }

    public function getBoughtCourse (Request $request) {
        return $this->userBuyRepository->getBoughtCourse();
    }
}