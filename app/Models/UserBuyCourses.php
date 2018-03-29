<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/9/13
 * Time: 15:18
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBuyCourses extends Model {
    protected $table = 'user_buy_courses';

    const STATUS_CAN_READ = 1;      // 可阅读
    const STATUS_BUY_CAT = 2;       // 购买套课
    const STATUS_BUY_COURSE = 3;    // 购买单课

    const TYPE_CAT = 1; // 套课
    const TYPE_COURSE = 2; // 单课

    const TRADE_PAIED = 1;
    const TRADE_UNPAY = 0;
}