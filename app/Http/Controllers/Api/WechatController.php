<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WxWyeth;
use Illuminate\Http\Request;

class WechatController extends Controller
{
    protected $result = [
        'status' => 1,
        'error_msg' => '',
        'data' => []
    ];

    function getSignPackage(Request $request)
    {
        $wxWyeth = new WxWyeth();
        $this->result['package'] = $wxWyeth->getSignPackage($request->input('url'));

        return response()->json($this->result);
    }

}