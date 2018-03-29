<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\CountOnlineStatistics::class,
        \App\Console\Commands\InitFriendship::class,
        \App\Console\Commands\QuestionnaireExport::class,
        \App\Console\Commands\UpdateUserInfo::class,
        \App\Console\Commands\updateCourseSomeColumnBystage::class,
        \App\Console\Commands\UpdateCourseHotByUserCourse::class,
        \App\Console\Commands\SendQuestionToYjt::class,
        \App\Console\Commands\SendTpls::class,
        \App\Console\Commands\TestQueue::class,
        \App\Console\Commands\UpdateCourse::class,
        \App\Console\Commands\CourseExport::class,
        \App\Console\Commands\CountUserCourse::class,
        \App\Console\Commands\UserUpdateSubscribe::class,
        \App\Console\Commands\CountCourseReview::class,
        \App\Console\Commands\CountToDb::class,
        \App\Console\Commands\CountLivingInit::class,
        \App\Console\Commands\UploadCIData::class,
        \App\Console\Commands\CoursePushCommand::class,
	    \App\Console\Commands\UpdateAudioInfoCommand::class,
        \App\Console\Commands\UploadItems::class,
        \App\Console\Commands\Huiyao::class,
        \App\Console\Commands\TplProjectSendByOpenid::class,
        \App\Console\Commands\UpdateCourseStatus::class,
        \App\Console\Commands\AutoPushCommand::class,
        \App\Console\Commands\CIHdCommand::class,
        \App\Console\Commands\UploadCMS::class,
        \App\Console\Commands\UpdateDisplayTag::class,
        \App\Console\Commands\UpdateMaterialPlatform::class,
        \App\Console\Commands\KeywordImport::class,
        \App\Console\Commands\UpdateUserTag::class,
        \App\Console\Commands\UserDisplayTag::class,
        \App\Console\Commands\DeleteUserTag::class,
        \App\Console\Commands\UserEnrollPushCommand::class,
        \App\Console\Commands\StatisticCommand::class,
        \App\Console\Commands\ExportOpenid::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('user:update:subscribe')->dailyAt('0:30');
        $schedule->command('count:to:db')->everyTenMinutes();

        //每分钟上传一次cidata
        $schedule->command('upload:cidata')->everyMinute();
        //定时推送
        $schedule->command('course:push')->everyMinute();
        //每5分钟更新课程状态
        $schedule->command('update:course:status')->everyFiveMinutes();

        //给慧摇报名的用户定时推送
        $schedule->command('enroll:push')->dailyAt('16:00');

        //每天9点更新昨天线上报名人数
        $schedule->command('huiyao:xxjp update')->dailyAt('09:00');

        //每天凌晨1点增加累计设备
        $schedule->command('statistic:update base')->dailyAt('01:00');
        // 每天23点累加孕育指南数据
        $schedule->command('statistic:update spring_query')->dailyAt('23:00');
        //每小时更新学霸卡和母乳卡的uv
        $schedule->command('statistic:update S26')->hourly();


        //每五分钟检查推送情况（8：30-23：00）
        $schedule->command('auto:push check')->everyFiveMinutes();
        //每小时检查cidata数据是否异常(9:00-23:00)
        $schedule->command('statistic:update cidata_check')->hourly();

        //改成周一三五推送了
        //早上9点推福利
        $schedule->command('auto:push fuli')->weekly()->mondays()->dailyAt('09:00');
        $schedule->command('auto:push fuli')->weekly()->wednesdays()->dailyAt('09:00');
        $schedule->command('auto:push fuli')->weekly()->fridays()->dailyAt('09:00');
        //晚上9点推回顾推荐 type=14
        $schedule->command('auto:push tjhg')->weekly()->mondays()->dailyAt('21:00');
        $schedule->command('auto:push tjhg')->weekly()->wednesdays()->dailyAt('21:00');
        $schedule->command('auto:push tjhg')->weekly()->fridays()->dailyAt('21:00');

        //前60天到前30天上过课，但是近30天没有上过课的用户进行推送 type=15
        //每周二 周五
        $schedule->command('auto:push lose')->weekly()->tuesdays()->at('09:00');
        $schedule->command('auto:push lose')->weekly()->fridays()->at('09:30');

        //30天内注册的新用户并且30天内未上课的人推送 type=16
        //每周三
        $schedule->command('auto:push newUser')->weekly()->wednesdays()->at('19:00');

        //推送900w会员，每天40w type=19
        $schedule->command('auto:push allUser')->dailyAt('10:00');
    }
}
