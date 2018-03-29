<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/8/28
 * Time: 上午10:58
 */

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseTag;
use App\Models\Course;
use Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TagQuestion extends Model
{
    protected $table = 'tag_question';
}