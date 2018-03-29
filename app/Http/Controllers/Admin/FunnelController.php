<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2018/1/5
 * Time: 17:40
 */

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\CIService\CIDataFunnel;

class FunnelController extends BaseController
{

    public function index(Request $request)
    {
        $funnel = new CIDataFunnel();
        $page = $request->input('page');
        $limit = $request->input('limit');
        if(!isset($page)){
            $page = 1;
        }
        if(isset($limit)){
            $limit = 10;
        }
        $result = $funnel->index(intval($page), intval($limit));
        $data = $result['data'];
        $data['currentPage'] = $page;

        return view('admin.statistics.funnel', ['data' => $data])
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    // 新增漏斗
    public function add(Request $request)
    {
        $funnel = new CIDataFunnel();
        // 前端传入数据
        $name = $request->input('name');
        $steps = $request->input('steps');

        $timeout = 10000;
        $result = $funnel->create($name, $timeout, $steps);
        $ret = $result['ret'];
        if($ret == 0){
            return $this->ajaxMsg($result['data'],1);
        }
        else{
            return $this->ajaxMsg($result['msg'],0);
        }
    }


    // 更新漏斗
    public function update(Request $request)
    {
        $funnel = new CIDataFunnel();
        // 前端传入数据
        $name = $request->input('name');
        $steps = $request->input('steps');
        $id = $request->input('id');
        $timeout = 10000;

        $result = $funnel->update($name, $timeout, $steps, intval($id));
        $ret = $result['ret'];
        if ($ret == 0){
            return $this->ajaxMsg($result['data'], 1);
        } else {
            return $this->ajaxMsg($result['msg'], 0);
        }
    }

    public function edit()
    {
        return view('admin.statistics.funneledit',['user_info' => Session::get('admin_info')])
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    // 获取漏斗详情,不需要返回页面
    public function detail($id)
    {
        $funnel = new CIDataFunnel();
        $result = $funnel->detail($id);
        $ret = $result['ret'];
        if ($ret == 0){
            return $this->ajaxMsg($result['data'], 1);
        } else {
            return $this->ajaxMsg($result['msg'], 0);
        }

    }

    // 获取漏斗详情,需要返回页面
    // 预留前端筛选数据接口
    public function conversion(Request $request, $id)
    {
        $funnel = new CIDataFunnel();

        // 获取当日零点时间戳
        $current_time = strtotime(date('Y-m-d',time()));

        $begin_time = $request->input('begin_time');
        $end_time = $request->input('end_time');
        $type = $request->input('type');
        if(!isset($begin_time)){
            $begin_time = $current_time - 3600*24;
        }
        if(!isset($end_time)){
            $end_time = $current_time;
        }
        if(!isset($type)){
            $type = "direct_path";
        }
        $result = $funnel->conversion($begin_time, $end_time, $id, $type);
        $ret = $result['ret'];
        if($ret != 0){
            return view('admin.error',['msg' => $result['msg']]);
        }else{
            $data = $result['data'];
        }
        return view('admin.statistics.funneldetail',['data' => $data])
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    // 删除漏斗
    public function delete(Request $request)
    {
        $funnel = new CIDataFunnel();
        $list = $request->input('ids_rm');
        /*foreach ($list as $item)
        {
            $funnel->delete($item);
        }*/
        for($i = 0; $i < count($list); $i++){
            $result=$funnel->delete($list[$i]);
        }
        return $this->ajaxMsg('删除完成', 1);

    }
}
