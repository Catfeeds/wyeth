<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2018/1/5
 * Time: 17:40
 */

namespace App\Http\Controllers\Admin;


use App\CIService\CIDataOpenApi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EventController extends BaseController
{

    public function index(Request $request)
    {
        $begin_time = strtotime(date('Y-m-d',time() - 86400));
        $end_time = strtotime(date('Y-m-d',time()));
        $cidata = new CIDataOpenApi();
        $per_page = 10;
        $params = $request->all();
        // 事件列表list
        if(empty($params['id'])){
            $list = DB::table('statistic_event')
                ->where('status',1)
                ->paginate($per_page);

            $trashList = DB::table('statistic_event')
                ->where('status',-1)
                ->paginate($per_page);
        }else{
            $id = $params['id'];
            $list = DB::table('statistic_event')
                ->where('status',1)
                ->where('name','like','%'.$id.'%')
                ->orWhere('desc','like','%'.$id.'%')
                ->paginate($per_page);
            $trashList = DB::table('statistic_event')
                ->where('status',-1)
                ->where('name','like','%'.$id.'%')
                ->orWhere('desc','like','%'.$id.'%')
                ->paginate($per_page);
        }


        foreach ($list as $item) {
            $event_id = $item->name;
            $num_active = $cidata->eventCount($begin_time,$end_time,'active',$event_id,'all');
            if(isset($num_active['data'][0]['user'])){
                $num = $num_active['data'][0]['user'];
                if(isset($num_active['data'][0]['event_count'])){
                    $pv = $num_active['data'][0]['event_count'];
                }else{
                    $pv = $num;
                }
            }else{
                $num = '';
                $pv = '';
            }
            // pv已提供，但是上线时间后才会有数据，之前的数据算 和num一样
            $item->num = $pv;
            $item->user_num = $num;
            if($item->status == 1){
                $item->lab_status = '正常';
            } else{
                $item->lab_status = '失效';
            }
        }

        foreach ($trashList as $item) {
            $event_id = $item->name;
            $num_active = $cidata->eventCount($begin_time,$end_time,'active',$event_id,'all');
            if(isset($num_active['data'][0]['user'])){
                $num = $num_active['data'][0]['user'];
                $pv = $num_active['data'][0]['event_count'];
            }else{
                $num = '';
                $pv = '';
            }
            // 目前pv没有提供,先算成一样的
            $item->num = $pv;
            $item->user_num = $num;
            if($item->status == 1){
                $item->lab_status = '正常';
            } else{
                $item->lab_status = '失效';
            }
        }
        return view('admin.statistics.event', ['list' => $list,'trashList' => $trashList,'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }
    public function detail(Request $request ,$id)
    {
        $begin_time = strtotime(date('Y-m-d',time() - 86400*29));
        $end_time = strtotime(date('Y-m-d',time()));
        $cidata = new CIDataOpenApi();
        $num_active = $cidata->eventCount($begin_time,$end_time,'active',$id,'day');
        $data = array();
        $pv_data = array();
        if(isset($num_active['data'][0]['user'])){
            foreach($num_active['data'] as $k => $item){
                $date = date('Y-m-d',$item['time']);
                $num = $item['user'];
                $data[$date] = $num;
                // pv上线时间不一致，有可能没有数据
                if(isset($item['event_count'])){
                    $pv = $item['event_count'];
                    $pv_data[$date] = $pv;
                }else{
                    $pv_data[$date] = $num;
                }

            }
        }else{
            return view('admin.error',['msg' => '获取信息超时！']);
        }
        $params = $request->all();
        $item = (object)array('id' => $id,'data' => $data,'pv_data' => $pv_data);

        return view('admin.statistics.eventdetail',['item' => $item,'params' => $params])
            ->nest('header', 'admin.common.header',['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    // 获取某一事件的详情
    public function getDetail(Request $request,$id)
    {
        // 检查id是否存在
        $ret = DB::table('statistic_event')->where('name',$id);
        if(!$ret){
            return view('admin.error',['msg' => '没有此事件信息！']);
        }
        $begin_time = strtotime(date('Y-m-d',time() - 86400*29));
        $end_time = strtotime(date('Y-m-d',time()));
        $cidata = new CIDataOpenApi();
        $num_active = $cidata->eventCount($begin_time,$end_time,'active',$id,'day');
        $data = array();
        $pv_data = array();
        if(isset($num_active['data'][0]['user'])){
            foreach($num_active['data'] as $k => $item){
                $date = date('Y-m-d',$item['time']);
                $num = $item['user'];
                $data[$date] = $num;
                // pv上线时间不一致，有可能没有数据
                if(isset($item['event_count'])){
                    $pv = $item['event_count'];
                    $pv_data[$date] = $pv;
                }else{
                    $pv_data[$date] = $num;
                }
            }
        }else{
            return view('admin.error',['msg' => '获取信息超时！']);
        }
        // data: 日期=>uv; pv_data : 日期=>pv;
        $item = (object)array('id' => $id,'data' => $data,'pv_data' => $pv_data);
        return response()->json($item);
    }


    // 删除指定的event_id
    public function delete(Request $request){
        $ids = $request->input('ids');
        //$ids = json_decode($request->input('ids'),true);
        $error = false;
        try{
            foreach ($ids as $id){
                DB::table('statistic_event')->where('name',$id)
                    ->update(['status' => -1]);
            }
        }catch (\Exception $e){
            $error = true;
        }

        if($error){
            return $this->ajaxError('删除失败', 0);;

        }else{
            return $this->ajaxMsg('删除成功',1);
        }

    }

    // 获取所有 正常的 event_id列表
    public function getEventList(){
        $list = DB::table('statistic_event')
            ->where('status',1)
            ->get();
        $ids = array();
        foreach ($list as $item) {
            $ids[$item->name] = $item->desc;
        }
        return response()->json($ids);
    }

    // 搜索事件
    public function search(Request $request){
        $id = $request->input('id');
        if($id){
            $begin_time = strtotime(date('Y-m-d',time() - 86400));
            $end_time = strtotime(date('Y-m-d',time()));
            $cidata = new CIDataOpenApi();
            $params = $request->all();
            // 事件列表list
            $per_page = 10;
            $list = DB::table('statistic_event')->where('status','!=',-1)->where('name','like','%'.$id.'%')->paginate($per_page);;
            if(!empty($list)){
                foreach ($list as $item) {
                    $event_id = $item->name;
                    $num_active = $cidata->eventCount($begin_time,$end_time,'active',$event_id,'all');
                    if(isset($num_active['data'][0]['user'])){
                        $num = $num_active['data'][0]['user'];
                    }else{
                        $num = '';
                    }
                    // 目前pv没有提供,先算成一样的
                    $item->num = $num;
                    $item->user_num = $num;
                    if($item->status == 1){
                        $item->lab_status = '正常';
                    } elseif($item->status == 0){
                        $item->lab_status = '失效';
                    }else{
                        $item->lab_status = '正常';
                    }
                }
            }
            $trashList = DB::table('statistic_event')->where('status','=',-1)->where('name','like','%'.$id.'%')->paginate($per_page);;
            if(!empty($trashList)){
                foreach ($trashList as $item) {
                    $event_id = $item->name;
                    $num_active = $cidata->eventCount($begin_time,$end_time,'active',$event_id,'all');
                    if(isset($num_active['data'][0]['user'])){
                        $num = $num_active['data'][0]['user'];
                    }else{
                        $num = '';
                    }
                    // 目前pv没有提供,先算成一样的
                    $item->num = $num;
                    $item->user_num = $num;
                    if($item->status == 1){
                        $item->lab_status = '正常';
                    } elseif($item->status == 0){
                        $item->lab_status = '失效';
                    }else{
                        $item->lab_status = '正常';
                    }
                }
            }
            return view('admin.statistics.event', ['list' => $list,'params' => $params,'trashList' => $trashList])
                ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
                ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
                ->nest('footer', 'admin.common.footer', []);
        }else{
            return view('admin.error',['msg' => '事件id不能为空！']);
        }
    }

}
