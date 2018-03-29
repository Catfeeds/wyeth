<?php
namespace App\Services;

use Orzcc\Opensearch\Sdk\CloudsearchClient;
use Orzcc\Opensearch\Sdk\CloudsearchSearch;
use Orzcc\Opensearch\Sdk\CloudsearchDoc;

class Search
{
    /**
     * aliyun opensearch配置项
     */
    private $config;

    /**
     * 和API服务进行交互的对象。
     * @var CloudsearchClient
     */
    private $client;


    function __construct()
    {
        $config = config('opensearch');
        $default = $config['default'];
        $this->config = $config['connections'][$default];

        $client = new CloudsearchClient(
            $this->config['client_id'],
            $this->config['client_secret'],
            $this->config['host'],
            'aliyun'
        );
        $this->client = $client;
    }

    public function getClientSearch()
    {
        $client = $this->client;
        $search = new CloudsearchSearch($client);
        $search->addIndex($this->config['app']);

        return $search;
    }

    public function getCLientDoc()
    {
        $client = $this->client;
        return new CloudsearchDoc($this->config['app'], $client);
    }


}