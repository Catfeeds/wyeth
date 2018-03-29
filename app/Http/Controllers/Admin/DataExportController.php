<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendExportSignal;

use App\Models\WeekDataExport;
use App\Http\Controllers\Controller;
use Endroid\QrCode\QrCode;
use GuzzleHttp;
use GuzzleHttp\Client;
use Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Cache;
use Input;
use Redirect;
use Response;


class DataExportController extends Controller
{
    public function index()
    {
        $per_page = 10;
        $list = WeekDataExport::orderBy('end_day','DESC')->paginate($per_page);
        //$params = Request::all();
        return view('admin.course.export_new', ['list' => $list])
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function generate()
    {
        $user_info =Session::get('admin_info');
        $per_page =10;
        $params = Request::all();
        $file = Request::file('file');
        if(!isset($file)){
            return view('admin.error',['msg' => '请上传数据文件！']);
        }elseif ($file->getClientOriginalExtension() !=="csv") {
            return View('admin.error',['msg' => '上传文件格式为csv！']);
        }
        //$filePath = $file->getRealPath();
        $state_path = storage_path() . "/WeekData/status.txt";

        if ($file->isValid()) {
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            // 上传文件
            $filename = 'other.'. $ext;
            // 使用新建的uploads本地存储空间（目录）
            $bool = Storage::disk('data')->put($filename, file_get_contents($realPath));
        }
        if(file_exists($state_path)){
            $fp = fopen($state_path,'r');
            $str = fread($fp,filesize($state_path));
            if ($str == '1'){
                fclose($fp);
                return view('admin.error',['msg' => '后台有数据在生成，请稍后再试']);
            }
        }

        //$summary_arr = array($params['week_large_plat'],$params['week_community'],$params['week_other_mgcc'],$params['week_other_jd'],$params['month_large_plat'],$params['month_community'],$params['month_other_mgcc'],$params['month_other_jd']);
        if (isset($params['type']) && $params['type'] == 'export' && $params['cid'])
        {
            $from = !empty($params['from']) ? $params['from'] : null;
            $to = !empty($params['to']) ? $params['to'] : null;
            $startDate = $from ." 00:00:00";
            $cid = $params['cid'];
	     $week_new =explode(',',$cid);
	     /*
            foreach ($summary_arr as $item) {
                if (!is_numeric(trim($item))){
                    return view('admin.error',['msg' => '周报信息格式不正确']);
                }
	        }
	     */

	    foreach($week_new as $v) {
		if (!is_numeric(trim($v))){
			return view('admin.error',['msg' => '请检查推广课程id是否有误']);
		}
	}
            $endDate = $to ." 00:00:00";
            if ($from === null || $to === null || (strtotime($startDate) > strtotime($endDate))){
                return view('admin.error',['msg' => '请填写正确的日期']);
            }
        }
        else {
            return view('admin.error',['msg' => '请填写完整的信息']);
        }
        $params['file'] = storage_path()."/WeekData/other.csv";
        $this->dispatch(new SendExportSignal($state_path,$params['from'],$params['to'],$params['cid'],$params));
        return view('admin.error',['msg' => '正在为您生成数据。。。请稍后刷新网页']);
    }	
    public function export()
    {
        $id = Request::input('id');
	    $type = Request::input('type');
        if($type == 'example'){
            return $this->download();
        }
        if (empty($id) || empty('id') ) {
            return view('admin.error', ['msg' => '参数不正确']);
        }else{
            $url = $type."_url";
            $file_name = DB::table('week_data_export')
                ->where('id',$id)
                ->pluck($url);
            if (!file_exists($file_name)) {
                return view('admin.error', ['msg' => '文件未找到']);
            };
        }
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_name));
        readfile($file_name);
    }

    public function download(){
        $filename = "其他平台和提问数.csv";
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
            , 'Content-type' => 'text/csv'
            , 'Content-Disposition' => 'attachment; filename=' . $filename
            , 'Expires' => '0'
            , 'Pragma' => 'public',
        ];
        $columnNames = [
            '课程id',
            '人数',
            '提问数'
        ];
        $callback = function () use ($headers, $columnNames) {
            $fileHandle = fopen('php://output', 'w');
            $this->str_putcsv($fileHandle, $columnNames);
            fclose($fileHandle);
        };
        return Response::stream($callback, 200, $headers);
    }

    private function str_putcsv($fileHandle, $input, $delimiter = ',', $enclosure = '"')
    {
        $item = [];
        foreach ($input as $v) {
            $item[] = mb_convert_encoding($v, 'GB18030');
        }
        $fp = $fileHandle;
        fputcsv($fp, $item, $delimiter, $enclosure);
    }
}
