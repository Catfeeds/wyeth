<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/21
 * Time: 上午10:24
 */

namespace App\CIService;

class CMS extends BaseCIService{

    public function getFindArticle($page, $limit, $showhtml){
        $img_suffix = '?imageView2/1/w/750/h/300';
        $token = (new Account())->login();
        $url = '/cms/main.php/json/article/getNewestArticle/'.$this->appkey.'/'.$this->channel.'/'.$page.'/'.$limit.'/1/null/'.$showhtml.'/0/' . $token;
        $data = $this->get($url, NULL, false);
        $article_datas = $data['data'];
        $modified_article_data = [];
        foreach ($article_datas as $article_data){
            if(array_key_exists('content', $article_data)){
                $article_data['content'] = '';
            }
            if(array_key_exists('img', $article_data)){
                $article_data['img'] = $article_data['img'] . $img_suffix;
            }
            $modified_article_data[] = $article_data;
        }
        unset($data['data']);
        $data['data'] = $modified_article_data;
        return $data;
    }

    public function getArticleDetail($article_id){
        $token = (new Account())->login();
        $url = '/cms/main.php/json/article/getArticleById/' . $this->appkey . '/0/' . $article_id . '/1/' . $token;
        $data = $this->get($url);
        if(array_key_exists('data', $data) && is_array($data['data']) && (count($data['data']) > 0)){
            $data['data'] = $data['data'][0];
        }else{
            $data['data'] = [];
        }
        $data['data']["link"]="main.php?action=f_article.html&channel=".$this->channel."&appkey=".$this->appkey."&id=".$article_id;
        return $data;
    }

    public function getArticleDetailByIds($article_ids){
        $token = (new Account())->login();
        $url = '/cms/main.php/json/article/getArticleById/' . $this->appkey . '/0/' . $article_ids . '/1/' . $token;
        $data = $this->get($url);
        $article_ids_array = json_decode($article_ids);
        if(array_key_exists('data', $data) && is_array($data['data']) && (count($data['data']) > 0)){
            $i = 0;
            foreach ($data['data'] as $item){
                $item["link"]="main.php?action=f_article.html&channel=".$this->channel."&appkey=".$this->appkey."&id=".$article_ids_array[$i];
                $i++;
            }
        }
        return $data;
    }

    public function addArticle($id, $title, $author, $content, $img, $author_avatar = ''){
        $url = '/cms/main.php/json/article/add/' . $this->appkey . '/' . $this->channel . '/' . $id;
        $showed = date('Y-m-d H:i:s');
        $params = [
            'title' => $title,
            'category' => 1,
            'author' => $author,
            'content' => $content,
            'showed' => $showed,
            'img' => $img,
            'publish' => 1,
            'tag' => '',
            'author_avatar' => $author_avatar,
        ];
        $data = $this->post($url, $params, false);
        $data['data']['data'] = $data['data']['id']['data'];
        return $data;
    }

    public function updateArticle($id, $title, $author, $content, $img, $author_avatar = ''){
        $url = '/cms/main.php/json/article/update/' . $id . '/' . $this->appkey . '/' . $this->channel . '/';
        $showed = date('Y-m-d H:i:s');
        $params = [
            'title' => $title,
            'category' => 1,
            'author' => $author,
            'content' => $content,
            'showed' => $showed,
            'img' => $img,
            'publish' => 1,
            'tag' => '',
            'author_avatar' => $author_avatar,
        ];
        $data = $this->post($url, $params, false);
        return $data;
    }

    public function deleteArticle($id){
        $url = '/cms/main.php/json/article/delete/' . $id . '/' . $this->appkey . '/' . $this->channel;
        $data = $this->get($url, null, false);
        return $data;
    }

    public function like($article_id, $isCancel){
        $token = (new Account())->login();
        $url = '/cms/main.php/json/article/likeOrSaveArticle/' . $this->appkey . '/' . $token . '/' . $article_id . '/1/' . $isCancel;
        $data = $this->get($url, null, false);
        return $data;
    }

    public function comment(){
        $url = '';
        $data = $this->get($url);
        return $data;
    }

    public function save($article_id, $isCancel){
        $token = (new Account())->login();
        $url = '/cms/main.php/json/article/likeOrSaveArticle/' . $this->appkey . '/' . $token . '/' . $article_id . '/2/'. $isCancel;
        $data = $this->get($url, null, false);
        return $data;
    }

    public function getSaveArticles($page, $page_size){
        $token = (new Account())->login();
        $url = '/cms/main.php/json/article/getSaveArticles/' . $this->appkey . '/' . $token . '/' . $page . '/'. $page_size;
        $data = $this->get($url, null, false);
        return $data;
    }

    public function addAuthor($author_name, $author_avatar){
        $url = '/cms/main.php/json/article/addAuthor/' . $this->appkey . '/' . $author_name;
        $params = [
            'author_avatar' => $author_avatar,
        ];
        $data = $this->post($url, $params, false);
        return $data;
    }

    public function updateAuthor($id, $old_name,  $author_name, $new_avatar = ''){
        $url = '/cms/main.php/json/article/updateAuthor/' . $this->appkey . '/' . $id . '/' . $old_name . '/' . $author_name;
        $params = [
            'author_avatar' => $new_avatar,
        ];
        $data = $this->post($url, $params, false);
        return $data;
    }

    public function deleteAuthor($id){
        $url = '/cms/main.php/json/article/deleteAuthor/' . $this->appkey . '/' . $id;
        $data = $this->get($url, null, false);
        return $data;
    }

    public function getAuthorByPage($page = 1, $page_size = 6){
        $url = '/cms/main.php/json/article/getAuthorByPage/' . $this->appkey . '/' . $page . '/'. $page_size;
        $data = $this->get($url, null, false);
        return $data;
    }

    public function getAuthorById($id){
        $url = '/cms/main.php/json/article/getAuthorById/' . $this->appkey . '/' . $id;
        $data = $this->get($url, null, false);
        return $data;
    }

    public function getArticleByAuthor($page = 1, $page_size = 6, $author_name){
        $url = '/cms/main.php/json/article/getArticleByAuthor/' . $this->appkey . '/' . $page . '/'. $page_size . '/'. $author_name;
        $data = $this->get($url, null, false);
        return $data;
    }
}