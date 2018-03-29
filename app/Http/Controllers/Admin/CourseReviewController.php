<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\getAudioDuration;
use App\Models\Course;
use App\Models\CourseTag;
use App\Models\Courseware;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Redirect, Input;
use App\Services\Qnupload;
use GuzzleHttp;
use App\Models\CourseReview;
use App\Models\Message;
use ZipArchive;
use Endroid\QrCode\QrCode;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;

require app_path() . '/Helpers/html2pdf/mpdf.php';
require app_path() . '/Helpers/simple_html_dom.php';

class CourseReviewController extends Controller
{
    var $status_arr = [0=>'无效',1=>'有效'];

    function index()
    {
        $user_info = Session::get('admin_info');
        $per_page = 10;
        $cIn = Course::lists('id');
        $md = CourseReview::whereIn('cid', $cIn);
        $list = $md->paginate($per_page);
        foreach($list as &$v) {
            $course_info = DB::table('course')->where('id', $v->cid)->first();
            $v->course_name = $course_info->title;
            $v->number = $course_info->number;

        }
        return view('admin.course_review.index',['list'=>$list,'status_arr'=>$this->status_arr])
                ->nest('header', 'admin.common.header', ['user_info'=>$user_info])
                ->nest('sidebar', 'admin.common.sidebar', ['menu'=>Session::get('menu')])
                ->nest('footer', 'admin.common.footer', []);
    }

