<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Questionnaire;
use Excel;

class QuestionnaireExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:questionnaire {cid}';

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
        $cid = $this->argument('cid');
        $qs = Questionnaire::where('cid', $cid)->get()->toArray();
        $data = [];
        foreach ($qs as $q) {
            $answer = $q['answers'];
            $as = json_decode($answer);
            $q5 = '';
            foreach ($as->q5 as $v) {
                $q5 .= $v . ';';
            }
            if (property_exists($as, 'q7')) {
                $q7 = $as->q7;
            } else {
                $q7 = '';
            }
            echo $q['openid'] . ',' . $as->q1 . ',' .  $as->q2 . ',' . $as->q3 . ',' . $as->q4 . ',' . $q5 . ','.$as->q6.','.$q7.','.$as->q8.','.$as->q9.','.$as->q10_0.','.$as->q10_1.','.$as->q10_2."\r\n";

        }

    }
}
