<?php

namespace App\Console\Commands;
require app_path() . '/Helpers/simple_html_dom.php';

use App\CIService\CMS;
use App\Models\Materiel;
use App\Services\Qnupload;
use Illuminate\Console\Command;

class UploadCMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CMS:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $materiels = Materiel::where('id', '>', 3929)->get();
        $i = 0;
        $names = [];
        foreach ($materiels as $index=>$materiel) {
            if ($materiel->link) {
                $html = file_get_html($materiel->link);
                $imgs = $html->find('img');
                $title = $materiel->name;
                if(in_array($title, $names)){
                    continue;
                }
                $names[] = $title;
                foreach ($imgs as $count=>$img) {
                    if (array_key_exists('data-src', $img->attr)) {
                        $pic = file_get_contents($img->attr['data-src']);
                        file_put_contents("/tmp/temp_tiny.jpg", $pic);
                        $url = Qnupload::uploadTmp("/tmp/temp_tiny.jpg", 'materiel/image/' . $index, $count);
                        $img->src = $url;
                        @unlink("/tmp/temp_tiny.jpg");
                    }
                }
                $rich_media_content = $html->find('.rich_media_content', 0);
                if(!$rich_media_content){
                    $head_pic = '';
                }else{
                    $head_pic = $rich_media_content->find('img', 0)->attr['src'];  // 获取头图
                }
                $html = $html->save();
                $html = $this->cutStr($html);
                (new CMS())->addArticle('', $title, '惠氏妈妈俱乐部', $html, $head_pic);
                echo($i . "\n");
                $i++;
            }
        }
    }

    public function cutStr($str){
        while(strpos($str, '<script')){
            $s = strstr($str, '<script', true);
            $s1 = strstr($str, '</script>');
            $length = mb_strlen($s1);
            $s2 = mb_substr($s1, 9, $length - 9, 'utf-8');
            $str = $s . $s2;
        }
        return $str;
    }
}
