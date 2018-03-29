<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/7/21
 * Time: 15:04
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model {
    protected $table = 'teacher';

    protected $fillable = ['name', 'avatar', 'hospital', 'position', 'desc'];

    // 替换图片域名
    public function getAvatarAttribute ($value) {
        return replaceUploadURL($value);
    }
}