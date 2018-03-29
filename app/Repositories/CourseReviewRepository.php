<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/27
 * Time: 下午6:17
 */

namespace App\Repositories;

use App\CIData\Cidata;
use App\Models\Course;
use App\Models\CourseReview;
use App\Models\CourseTag;
use App\Models\UserCourse;
use App\Models\CourseStat;
use App\Models\Tag;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\UserEventService;
use App\Jobs\SendTemplateMessageBySignUp;

use App\Models\AppConfig;
use App\Services\CourseService;
use App\Services\CounterService;

use Session;
use Log;

class CourseReviewRepository
{
    public function updateSection($cid, $section){
        CourseReview::where('id', $cid)->update([
            'section' => $section
        ]);
        return '';
    }
}