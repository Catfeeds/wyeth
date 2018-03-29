<?php

namespace App\Console\Commands;

use App\Models\Course;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCourseStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:course:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动更新课程报名状态,到了开课时间就从报名中到已结束';

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
        $start_day = date('Y-m-d');
        $start_time = date('H:i:s');

        $sql = "SELECT id, title FROM course WHERE status = 1 AND display_status = 1 AND (start_day < '{$start_day}' OR  (start_day = '{$start_day}' and start_time < '{$start_time}'))";
        $course = DB::select($sql);
        $cids = [];
        foreach ($course as $item){
            $cids[] = $item->id;
        }
        if ($cids){
            $course = DB::table('course')
                ->whereIn('id', $cids)
                ->update([
                    'status' => 3,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            $this->info('更新课程状态, 报名中->已结束'.json_encode($cids));
            \Log::info('定时更新课程状态', $cids);
        }
        $this->info('没有要更新的课程');

    }
}
