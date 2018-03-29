<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/10
 * Time: 15:42
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use GuzzleHttp;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Response;

class CourseDetailsController extends Controller
{
    public function index()
    {	
        return view('admin.course.course_details')
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function update(Request $request){
	$start_day = $request->input('from');
	$end_day = $request->input('to');
	if(!isset($start_day) || !isset($end_day) || (strtotime($start_day)>strtotime($end_day))){
		return view('admin.error',['msg' => '请输入正确的日期！']);
}
	$file = $request->file('file');
	//$ask = $request->file('ask');
        if(!isset($file)){
            return view('admin.error',['msg' => '请上传数据文件！']);
        }elseif ($file->getClientOriginalExtension() !=="csv") {
            return View('admin.error',['msg' => '上传文件格式为csv！']);
        }
        $filePath = $file->getRealPath();
        if ($file->isValid()) {
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $realPath = $file->getRealPath();   //临时文件的绝对路径
		 // 上传文件
            $filename = 'data.'. $ext;
            // 使用我们新建的uploads本地存储空间（目录）
            $bool = Storage::disk('data')->put($filename, file_get_contents($realPath));
        }
	    set_time_limit(0);
            header("Content-Transfer-Encoding: binary");
             ini_set('auto_detect_line_endings', true);
             $path = storage_path()."/WeekData/data.csv";
             setlocale(LC_ALL, 'zh_CN');
             $fp = fopen($path, "r");
             //DB::table('course_detail')->delete();
                while(!feof($fp))
            {
                $data = fgets($fp);
                $data = iconv("gb2312","utf-8//IGNORE",$data);
                $data = explode(',',$data);
                $arr = array(
                    "id"=>$data[0],
	/*
            "ytd_other"=>$data[1],
            "ytd_h5"=>$data[3],
            "now_all_sign"=>$data[4],
            "now_all_edu"=>$data[7],
            "now_other"=>$data[6],
            "now_h5"=>$data[5],

            // "out_date"=>iconv('gb2312','utf-8',$data[9]),
            // "is_order"=>iconv('gb2312','utf-8',$data[10]),
            "title"=>isset($data[9])?"":$data[9],
            // "update_brand"=>iconv('gb2312','utf-8',$data[12]),
	        "now_mudu"=>$data[8],
            "month_all"=>$data[4],
            "month_h5"=>$data[1],
            "month_other"=>$data[1],
            "month_mudu"=>$data[1],
*/
        );   // "question"=>$data[8],

                if(!is_numeric($arr['id'])){
                continue;
            }
            //var_dump($data);
            $arr = array(
                "cid"=>intval($data[0]),
                "start_day"=>date('Y-m-d',strtotime($start_day)),
                "end_day"=>date('Y-m-d',strtotime($end_day)),
                "week_other"=>0,
                "week_mudu"=>0,
                "month_other"=>intval($data[1]),
                "month_mudu"=>intval($data[2]),
                "ytd_h5"=>intval($data[4]),
                "ytd_other"=>intval($data[3]),
                "ytd_mudu"=>0,
                "now_all_sign"=>intval($data[5]),
                "now_all_edu"=>intval($data[6])+intval($data[7]),
                "now_other"=>intval($data[6]),
                "now_h5"=>intval($data[7]),
                // "question"=>$data[8],
		        "ask"=>intval($data[9]),
                "share"=>intval($data[8]),
                "is_order"=>isset($data[11])?str_replace('"','',$data[11]):'',
                "ask_lask_week"=>0,
                "ext"=>isset($data[10])?$data[10]:'', //版权到期时间
                // "out_date"=>iconv('gb2312','utf-8',$data[9]),
                // "is_order"=>iconv('gb2312','utf-8',$data[10]),
                // "update_brand"=>iconv('gb2312','utf-8',$data[12]),
            );

            if($arr['cid']<=0){
                break;
            }
            $is_exist = DB::table('course_detail')->where('cid',intval($arr['cid']))->get();
            if($is_exist){
                $res= DB::table('course_detail')->where('cid',$arr['cid'])->update($arr);
            }else{
                $res= DB::table('course_detail')->insert($arr);
            }
        }
        return view('admin.error',['msg' => '更新完成！']);
    }

}
