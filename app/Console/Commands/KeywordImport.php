<?php

namespace App\Console\Commands;

use App\Models\Materiel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class KeywordImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keys:import';

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
        $materiel = Materiel::where('key_word', '!=', '')->get();

        foreach ($materiel as $m) {
            $arr_key = explode('，', $m->key_word);
            foreach ($arr_key as $index => $k) {
                $ret = DB::table('keyword')->where('name', $k)->first();
                if (!$ret) {
                    $data = array(
                        'name' => $k,
                        'created_at' => date('Y-m-d H;i:s')
                    );
                    $id = DB::table('keyword')->insertGetId($data);
                    $arr_key[$index] = $id;
                } else {
                    $arr_key[$index] = $ret->id;
                }
            }
            $m->key_word =  implode(',', $arr_key);
            echo $m->key_word . '<br>';
            $m->save();
        }
    }
}
