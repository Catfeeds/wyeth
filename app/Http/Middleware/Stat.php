<?php namespace App\Http\Middleware;

use Closure;
use App\Models\CourseStat;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class Stat
{
    public function handle($request, Closure $next)
    {
        if ($request->has('cid')) {
            $cid = $request->input('cid');
            $course = Course::where('id', $cid)->first();
            if ($course) {
                $uid = Auth::id();
                CourseStat::firstOrCreate(['cid' => $cid, 'uid' => $uid]);
            }
        }

        return $next($request);
    }

}