<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/31
 * Time: 下午4:08
 */

//清除所有cookie

var_dump($_COOKIE);
foreach($_COOKIE as $key=>$value){
    setcookie($key, '', time()-3600, '/');
}