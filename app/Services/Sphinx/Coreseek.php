<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/28
 * Time: 上午10:20
 */

#header("Content-type: text/html; charset=utf-8");
require __DIR__ ."/sphinxapi.php";
class Coreseek
{
    public static function getIns()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    public function init()
    {
        $this->sc = new SphinxClient();
        # param1为本机, param2为coreseek监听的端口
        $this->sc->setServer("127.0.0.1", 9312);

        # true表示以数组返回（默认是hash）
        $this->sc->setArrayResult(true);

        # 连接超时时间(比如sphinx服务器挂了等异常情况)单位为s
        $this->sc->setConnectTimeout(3);

        # 设置查询模式
        $this->sc->setMatchMode(SPH_MATCH_ANY);

        # 设置查询分页(设置最大1000, 每页50)
        $this->sc->setLimits(0, 50, 1000);

        # 设置排序模式
//        $this->sc->setSortMode(SPH_SORT_ATTR_DESC, 'hot');
    }

    # 关键词 && 索引
    public function query($keyword, $index, $c, $s)
    {
        if (null === $index) {
            $index = '*';
        }elseif ($index == 'idx_course'){
            $this->sc->setSortMode(SPH_SORT_ATTR_DESC, 'hot');
            $this->sc->setSortMode('SPH_SORT_ATTR_EXPR','@weight');
        }
        $this->sc->SetLimits(($c - 1) * $s, $s);
        $res = $this->sc->query($keyword, $index);
        #var_dump($res);
        if ($res === false) {
            return "Query failed:" . $this->sc->GetLastError() . ".\n";
        } else {
            if ($this->sc->GetLastWarning()){
                return "Warning: " . $this->sc->GetLastWarning() . "";
            }
        }

        if ($res["total_found"] <= ($c - 1) * $s) {
            return "Warning: no data";
        }
        # 区分多表查询与单表查询
        if ($index === '*') {
            # 多表查询
            #foreach($res["matches"] as $key => $value) {
            #$new_id = "id_" . substr($index, 4);

            #$res["matches"][$key][$new_id]= $res["matches"][$key]['id'];
            #}
        }
        else {
            # 单表查询
            count($res["matches"]);
            foreach($res["matches"] as $key => $value) {
                #echo $key . $res["matches"][$key]["id"];
                #echo "<br>";
                $new_id = "id_" . substr($index, 4);

                $res["matches"][$key][$new_id]= $res["matches"][$key]['id'];
            }
        }
        return $res;
    }

    public function insert() {

    }

    public function update() {

    }
}