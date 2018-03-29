<?php
namespace App\Contracts;

// 敏感词
interface CensorContract
{
    // 生成词典
    public function make($input, $output);

    // 搜索
    public function search($str);

    // 替换
    public function replace($str, $to);
}
