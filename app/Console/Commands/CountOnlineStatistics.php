<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InclassLog;
use App\Models\OnlineStatistics;
use App\Models\Course;

/**
 * 统计课程在线人数
 */
class CountOnlineStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'count:onlinestatistics {cid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计课程在线人数';

    /**
     * 统计课程在线人数
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
        $cid = $this->argument('cid');
        $course = Course::where('id', $cid)->first();
        $start_time = strtotime($course['start_day'] . $course['start_time'])  - 1800;
        $end_time = strtotime($course['start_day'] . $course['end_time']) + 1800;
        $logs = InclassLog::select('login_at', 'logout_at')
            ->where('cid', $cid)->where('login_at', '>=', $start_time)->where('logout_at', '<=', $end_time)
            ->get()->toArray();
        OnlineStatistics::where('cid', $cid)->delete();

        for ($i = $start_time; $i <= $end_time; $i++) {
            $count = 0;
            foreach ($logs as $log) {
                if ($i >= $log['login_at'] && $i < $log['logout_at']) {
                    $count++;
                }
            }
            $data = [
                'cid' => $cid,
                'count' => $count,
                'time' => $i,
            ];
            OnlineStatistics::insert($data);
        }
    }
}