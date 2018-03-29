<?php

namespace App\Console\Commands;

use App\CIData\Utils;
use App\CIData\EventSaver;
use Illuminate\Console\Command;

class UploadCIData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:cidata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '上传数据到cidata';

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
        Utils::log("cron begin");

        $events = EventSaver::fetchEvents();

        Utils::log("event num:" . count($events));

        $batch = array();
        foreach ($events as $event) {
            $batch[] = $event;
            if (count($batch) >= 100) {
                Utils::sendToServer($batch);
                $batch = array();
            }
        }

        if (count($batch) > 0) {
            Utils::sendToServer($batch);
            $batch = array();
        }

        Utils::log("cron exit");
    }
}
