<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
/**
 * separate stage to six column
 * ALTER TABLE `course` ADD `status` TINYINT(1) UNSIGNED NOT NULL
 * <option value="备孕">备孕</option>
 * <option value="孕中">孕中</option>
 * <option value="宝宝">宝宝</option>

 * ALTER TABLE `course` ADD `month` TINYINT(2) UNSIGNED NOT NULL
 * <option value="0个月">0个月</option>
 * <option value="1个月">1个月</option>
 * <option value="2个月">2个月</option>
 * <option value="3个月">3个月</option>
 * <option value="4个月">4个月</option>
 * <option value="5个月">5个月</option>
 * <option value="6个月">6个月</option>
 * <option value="7个月">7个月</option>
 * <option value="8个月">8个月</option>
 * <option value="9个月">9个月</option>
 * <option value="10个月">10个月</option>

 * ALTER TABLE `age` ADD `month` TINYINT(2) UNSIGNED NOT NULL
 * <option value="0岁">0岁</option>
 * <option value="1岁">1岁</option>
 * <option value="2岁">2岁</option>
 * <option value="3岁">3岁</option>
 * <option value="4岁">4岁</option>
 * <option value="5岁">5岁</option>
 * <option value="6岁">6岁</optio>
 要执行的sql
 update course set sstatus = (CASE  when LOCATE('备孕',SUBSTRING_INDEX(stage, '-', 1)) >0 then 1
                                   when LOCATE('孕中',SUBSTRING_INDEX(stage, '-', 1)) >0 then 2
                                   when LOCATE('宝宝',SUBSTRING_INDEX(stage, '-', 1)) >0 then 3
                                   else 0 end)

update course set smonth = (CASE  when LOCATE('0个月',SUBSTRING_INDEX(stage, '-', 1)) >0 then 0
                                  when LOCATE('1个月',SUBSTRING_INDEX(stage, '-', 1)) >0 then 1
                                  when LOCATE('2个月',SUBSTRING_INDEX(stage, '-', 1)) >0 then 2
                                  when LOCATE('3个月',SUBSTRING_INDEX(stage, '-', 1)) >0 then 3
                                  when LOCATE('4个月',SUBSTRING_INDEX(stage, '-', 1)) >0 then 4
                                  when LOCATE('5个月',SUBSTRING_INDEX(stage, '-', 1)) >0 then 5
                                  when LOCATE('6个月',SUBSTRING_INDEX(stage, '-', 1)) >0 then 6
                                  when LOCATE('7个月',SUBSTRING_INDEX(stage, '-', 1)) >0 then 7
                                  when LOCATE('8个月',SUBSTRING_INDEX(stage, '-', 1)) >0 then 8
                                  when LOCATE('9个月',SUBSTRING_INDEX(stage, '-', 1)) >0 then 9
                                  when LOCATE('0个月',SUBSTRING_INDEX(stage, '-', 1)) >0 then 10
                                  when LOCATE('10个月',SUBSTRING_INDEX(stage, '-', 1)) >0 then 1
                                  else 0 end)

update course set sage = (CASE    when LOCATE('0岁',SUBSTRING_INDEX(stage, '-', 1)) >0 then 0
                                  when LOCATE('1岁',SUBSTRING_INDEX(stage, '-', 1)) >0 then 1
                                  when LOCATE('2岁',SUBSTRING_INDEX(stage, '-', 1)) >0 then 2
                                  when LOCATE('3岁',SUBSTRING_INDEX(stage, '-', 1)) >0 then 3
                                  when LOCATE('4岁',SUBSTRING_INDEX(stage, '-', 1)) >0 then 4
                                  when LOCATE('5岁',SUBSTRING_INDEX(stage, '-', 1)) >0 then 5
                                  when LOCATE('6岁',SUBSTRING_INDEX(stage, '-', 1)) >0 then 6
                                  else 0 end)


update course set estatus = (CASE  when LOCATE('备孕',SUBSTRING_INDEX(stage, '-', -1)) >0 then 1
                                   when LOCATE('孕中',SUBSTRING_INDEX(stage, '-', -1)) >0 then 2
                                   when LOCATE('宝宝',SUBSTRING_INDEX(stage, '-', -1)) >0 then 3
                                   else 0 end)

update course set emonth = (CASE  when LOCATE('0个月',SUBSTRING_INDEX(stage, '-', -1)) >0 then 0
                                  when LOCATE('1个月',SUBSTRING_INDEX(stage, '-', -1)) >0 then 1
                                  when LOCATE('2个月',SUBSTRING_INDEX(stage, '-', -1)) >0 then 2
                                  when LOCATE('3个月',SUBSTRING_INDEX(stage, '-', -1)) >0 then 3
                                  when LOCATE('4个月',SUBSTRING_INDEX(stage, '-', -1)) >0 then 4
                                  when LOCATE('5个月',SUBSTRING_INDEX(stage, '-', -1)) >0 then 5
                                  when LOCATE('6个月',SUBSTRING_INDEX(stage, '-', -1)) >0 then 6
                                  when LOCATE('7个月',SUBSTRING_INDEX(stage, '-', -1)) >0 then 7
                                  when LOCATE('8个月',SUBSTRING_INDEX(stage, '-', -1)) >0 then 8
                                  when LOCATE('9个月',SUBSTRING_INDEX(stage, '-', -1)) >0 then 9
                                  when LOCATE('0个月',SUBSTRING_INDEX(stage, '-', -1)) >0 then 10
                                  when LOCATE('10个月',SUBSTRING_INDEX(stage, '-', -1)) >0 then 1
                                  else 0 end)

update course set eage = (CASE    when LOCATE('0岁',SUBSTRING_INDEX(stage, '-', -1)) >0 then 0
                                  when LOCATE('1岁',SUBSTRING_INDEX(stage, '-', -1)) >0 then 1
                                  when LOCATE('2岁',SUBSTRING_INDEX(stage, '-', -1)) >0 then 2
                                  when LOCATE('3岁',SUBSTRING_INDEX(stage, '-', -1)) >0 then 3
                                  when LOCATE('4岁',SUBSTRING_INDEX(stage, '-', -1)) >0 then 4
                                  when LOCATE('5岁',SUBSTRING_INDEX(stage, '-', -1)) >0 then 5
                                  when LOCATE('6岁',SUBSTRING_INDEX(stage, '-', -1)) >0 then 6
                                  else 0 end)

 * hot 报名人数 ++  上课人数 (报名人数表为空,所以没有写)
update course,(select count(*) as hot,cid from user_course group by cid)  as uc
set course.hot = uc.hot where course.id = uc.cid

mysql -u root -p509abfe0
use wyeth_course_dev
source /usr/local/cncounter/mysql_dump/cncounter_dump.sql.20140414_1333;
source /data/www/wyethcourse_dev1/crontab/course.sql
 */
class SeparateStageToSixCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create six column
        Schema::table('course', function ($table) {
            $table->tinyInteger('sstage');
            $table->tinyInteger('smonth');
            $table->tinyInteger('sage');

            $table->tinyInteger('estage');
            $table->tinyInteger('emonth');
            $table->tinyInteger('eage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('course', function ($table) {
            $table->dropColumn('sstage');
            $table->dropColumn('smonth');
            $table->dropColumn('sage');

            $table->dropColumn('estage');
            $table->dropColumn('emonth');
            $table->dropColumn('eage');
        });
    }
}
