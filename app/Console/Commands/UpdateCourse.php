<?php

namespace App\Console\Commands;

use App\Models\Course;
use Illuminate\Console\Command;


class UpdateCourse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:update:stage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update course stage';

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
     * status 100 备孕 200-210 孕中 300-306 宝宝
     * @return mixed
     */
    public function handle()
    {
        header("Content-Type:text/html;charset=utf-8");
        $courseAll = Course::all();
        $course = new Course;
        foreach ($courseAll as $key => $value) {
            $stage_from = '';
            $stage_to = '';
            $stage = $value->stage;
            $stageArr = explode('-', $stage);
            //from
            if (trim($stageArr[0]) == '备孕') {
                $stage_from = 100;
            }
            if( strpos($stageArr[0],"孕中") !== false){
                $fromMonth  = $this->findNum($stageArr[0]);
                if(strlen(trim($fromMonth)) > 1){
                    $stageFromMonth = '2'.$fromMonth;
                }else{
                    $stageFromMonth = '20'.$fromMonth;
                }
                $stage_from = (int)$stageFromMonth;
            }
            if( strpos($stageArr[0],"宝宝") !== false){
                $fromYear  = $this->findNum($stageArr[0]);
                $stageFromYear= '30'.$fromYear;
                $stage_from = (int)$stageFromYear;
            }
            //to
            if (trim($stageArr[1]) == '备孕') {
                $stage_to = 100;
            }
            if( strpos($stageArr[1],"孕中") !== false){
                $toMonth = $this->findNum($stageArr[1]);
                if(strlen(trim($toMonth)) > 1){
                    $stageToMonth = '2'.$toMonth;
                }else{
                    $stageToMonth = '20'.$toMonth;
                }
                $stage_to = (int)$stageToMonth;
            }
            if( strpos($stageArr[1],"宝宝") !== false){
                $toYear  = $this->findNum($stageArr[1]);
                $stageToYear= '30'.$toYear;
                $stage_to = (int)$stageToYear;
            }
            //save
            $course = Course::find($value->id);
            $course->stage_from = $stage_from;
            $course->stage_to = $stage_to;
            $course->save();
        }
    }
    public function findNum($str=''){
        $str=trim($str);
        if(empty($str)){
            return '';
        }
        $result='';
        for($i=0;$i<strlen($str);$i++){
            if(is_numeric($str[$i])){
                $result.=$str[$i];
            }
        }
        return $result;
    }
}
