<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\CourseTag;
use App\Models\DisplayTags;
use App\Models\Tag;
use Illuminate\Console\Command;

class UpdateDisplayTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'displaytag:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $display_tags = DisplayTags::get()->toArray();
        echo(count($display_tags) . "\n");
        $i = 0;
        foreach ($display_tags as $display_tag){
            $tag = new Tag();
            $tag->name = $display_tag['name'];
            $tag->type = 3;
            $tag->img = $display_tag['img'];
            $tag->save();
            $i++;
            echo ($i . "\n");
        }

        echo("\n");

        $courses = Course::where('display_status', 1)->get()->toArray();
        echo(count($courses) . "\n");
        $i = 0;
        foreach ($courses as $course){
            $course_display_tag_ids = explode(',', $course['display_tags']);
            foreach ($course_display_tag_ids as $course_display_tag_id){
                $display_tag = DisplayTags::where('id', $course_display_tag_id)->first();
                $tag = Tag::where('name', $display_tag->name)->where('type', 3)->first();
                if(!$tag){
                    continue;
                }
                $course_tag = new CourseTag();
                $course_tag->cid = $course['id'];
                $course_tag->tid = $tag->id;
                $course_tag->type = 3;
                $course_tag->save();
            }
            $i++;
            echo($i . "\n");
        }


    }
}
