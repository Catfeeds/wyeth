<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\Job;
use getID3;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;


class getAudioDuration extends Job implements SelfHandling,ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $url;
    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url,$id)
    {
        $this->url=$url;
        $this->id=$id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '200M');
        $id = $this->id;
        $src = $this->url;
        \Log::info("更新音频 id=".$id);
        $getID3 = new getID3();
        $file = $this->httpcopy($src);
        $data ="开始 课程id ".$id."===".date('Y-d-m H:i:s')."===".$file."\t\n";
	    $infoPath = storage_path()."/WeekData/audioChangeInfo.txt";
        $buffer = fopen($infoPath,'a+');
        fputs($buffer,$data);
        fclose($buffer);
        \Log::info($file);
	    if($file !== false){
            $fileInfo=$getID3->analyze($file);
            $fileDuration = floor($fileInfo['playtime_seconds']);
            $res = DB::table('course_review')->where('id',$id)->update(['audio_duration' => $fileDuration]);
            \Log::info("更新结果： ".$res);
            $data ="课程ID ".$id."====".date('Y-d-m H:i:s')."==更新成功"."\t\n";
        }
        if($file === false){
            $data ="课程ID ".$id."====".date('Y-d-m H:i:s')."==更新失败"."\t\n";
        }
        $buffer = fopen($infoPath,'a+');
        fputs($buffer,$data);
        fclose($buffer);


    } 

   function httpcopy($url, $file="", $timeout=60) {

       if(function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $temp = curl_exec($ch);
            $file = storage_path()."/WeekData/temp/temp.mp3";
            if(file_put_contents($file, $temp) && !curl_error($ch)) {
                return $file;
            } else {
                return false;
            }
        } else {
            $opts = array(
                "http"=>array(
                    "method"=>"GET",
                    "header"=>"",
                    "timeout"=>$timeout)
            );
            $context = stream_context_create($opts);
            if(@copy($url, $file, $context)) {
                //$http_response_header
                return $file;
            } else {
                return false;
            }
        }
    }

}