    function add(Request $request, $cid)
    {
        $user_info = Session::get('admin_info');
        $data = $request->all();
        empty($data['status']) && $data['status'] = 0;
        if (!empty($data['cid'])) {
            if ($_FILES['audio']['size'] > 0) {
                $data['audio'] = Qnupload::upload($_FILES['audio']);
            }
            if ($_FILES['share_picture']['size'] > 0) {
                $data['share_picture'] = Qnupload::upload($_FILES['share_picture']);
            }
            if ($_FILES['video_cover']['size'] > 0) {
                $data['video_cover'] = Qnupload::upload($_FILES['video_cover']);
            }
            if ($_FILES['teacher_avatar']['size'] > 0) {
                $data['teacher_avatar'] = Qnupload::upload($_FILES['teacher_avatar'], null, 'course/teacheravatar');
            }
            if (isset($data['q_and_a']) && !$this->isEmpty($data['q_and_a'])){
                $qAndA = $data['q_and_a'];
                unset($data['q_and_a']);
            } else {
                $qAndA = null;
                unset($data['q_and_a']);
            }
            if(isset($data['section']) && !$this->isEmpty($data['section'])) {
                foreach ($data['section'] as $k=>$v) {
                    $second = (int) $v['second'];
                    $data['section'][$k]['second'] = $second;
                    if(isset($v['section']) && !$this->isEmpty($v['section'])){
                        $data['section'][$k]['second'] = $v['section'][0]['second'];
                        foreach($v['section'] as $a=>$item){
                            if(!isset($item['second']) || !is_numeric($item['second'])){
                                return view('admin.error', ['msg'=>'添加失败，请填写正确的时间']);
                            }
                            $data['section'][$k]['section'][$a]['point']=$item['point'];
                            $data['section'][$k]['section'][$a]['second']=$item['second'];
                        }
                    }elseif(isset($v['section']) && $this->isEmpty($v['section'])){
                        return view('admin.error', ['msg'=>'添加失败，每章至少要有一节']);
                    }
                }
            }
            if (isset($data['section']) && !$this->isEmpty($data['section'])){
                $section = $data['section'];
                unset($data['section']);
            } else {
                $section = '';
                unset($data['section']);
            }
            $data['created_at'] = date('Y-m-d H:i:s');

            if (isset($data['guide'])){
                $html = str_get_html($data['guide']);
                if ($html) {
                    $thumb_html = '';
                    foreach ($html->find('img') as $element) {
                        ini_set( 'memory_limit', '220M' );            //为支持大图片增加内存限制

                        list($width, $height) = getimagesize($element->src);  //获取大图片的属性
                        if ($height > 1000) {
                            $picW = $width;
                            $picH = 1000;

                            $basename = basename($element->src);
                            $ext = strtolower(pathinfo($basename)['extension']);
                            if ($ext == 'jpg' || $ext == 'jpeg') {
                                $image = imagecreatefromjpeg($element->src);
                            } else {
                                $image = imagecreatefrompng($element->src);
                            }

                            $p = ceil($height / $picH);
                            $last = $height % $picH;

                            for( $i = 0 ; $i < $p; $i++ ){
                                $_p = $picH * $i;
                                if( ( $i + 1 ) == $p ) {
                                    $picH = $last;
                                }
                                $thumb = ImageCreateTrueColor($picW, $picH);
                                $colBG = imagecolorallocate($thumb, 255, 255, 255);//白色背景
                                imagefill( $thumb, 0, 0, $colBG );//加白色背景
                                imagecopyresized( $thumb, $image, 0, 0, 0, $_p, $picW,  $height, $width, $height);
                                imagejpeg($thumb , "/tmp/temp_tiny.jpg" ,100);
                                $url = Qnupload::uploadTmp("/tmp/temp_tiny.jpg", 'course/image/sub', $i);
                                $thumb_html .= '<img src="' . $url . '" title="' . $element->title .'" alt="' . $element->alt . '"/>';
                                imagedestroy($thumb);
                            }
                            $t = '<img src="' . $element->src . '" title="' . $element->title . '" alt="' . $element->alt . '"/>';
                            $data['guide'] = str_replace($t, $thumb_html, $data['guide']);
                            $thumb_html = '';

                            imagedestroy($image);//释放与 $image 关联的内存
                        }
                    }

                    @unlink("/tmp/temp_tiny.jpg");
                }
            }

            $review_id = DB::table('course_review')->insertGetId($data);
            if ($review_id > 0) {
                $courseReview = CourseReview::find($review_id);
                $courseReview->q_and_a = $qAndA;
                $courseReview->section = $section;
                $courseReview->save();
	            if(isset($data['audio']) && !empty($data['audio'])){
	                $url = $data['audio'];
                    $this->dispatch(new getAudioDuration($url,$review_id));
}

                return view('admin.error', ['msg'=>'添加成功，进入回顾课程列表','url'=>'/admin/course_review']);
            } else {
                return view('admin.error', ['msg'=>'添加失败，请重试']);
            }
        }
        $domain = config('qiniu.domain');
        $course_list = DB::table('course')->get();
        return view('admin.course_review.add',['course_list'=>$course_list, 'cid'=>$cid, 'domain' => $domain])
            ->nest('header', 'admin.common.header', ['user_info'=>$user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu'=>Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function manage(Request $request) {
        $user_info = Session::get('admin_info');
        $per_page = 10;
        $params = [];
        $params['id'] = $request->input('id');
        $params['title'] = $request->input('title');
        $params['teacher'] = $request->input('teacher');
        $params['status'] = $request->input('status');

        if ($params['id']) {
            if (Course::find($params['id'])) {
                $md = CourseReview::where('cid', '=', $params['id']);
            } else {
                $md = CourseReview::where('cid', '<', 0);
            }
        } else {
            $cIn = Course::lists('id');
            $md = CourseReview::whereIn('cid', $cIn);
        }

        if (!empty($params['title'])) {
            $courses = Course::where("title", "like", "%" . $params['title'] . "%")->lists('id');
            $md->whereIn('cid', $courses)->get();
        }

        if (!empty($params['teacher'])) {
            $this->searchByLecturer($params['teacher'], $md);
        }

        if ($params['status'] == 1) {
            $md->where('status', 1);
        } else if ($params['status'] == 2) {
            $md->where('status', 0);
        }

        if ($user_info->user_type == 2 || $user_info->user_type == 3) {
            $md->whereIn('cid', $user_info->cids);
        }

        $list = $md->orderBy('id', 'desc')->paginate($per_page);

        foreach ($list as $item) {
            $course = DB::table('course')->where('id', $item->cid)->first();
            if ($course) {
                $item->title = $course->title;
            }
            $course_tag = CourseTag::where('cid', $item->cid)->where('type', Tag::TAG_TEACHER)->first();
            if ($course_tag) {
                $item->teacher = Tag::where('id', $course_tag->tid)->first()->name;
            }
        }
        return view('admin.course.manage', ['list' => $list, 'params' => $params, 'user_info' => $user_info])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    function searchByLecturer($name, $md) {
        $tags = Tag::where("name", "like", "%" . $name . "%")->where("type", Tag::TAG_TEACHER)->lists('id');
        if (count($tags) > 0) {
            $courseTags = CourseTag::whereIn('tid', $tags)->lists('cid');
            if (count($courseTags) > 0) {
                return $md->whereIn('cid', $courseTags)->get();
            }
        } else {
            return $md->where('id', '<', 0);
        }
    }

    public function downloadZipByImageUrl(Request $request, $cid) {
        $imageDir = "/tmp/course_image/";
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }
        $this->delDirFiles($imageDir);

        $imageUrls = Courseware::where('cid', $cid)->lists('img');
        $filename = $imageDir . "course" . $cid . ".zip";
        $zip = new ZipArchive();
        if ($zip->open($filename, ZIPARCHIVE::CREATE) != TRUE) {
            exit('无法打开文件，或者文件创建失败');
        }
        foreach( $imageUrls as $index => $val){
            $basename = basename($val);
            $ext = strtolower(pathinfo($basename)['extension']);
            if ($ext == 'jpg' || $ext == 'jpeg') {
                $im = @imagecreatefromjpeg($val);
                imagejpeg($im, $imageDir . "$index.jpg", 100);
            } else if ($ext == 'png') {
                $im = @imagecreatefrompng($val);
                imagepng($im, $imageDir . "$index.png", 0);
            } else {
                $im = @imagecreatefromjpeg($val);
                $ext = 'jpg';
                if (!$im) {
                    $im = @imagecreatefrompng($val);
                    $ext = 'png';
                }
                imagepng($im, $imageDir . "$index.$ext", 0);
            }

            if(file_exists($imageDir . "$index.$ext")){
                $zip->addFile($imageDir . "$index.$ext" , basename($imageDir . "$index.$ext"));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
            }
            imagedestroy($im);
        }
        $zip->close();//关闭
        if(!file_exists($filename)){
            exit("无法找到文件"); //即使创建，仍有可能失败。。。。
        }
        return response()->download($filename);
    }

    public function downloadHtml(Request $request, $id) {
        $htmlDir = storage_path() . '/html2pdf/';
        if (!is_dir($htmlDir)) {
            mkdir($htmlDir, 0777, true);
        }
//        $this->delDirFiles($htmlDir);
        $c = CourseReview::where('id', $id)->first();
        $p = '<!DOCTYPE html><html lang="en"><head>
                <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
                <style>#container>p{display: flex; align-items: flex-start; flex-direction: column}</style></head>
                <body><div id="container" style="">' . $c->guide . $c->desc . '</div></body></html>';
//        file_put_contents($htmlDir . "desc$id.html", iconv('utf-8', 'GB2312//IGNORE', $p));
//        return response()->download($htmlDir . "desc$id.html");
        return response()->download($htmlDir . $this->html2pdf($p, $htmlDir, $id, 180, 2000));
    }

    function html2pdf ($html, $PATH, $id, $w=180 ,$h=1736) {
        $mpdf = new \mPDF('utf-8');
        //设置字体，解决中文乱码
        $mpdf->useAdobeCJK = TRUE;
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        // 设置pdf尺寸
        $mpdf->WriteHTML('<pagebreak sheet-size="'.$w.'mm '.$h.'mm" />');

        //设置pdf显示方式
        $mpdf->SetDisplayMode('fullpage');

        //删除pdf第一页(由于设置pdf尺寸导致多出了一页)
        $mpdf->DeletePages(1,1);

        $mpdf->WriteHTML($html);

        $pdf_name = 'course' . $id .'.pdf';

        $mpdf->Output($PATH.$pdf_name);

        return $pdf_name;
    }

    function delDirFiles($dirName) {
        if(file_exists($dirName) && $handle=opendir($dirName)){
            while(false!==($item = readdir($handle))){
                if($item!= "." && $item != ".."){
                    if(file_exists($dirName.'/'.$item) && is_dir($dirName.'/'.$item)){
                        $this->delDirFiles($dirName.'/'.$item);
                    }else{
                        if(unlink($dirName.'/'.$item)){
                            return true;
                        }
                    }
                }
            }
            closedir( $handle);
        }
    }


    function getUpToken() {
        $token = Qnupload::getUpToken();
        $result = [
            'uptoken' => $token
        ];
        return response()->json($result);
    }

    function edit(Request $request, $id)
    {
        $user_info = Session::get('admin_info');
        $id = intval($id);
        empty($id) && Redirect('/admin/course_review/add');
        $info = CourseReview::find($id);
        if (empty($info)) {
            return view('admin.error', ['msg'=>'数据不存在']);
        }
        $cid = $info->cid;
        $data = $request->all();
        empty($data['status']) && $data['status'] = 0;
        if (!empty($data['cid'])) {
            if (!empty($_FILES['audio']) && $_FILES['audio']['size'] > 0) {
                // $key = null, $pathPre = 'default'
                $data['audio'] = Qnupload::upload($_FILES['audio'], null, 'course/audio');
}
            if ($_FILES['share_picture']['size'] > 0) {
                $data['share_picture'] = Qnupload::upload($_FILES['share_picture'], null, 'course/share');
            }
            if ($_FILES['video_cover']['size'] > 0) {
                $data['video_cover'] = Qnupload::upload($_FILES['video_cover'], null, 'course/videocover');
            }
            if ($_FILES['teacher_avatar']['size'] > 0) {
                $data['teacher_avatar'] = Qnupload::upload($_FILES['teacher_avatar'], null, 'course/teacheravatar');
            }
            if (isset($data['q_and_a']) && !$this->isEmpty($data['q_and_a'])){
                $info->q_and_a = $data['q_and_a'];
                $info->save();
            } else {
                $info->q_and_a = null;
                $info->save();
            }

            if(isset($data['section']) && !$this->isEmpty($data['section'])) {
                foreach ($data['section'] as $k=>$v) {
                    $second = (int) $v['second'];
                    $data['section'][$k]['second'] = $second;
		    if(isset($v['section']) && !$this->isEmpty($v['section'])){
		        $data['section'][$k]['second'] = $v['section'][0]['second'];
		    foreach($v['section'] as $a=>$item){
                if(!isset($item['second']) || !is_numeric($item['second'])){
                    return view('admin.error', ['msg'=>'添加失败，请在时间输入框填写正确的数值']);
                }
                $data['section'][$k]['section'][$a]['point']=$item['point'];
			    $data['section'][$k]['section'][$a]['second']=$item['second'];
			}
			}elseif(isset($v['section']) && $this->isEmpty($v['section'])){
                return view('admin.error', ['msg'=>'添加失败，每章至少要有一节']);
            }

                }
            }
            if (isset($data['section']) && !$this->isEmpty($data['section'])){
                $info->section = $data['section'];
                $info->save();
            } else {
                $info->section = null;
                $info->save();
            }

            if(isset($data['content'])) {
                $info->content = $data['content'];
                $info->save();
            } else{
                $info->content = '';
                $info->save();
            }

            if (isset($data['audio']) && empty($data['audio'])) unset($data['audio']);
            if (isset($data['video']) && empty($data['video'])) unset($data['video']);
            if (isset($data['q_and_a'])) unset($data['q_and_a']);
            if (isset($data['section'])) unset($data['section']);
            if (isset($data['content']) && empty($data['content'])) unset($data['content']);

            if (isset($data['guide'])) {
                $html = str_get_html($data['guide']);
                if ($html) {
                    $thumb_html = '';
                    $imgs = $html->find('img');
                    foreach ($imgs as $element) {
                        ini_set('memory_limit', '220M');            //为支持大图片增加内存限制

                        list($width, $height) = getimagesize($element->src);  //获取大图片的属性
                        if ($height > 1000) {
                            $picW = $width;
                            $picH = 1000;

                            $basename = basename($element->src);
                            $ext = strtolower(pathinfo($basename)['extension']);
                            if ($ext == 'jpg' || $ext == 'jpeg') {
                                $image = imagecreatefromjpeg($element->src);
                            } else {
                                $image = imagecreatefrompng($element->src);
                            }

//                        $image = imagecreatefromjpeg($element->src);
                            $p = ceil($height / $picH);
                            $last = $height % $picH;

                            for ($i = 0; $i < $p; $i++) {
                                $_p = $picH * $i;
                                if (($i + 1) == $p) {
                                    $picH = $last;
                                }
                                if ($picH > 0) {
                                    $thumb = ImageCreateTrueColor($picW, $picH);
//                                $colBG = imagecolorallocate($thumb, 255, 255, 255);//白色背景
//                                imagefill($thumb, 0, 0, $colBG);//加白色背景
                                    imagecopyresized($thumb, $image, 0, 0, 0, $_p, $picW, $height, $width, $height);
                                    imagejpeg($thumb, "/tmp/temp_tiny.jpg", 100);
                                    $url = Qnupload::uploadTmp("/tmp/temp_tiny.jpg", 'course/image/sub', $i);
                                    $thumb_html .= '<img src="' . $url . '" title="' . $element->title . '" alt="' . $element->alt . '"/>';

                                    imagedestroy($thumb);
                                }
                            }
                            // 拼出img标签
                            $t = '<img';
                            foreach ($element->attr as $key => $attr) {
                                $t .= ' ' . $key . '="' . $attr . '"';
                            }
                            $t .= '/>';
                            $data['guide'] = str_replace($t, $thumb_html, $data['guide']);
                            $thumb_html = '';
                            imagedestroy($image);//释放与 $image 关联的内存
                        }
                    }

                    @unlink("/tmp/temp_tiny.jpg");
                }
            }

            DB::table('course_review')->where('id',$id)->update($data);

            if(isset($data['audio']) && !empty($data['audio'])) {
                $url = $data['audio'];
                $this->dispatch(new getAudioDuration($url, $id));
            }

            return view('admin.error', ['msg'=>'已更新','url'=>'/admin/course_review']);
        }
        $courseReviewQuestions = Message::where('cid', $cid)
            ->where('type', Message::TYPE_TEXT)
            ->where('state', Message::ANSWERED)
            ->get();
        $course_list = DB::table('course')->get();
        $domain = config('qiniu.domain');
        $data = [
            'info' => $info,
            'id' => $id,
            'course_list' => $course_list,
            'domain' => $domain,
            'courseReviewQuestions' => $courseReviewQuestions,
        ];
        return view('admin.course_review.edit', $data)
            ->nest('header', 'admin.common.header', ['user_info'=>$user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu'=>Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }


    public function imageUpload(Request $request)
    {
        $url = '';
        if ($_FILES['file']['size'] > 0) {
            $url = Qnupload::upload($_FILES['file'], null, 'course/image');
        }
        return response()->json(['url' => $url]);
    }

    private function isEmpty($array)
    {
        foreach ($array as $v) {
            foreach ($v as $vv) {
                if ($vv) {
                    return false;
                }
            }
        }
        return true;
    }

    function delete($id)
    {
        $id = intval($id);
        $result = DB::table('course_review')->where("id",$id)->delete();
        if ($result) {
            return view('admin.error', ['msg'=>'已删除','url'=>'/admin/course_review']);
        } else {
            return view('admin.error', ['msg'=>'删除失败', 'url'=>'/admin/course_review']);
        }
    }

    function delete_all(Request $request)
    {
        $params = $request->all();
        $id_arr = $params['id'];
        if(empty($id_arr)){
            return view('admin.error', ['msg'=>'请选择要删除的对象']);
        }
        DB::table('course_review')->whereIn('id',$id_arr)->delete();
        $refer = !empty($_SERVER['HTTP_REFER'])?$_SERVER['HTTP_REFER']:'';
        return view('admin.error', ['msg'=>'已删除','url'=>$refer]);
    }

    public function questions(Request $request)
    {
        $cid = $request->input('cid');
        $courseReviewQuestions = Message::where('cid', $cid)
            ->where('type', Message::TYPE_TEXT)
            ->where('state', Message::ANSWERED)
            ->get();
        return response()->json($courseReviewQuestions);
    }

    public function ueditorImageUpload(Request $request)
    {
        \Debugbar::disable();
        $con = '{
            "imageActionName": "uploadimage",
            "imageFieldName": "upfile", 
            "imageMaxSize": 2048000, 
            "imageAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"],
            "imageCompressEnable": true,
            "imageCompressBorder": 1600,
            "imageInsertAlign": "none",
            "imageUrlPrefix": "",
            "imagePathFormat": ""
        }';

        $config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", $con), true);
        switch ($_GET['action']) {
            case 'config':
                $result = json_encode($config);
                break;
            case 'uploadimage':
                $url = '';
                if ($_FILES['upfile']['size'] > 0) {
                    $url = Qnupload::upload($_FILES['upfile'], null, 'course/image');
                }
                $result = array(
                    "state" => 'SUCCESS',
                    "url" => $url,
                    "title" => trim(strrchr($url, '/'), '/'),
                    "original" => $_FILES['upfile']['name'],
                    "type" => $_FILES['upfile']['type'],
                    "size" => $_FILES['upfile']['size']
                );
                $result = json_encode($result);
                break;
            default:
                $result = '';
        }
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state' => 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }
}
