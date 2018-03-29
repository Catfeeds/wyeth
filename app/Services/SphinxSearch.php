<?php
namespace App\Services;
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/28
 * Time: 上午10:47
 */

require_once __DIR__ . '/Sphinx/Coreseek.php';

class SphinxSearch{
    public function getSearch($keyword, $page, $page_size){
        \Coreseek::getIns()->init();
        $array = \Coreseek::getIns()->query($keyword, '*', $page, $page_size);
        return $array;
    }

    public function getCourseSearch($keyword, $page_size){
        \Coreseek::getIns()->init();
        $array = \Coreseek::getIns()->query($keyword, 'idx_course', 1, $page_size);
        return $array;
    }

    public function getTagSearch($keyword, $page_size){
        \Coreseek::getIns()->init();
        $array = \Coreseek::getIns()->query($keyword, 'idx_tags', 1, $page_size);
        return $array;
    }

    public function getQuestionSearch($keyword, $page_size){
        \Coreseek::getIns()->init();
        $array = \Coreseek::getIns()->query($keyword, 'idx_tag_question', 1, $page_size);
        return $array;
    }

    public function getSearchIndex($keyword, $index, $page, $page_size){
        \Coreseek::getIns()->init();
        $array = \Coreseek::getIns()->query($keyword, $index, $page, $page_size);
        return $array;
    }
}