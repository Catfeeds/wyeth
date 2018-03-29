<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Services\WxWyeth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Validator;

class QuestionnaireController extends Controller
{
    public function __construct()
    {
        // openid token 指到所有的模板中
        $this->openid = Session::get('openid');

    }

    public function index(Request $request)
    {
        $cid = $request->input('cid', 0);
        $wxWyeth = new WxWyeth();
        $package = $wxWyeth->getSignPackage();
        $static_url = config('course.static_url');
        $data = [
            'openid' => $this->openid,
            'cid' => $cid,
            'static_url' => $static_url,
            'package' => $package,
        ];
        return view('mobile.questionnaire', $data);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q1' => 'required',
            'q2' => 'required',
            'q3' => 'required',
            'q4' => 'required',
            'q5' => 'required',
            'q6' => 'required',
            'q7' => 'required',
            'q8' => 'required',
            'q9_name' => 'required',
            'q9_phone' => 'required',
            'q9_address' => 'required',
        ]);
        if ($validator->fails()) {
            $result = [
                'error' => 1,
            ];
            return response()->json($result);
        }
        $all = $request->all();
        $cid = $all['cid'];
        $openid = $all['openid'];
        $res = Questionnaire::where(['cid' => $cid, 'openid' => $openid])->first();
        if ($res) {
            $result = [
                'error' => 0,
                'message' => '您之前已经提交过了，谢谢您的参与',
            ];
            return response()->json($result);
        }
        unset($all['cid']);
        unset($all['openid']);
        $answers = json_encode($all);
        $model = new Questionnaire();
        $model->cid = $cid;
        $model->openid = $openid;
        $model->answers = $answers;
        $model->save();
        $result = [
            'error' => 0,
            'message' => '谢谢您的参与',
        ];
        return response()->json($result);
    }
}
