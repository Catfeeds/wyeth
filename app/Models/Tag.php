<?php

namespace App\Models;


use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseTag;
use App\Models\Course;
use Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = ['name'];

    const TAG_CONTENT = 0; // 内容标签

    const TAG_PREGNANT = 1; // 孕期标签

    const TAG_TEACHER = 2; // 讲师标签

    const TAG_DISPLAY = 3; // 显示标签

    const AGE_STAGE_1 = 358;

    const AGE_STAGE_2 = 360;

    const AGE_STAGE_3 = 361;

    const AGE_STAGE_4 = 362;

    /**
     * 当前所有的tags,中间可能不包括review,每次从cache随机显示，前提是当删除课或者去除签标时，检查是否删除标签
     *
     * @param $number 获取数量
     * @param bool $review 是否是review
     * @param int $length 标题截取长度
     * @param int $notIncludeId 不包含的id
     * @return array
     */
    public static function randChunk($number, $review = false, $length = 4, $notIncludeId = 0)
    {
        $version = 4;
        if (!$review) {
            $tags = Cache::remember('tags:rand:all'.$version, 120, function () {
                $results = DB::select('SELECT course_tags.tid, COUNT(course_tags.tid) as total
                FROM course_tags
                LEFT JOIN course ON course_tags.cid = course.id
                WHERE course.status IN ('.Course::COURSE_STATUS_ORDER.')
                GROUP BY course_tags.tid
                LIMIT 0, 500', []);
                $results = new Collection($results);
                $tids = $results->lists('tid');

                $tags = static::whereIn('id', $tids)->where('type', '=', Tag::TAG_CONTENT)->get();
                return $tags->toArray();
            });
        } else {
            $tags = Cache::remember('tags:rand:review'.$version, 120, function () {
                $results = DB::select('SELECT course_tags.tid, COUNT(course_tags.tid) as total
                    FROM course_tags
                    LEFT JOIN course ON course_tags.cid = course.id
                    WHERE course.status = ?
                    GROUP BY course_tags.tid
                    LIMIT 0, 500', [Course::COURSE_END_STATUS]);
                $results = new Collection($results);
                $tids = $results->lists('tid');
                $tags = static::whereIn('id', $tids)->where('type', '=' ,Tag::TAG_CONTENT)->get();
                return $tags->toArray();
            });
        }

        // 过滤掉 $notIncludeId
        if ($notIncludeId) {
            $tags = array_filter($tags, function ($tag) use ($notIncludeId) {
                return $tag['id'] != $notIncludeId;
            });
        }
        // 过滤掉 长度大于$length的
        $tags = array_filter($tags, function ($tag) use ($notIncludeId, $length) {
            return Str::length($tag['name']) <= $length;
        });
        // 再随机下
        shuffle($tags);
        return array_slice($tags, 0, $number);
    }

    public static function getTagIdByName($name)
    {
        $tagId = static::where('name', 'like', $name . '%')->value('id');
        return $tagId;
    }

    public static function getNewTag () {
//        if (env('APP_ENV') == 'local') {
//            $new_ids = array(339, 46, 340, 343, 15, 344, 198, 345, 70, 25, 212, 194, 189, 108, 109, 346, 347, 348, 349, 71, 350, 351, 352, 353, 354, 21, 20, 359);
//        } else {
//            $new_ids = array(339, 46, 340, 343, 15, 344, 198, 345, 70, 25, 212, 194, 189, 108, 109, 346, 347, 348, 349, 71, 350, 351, 352, 353, 354, 21, 20, 359);
//        }
        $new_ids = Tag::where('type', 0)->lists('id')->toArray();
        return $new_ids;
    }

    // 替换图片域名
    public function getImgAttribute ($value) {
        return replaceUploadURL($value);
    }
}
