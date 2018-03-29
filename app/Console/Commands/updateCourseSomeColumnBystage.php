<?php
/**
 * 因为原来course的stage同时写了六个参数，要是改流程一时比较急，所以做了这个类用crontab定时把stage的内容拆分成六块存起来。
 *
 */
namespace App\Console\Commands;

use App\Models\Course;
use Illuminate\Console\Command;

class updateCourseSomeColumnBystage extends Command
{

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'course:updateCourseSomeColumnBystage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '_crontab,updateCourseSomeColumnBystage';

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
     * separate course hot to six
     */
    public function handle()
    {
        //取出表course中的stage字段
        $courses = Course::all();
        $updateCourse = new Course;

        foreach ($courses as $course) {
            //通过字段中都含有字符 '-' 的特性，把字符前后段分开
            $pos = strpos($course->stage, '-');
            if ($pos === false) {
                break;
            }

            $prefix = substr($course->stage, 0, $pos);
            $suffix = substr($course->stage, $pos + 2);

            //查看字符前半部分是否有属性中的字样转化成数字
            //sstage
            if (strstr($prefix, '孕中') !== false) {
                $sstage = 1;
            } elseif (strstr($prefix, '宝宝') !== false) {
                $sstage = 2;
            } else {
                //备孕
                $sstage = 0;
            }
            //smonth
            if (strstr($prefix, '1个月') !== false) {
                $smonth = 1;
            } elseif (strstr($prefix, '2个月') !== false) {
                $smonth = 2;
            } elseif (strstr($prefix, '3个月') !== false) {
                $smonth = 3;
            } elseif (strstr($prefix, '4个月') !== false) {
                $smonth = 4;
            } elseif (strstr($prefix, '5个月') !== false) {
                $smonth = 5;
            } elseif (strstr($prefix, '6个月') !== false) {
                $smonth = 6;
            } elseif (strstr($prefix, '7个月') !== false) {
                $smonth = 7;
            } elseif (strstr($prefix, '8个月') !== false) {
                $smonth = 8;
            } elseif (strstr($prefix, '9个月') !== false) {
                $smonth = 9;
            } elseif (strstr($prefix, '10个月') !== false) {
                $smonth = 10;
            } else {
                //0个月
                $smonth = 0;
            }
            //sage
            if (strstr($prefix, '1岁') > 0) {
                $sage = 1;
            } elseif (strstr($prefix, '2岁') > 0) {
                $sage = 2;
            } elseif (strstr($prefix, '3岁') > 0) {
                $sage = 3;
            } elseif (strstr($prefix, '4岁') > 0) {
                $sage = 4;
            } elseif (strstr($prefix, '5岁') > 0) {
                $sage = 5;
            } elseif (strstr($prefix, '6岁') > 0) {
                $sage = 6;
            } else {
                //0岁
                $sage = 0;
            }

            //查看字符前后部分是否有属性中的字样转化成数字
            //estage
            if (strstr($suffix, '孕中') !== false) {
                $estage = 1;
            } elseif (strstr($suffix, '宝宝') !== false) {
                $estage = 2;
            } else {
                //备孕
                $estage = 0;
            }
            //emonth
            if (strstr($suffix, '1个月') !== false) {
                $emonth = 1;
            } elseif (strstr($suffix, '2个月') !== false) {
                $emonth = 2;
            } elseif (strstr($suffix, '3个月') !== false) {
                $emonth = 3;
            } elseif (strstr($suffix, '4个月') !== false) {
                $emonth = 4;
            } elseif (strstr($suffix, '5个月') !== false) {
                $emonth = 5;
            } elseif (strstr($suffix, '6个月') !== false) {
                $emonth = 6;
            } elseif (strstr($suffix, '7个月') !== false) {
                $emonth = 7;
            } elseif (strstr($suffix, '8个月') !== false) {
                $emonth = 8;
            } elseif (strstr($suffix, '9个月') !== false) {
                $emonth = 9;
            } elseif (strstr($suffix, '10个月') !== false) {
                $emonth = 10;
            } else {
                //0个月
                $emonth = 0;
            }
            //eage
            if (strstr($suffix, '1岁') > 0) {
                $eage = 1;
            } elseif (strstr($suffix, '2岁') > 0) {
                $eage = 2;
            } elseif (strstr($suffix, '3岁') > 0) {
                $eage = 3;
            } elseif (strstr($suffix, '4岁') > 0) {
                $eage = 4;
            } elseif (strstr($suffix, '5岁') > 0) {
                $eage = 5;
            } elseif (strstr($suffix, '6岁') > 0) {
                $eage = 6;
            } else {
                //0岁
                $eage = 0;
            }

            //取出的数字以id为准存到新字段中
            Course::where('id', $course->id)
                ->update([
                    'sstage' => $sstage,
                    'smonth' => $smonth,
                    'sage' => $sage,
                    'estage' => $estage,
                    'emonth' => $emonth,
                    'eage' => $eage,
                ]);
            /*
        Course::where('id', $course->id)
        ->update([
        'sstatus' => 9,
        'smonth' => 9,
        'sage' => 9,
        'estatus' => 9,
        'emonth' => 9,
        'eage' => 9
        ]);
         */
        }

        $this->info('update course complete!');
    }
}
