<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 短网址
 */
class ShortUrl extends Model
{
    // Url 转为短地址
    public function encode($url) {
        // echo $crcTen;
        $crcTen = sprintf('%u', crc32($url));
        $shortUrl = new ShortUrl();
        $shortUrl->hash = bc_base_convert($crcTen, 10, 62);
        $shortUrl->url = $url;
        $shortUrl->save();
        return url('/url/' . $shortUrl->hash);
    }

    // 短地址转为url
    public function decode($hash) {
        if (!$hash) {
            return false;
        }
        $shortUrl = ShortUrl::where('hash', $hash)->first();
        if (!$shortUrl) {
            return false;
        }
        return $shortUrl->url;
    }
}
