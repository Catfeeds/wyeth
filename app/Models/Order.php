<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/9/13
 * Time: 15:51
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $table = 'order';

    //支付状态
    //待支付
    const STATE_WAIT = 'wait';

    //支付成功
    const STATE_SUCCESS = 'success';

    //支付失败
    const STATE_FAIL = 'fail';
}