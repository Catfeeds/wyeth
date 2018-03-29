<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CIService\CIDataRecommend;
use GuzzleHttp\Client;

class UploadItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'items:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload items';

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
        $ciDataRecom = new CIDataRecommend();
        $response = $ciDataRecom->updateTag();
        echo('ret:' . $response['ret'] . "\n");
        $ciDataRecom->uploadItems();
    }
}
