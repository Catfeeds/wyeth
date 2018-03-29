<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;


class TokenController extends Controller
{
    function __construct()
    {
    }

    // 获取 token
    function index()
    {
        if (!Session::get('token')) {
            $token = '';
        } else {
            $token = Session::get('token')->get();
        }
        return response()->json(['token' => $token]);
    }

}
