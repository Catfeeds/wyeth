<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    static function getCategoryList()
    {
        $data = [];
        $lists = Category::all();
        if ($lists) {
            //一级菜单
            $temp = [];
            foreach ($lists as $l) {
                $pid = $l->pid;
                $temp[$pid][] = [
                    'caid' => $l->id,
                    'name' => $l->name,
                ];
            }

            //二级菜单
            $data = isset($temp[0]) ? $temp[0] : array();
            if ($data) {
                foreach ($data as $k => $d) {
                    $caid = $d['caid'];
                    if (isset($temp[$caid])) {
                        $data[$k]['child'] = $temp[$caid];

                        //三级菜单
                        foreach ($data[$k]['child'] as $key => $c) {
                            $caid = $c['caid'];
                            if (isset($temp[$caid])) {
                                $data[$k]['child'][$key]['child'] = $temp[$caid];
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

}
