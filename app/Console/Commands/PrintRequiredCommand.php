<?php

namespace App\Console\Commands;

use App\Helpers\RedisHelper;
use App\Models\Accessory;
use App\Models\LogImport;
use App\Models\Required;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PrintRequiredCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:print_required';

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
     * @return int
     */
    public function handle()
    {
        $time_start = microtime(true);
        $startPrintEdp = RedisHelper::getKey('startPrintEdp');
        if($startPrintEdp == 1){
            RedisHelper::delKey('startPrintEdp');
            do {
                try {
                    $details = RedisHelper::queuePop(['print_edp']);
                    if ($details == null) {
                        break;
                    }
                    $result = Required::printEdp($details);
                    // LogImport::create([
                    //     'type' => 1,
                    //     'status' => 0,
                    //     'data' => 'print_edp',
                    //     'messages' => json_encode($details)
                    // ]);
                    $time_end = microtime(true);
                    $time = $time_end - $time_start;
                } catch (\Exception $e) {
                    LogImport::create([
                        'type' => 1,
                        'status' => 0,
                        'data' => "print_edp",
                        'messages' => $e->getLine().'||'.$e->getMessage()
                    ]);
                }
            } while ($details != null || $time < 55);
        }else{
            do {
                $get_detail = null;
                try {
                    $details = RedisHelper::queuePop(['print_required']);
                    if ($details == null) {
                        break;
                    }
                    $get_detail = $details;
                    $result = Required::printPdf($details);
                    $time_end = microtime(true);
                    $time = $time_end - $time_start;
                } catch (\Exception $e) {
                    LogImport::create([
                        'type' => 1,
                        'status' => 0,
                        'data' => $get_detail,
                        'messages' => $e->getLine().'||'.$e->getMessage()
                    ]);
                }
            } while ($details != null || $time < 55);
        }

        return true;
    }
}
