<?php

namespace App\Console\Commands;

use App\CIService\CIDataQuery;
use App\Repositories\CourseRepository;
use App\Services\WxWyeth;
use Illuminate\Console\Command;
use App\Models\CourseReview;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use getID3;
use App\Models\CiAppConfig;
use Illuminate\Support\Facades\Crypt;
use App\Models\CoursePush;

class UpdateAudioInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string

     */
    protected $signature = 'audio:update {--type=} {--cid=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'type : duration | text';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        header("Content-Type:text/html;charset=utf-8");
        $type = $this->option('type');
        $cid = $this->option('cid');
        if ($type == 'duration') {
            if (!empty($cid) && is_numeric($cid) && $cid>0) {
                $res = $this->updateDuration($id = $cid);
            } else {
                $res = $this->updateDuration();
            }
        } elseif ($type == 'text') {
            $res = 0;
            if (!empty($cid) && is_numeric($cid)) {
                $res = $this->updateContent($cid);
            } else {
                $res = $this->updateContent();
            }
        } else {
            $this->error('Invalid input');
        }
    }

    function updateDuration($id = null)
    {
        // 更新所有音频时长
        ini_set('memory_limit', '500M');
        $getID3 = new getID3();
        $courseAll = DB::table('course_review')->select('id')->get();
        if ($id == null) {
            $ids = array();
            foreach ($courseAll as $k => $value) {
                $ids[] = $value->id;
            }
            foreach ($ids as $id) {
                $value = DB::table('course_review')->where('id', $id)->select('id','cid', 'audio', 'audio_duration', 'review_type')->get()[0];
                //die();
                $src = $value->audio;
                if ($value->review_type == 2 || empty($src)) {
                    continue;
                }
                $cid= $value->cid;
                $file = $this->httpcopy($src);
                $fileInfo = $getID3->analyze($file);
                $fileDuration = floor($fileInfo['playtime_seconds']);
                DB::table('course_review')->where('id', $value->id)->update(['audio_duration' => $fileDuration]);
                $this->info('updated--cid--'.$cid);
            }
        } else {
            $id = intval($id);
            $value = DB::table('course_review')->where('cid', $id)->select('id', 'audio', 'review_type')->get();
            if (empty($value)) {
                $this->error("\n" . 'cid Not Found in DataBase');
                die();
            }
            $value = $value[0];
            $src = $value->audio;
            if ($value->review_type == 2 || empty($src)) {
                $this->error("cid type is video");
                die();
            }
            $file = $this->httpcopy($src);
            $fileInfo = $getID3->analyze($file);
            $fileDuration = floor($fileInfo['playtime_seconds']);
            DB::table('course_review')->where('id', $value->id)->update(['audio_duration' => $fileDuration]);
            $this->info('updated--cid--'.$id);
        }
        $this->info('update complete!!');
    }

    function updateContent($id = null)
    {
        // 更新所有content
        $path = storage_path() . "/WeekData/temp";
        if (!file_exists($path)) {
            $this->error('cant find path :' . "$path");
            die();//mkdir($path);
        }
        if ($id == null) {
            $this->update_audio($path);
        } else {
            if (!empty($id) && !is_numeric($id)) {
                $this->error('Invalid cid');
                die();
            }
            $handle = opendir($path);
            if ($handle) {
                while (($file = readdir($handle)) !== false) {
                    if ($file != '.' && $file != '..') {
                        $cur_path = $path . DIRECTORY_SEPARATOR . $file;
                        if (is_dir($cur_path) || substr(strrchr($file, '.'), 1) !== 'json') {
                            continue;
                        } else {
                            $cid = intval(basename($cur_path, ".json"));
                            echo "=====$cid";
                            if ($cid != $id) {
                                continue;
                            }
                            $value = DB::table('course_review')->where('cid', $cid)->select('id')->first();
                            if (empty($value)) {
                                continue;
                            }
                            $text = "";
                            $section = json_decode(file_get_contents($cur_path), true);
                            $num = 1;
                            foreach ($section as $v) {
                                $start = floor(intval($v['bg']) / 1000);
                                $text .= "$num" . ". " . $v['onebest'] . "  [开始时间: 第 " . $start . " 秒]" . "\n";
                                $num += 1;
                            }
                            $res = DB::table('course_review')->where('id', $value->id)->update(['content' => $text]);
                            //var_dump("数据库返回".$res);
                        }
                    }
                }
                closedir($handle);
            }
        }

    }

    function httpcopy($url, $file = "", $timeout = 60)
    {
        $file = empty($file) ? pathinfo($url, PATHINFO_BASENAME) : $file;
        $dir = pathinfo($file, PATHINFO_DIRNAME);
        !is_dir($dir) && @mkdir($dir, 0755, true);
        $url = str_replace(" ", "%20", $url);

        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $temp = curl_exec($ch);
            $file = storage_path() . "/WeekData/temp/temp.mp3";
            if (@file_put_contents($file, $temp) && !curl_error($ch)) {
                return $file;
            } else {
                return false;
            }
        } else {
            $opts = array(
                "http" => array(
                    "method" => "GET",
                    "header" => "",
                    "timeout" => $timeout)
            );
            $context = stream_context_create($opts);
            if (@copy($url, $file, $context)) {
                //$http_response_header
                return $file;
            } else {
                return false;
            }
        }
    }

    function update_audio($dir)
    {
        $handle = opendir($dir);
        if ($handle) {
            while (($file = readdir($handle)) !== false) {
                if ($file != '.' && $file != '..') {
                    $cur_path = $dir . DIRECTORY_SEPARATOR . $file;
                    if (is_dir($cur_path) || substr(strrchr($file, '.'), 1) !== 'json') {
                        continue;
                    } else {
                        $cid = intval(basename($cur_path, ".json"));
                        echo "=====$cid";

                        $value = DB::table('course_review')->where('cid', $cid)->select('id')->first();
                        if (empty($value)) {
                            continue;
                        }
                        var_dump($value);
                        $text = "";
                        $section = json_decode(file_get_contents($cur_path), true);
                        $num = 1;
                        foreach ($section as $v) {
                            $start = floor(intval($v['bg']) / 1000);
                            $text .= "$num" . ". " . $v['onebest'] . "  [开始时间: 第 " . $start . " 秒]" . "\n";
                            $num += 1;
                        }
                        $res = DB::table('course_review')->where('id', $value->id)->update(['content' => $text]);
                        //var_dump("数据库返回".$res);
                    }
                }
            }
            closedir($handle);
        }
    }


}
