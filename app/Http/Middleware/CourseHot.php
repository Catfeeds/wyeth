<?php namespace App\Http\Middleware;

use Closure;
use App\Models\Course;

class CourseHot
{
    public function handle($request, Closure $next)
    {
        $cid = $request->input('cid');
        $hot = Course::where('id', $cid)->pluck('hot');

        $model = new Course;
        $model->hot = intval($hot)+1;

        return $next($request);
    }

}