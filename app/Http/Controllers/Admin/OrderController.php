<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/9/18
 * Time: 上午11:56
 */

namespace App\Http\Controllers\Admin;


//后台流水查询
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OrderController extends BaseController
{
    public function index(Request $request){
        $params = $request->all();

        $user_info = Session::get('admin_info');
        $per_page = 10;
        $order = Order::orderBy('created_at', 'desc');

        if (!empty($params['order_no'])){
            $order->where('order_no', 'like', "{$params['order_no']}%");
        }
        if (!empty($params['openid'])){
            $user = User::where('openid', $params['openid'])->first();
            if (!$user){
                $uid = 0;
            }else{
                $uid = $user->id;
            }
            $order->where('uid', $uid);
        }
        if (!empty($params['status'])){
            $order->where('status', $params['status']);
        }

        $list = $order->paginate($per_page);

        foreach ($list as &$item){
            $uid = $item->uid;
            $user = User::find($uid);
            $item->nickname = $user->nickname;
            $item->openid = $user->openid;
        }
        
        return view('admin.order.order', ['list' => $list, 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }
}