<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/8/15
 * Time: 11:10
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model {
    protected $table = 'brand';

    const brand_arr = array(
        0 => '无主',
//        1 => 'club',
//        2 => 'DHA',
//        3 => 'KOL',
        1 => 'S-26',
//        5 => 'S-26妈妈',
//        6 => '铂臻',
//        7 => '干货',
//        8 => '金装妈妈',
//        9 => '玛特纳',
        2 => '启赋',
//        11 => '启赋+启韵',
//        12 => '启韵',
//        13 => '特配',
//        14 => '育儿24',
//        15 => '君和堂',
//        16 => '育学园',
//        17 => '巴斯德疫苗'
    );
}