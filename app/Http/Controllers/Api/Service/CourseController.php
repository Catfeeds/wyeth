<?php namespace app\http\controllers\api\service;

use App\Repositories\SearchRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course;

/**
 * opensearch搜索服务
 */
class CourseController extends Controller
{
    protected $result = [
        'status' => 1,
        'error_msg' => '',
        'data' => []
    ];

    public function __construct()
    {
        $this->middleware('signature');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    function search(Request $request)
    {
        if (!$request->has('kw')) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = 'params is invalid';
            return response()->json($this->result);
        }

        $key = $request->input('kw');
        $p = $request->input('p', 1);
        $size = $request->input('szie', 10);
        $start = ($p - 1) * $size;

        $search = new SearchRepository();
        $ret = $search->getSearchResult($key, $p);

        $lists = [];

        foreach ($ret['data'] as $course) {
            $link = config('app.url') . '/mobile/end/?cid=' . $course['id'];
            $lists[] = [
                'title' => $course['title'],
                'link' => $link,
                'status' => $course['status']
            ];
        }


        $this->result['data'] = [
            'hasNextPage' => count($lists) >= 10,
            'items' => $lists
        ];


        return response()->json($this->result);
    }
}