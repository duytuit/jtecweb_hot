<?php

namespace App\Console\Commands;

use App\Models\LogImport;
use App\Utility\RedisUtility;
use Illuminate\Console\Command;

class ImportExamCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:exam';

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
        // foreach ($rows as $row) {
        //     $row = (object)$row;
        //     try {
        //         RedisUtility::queueSet('Redis_Import_City_District_Ward',$row);
        //     }catch (\Exception $e){
        //         dd($e->getTraceAsString());
        //     }
        //  }
        //  dd('thành công.');

        // $todays_deal_products = Cache::rememberForever('todays_deal_products', function () {
        //     return filter_products(Product::where('published', 1)->where('todays_deal', '1'))->limit(12)->get();
        // });

        // $newest_products = Cache::remember('newest_products', 3600, function () {
        //     return filter_products(Product::latest())->limit(12)->get();
        // });
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $time_start = microtime(true);

        do {
            $get_detail = null;
            try {
                $details = RedisUtility::queuePop(['Redis_Import_City_District_Ward']);
                if ($details == null) {
                    break;
                }
                $get_detail = $details;
                $details = json_decode($details);
                $time_end = microtime(true);
                $time = $time_end - $time_start;
            } catch (\Exception $e) {
                LogImport::create([
                    'type' => 1, // import ward district
                    'status' => 0,
                    'data' => $get_detail,
                    'messages' => $e->getLine().'||'.$e->getTraceAsString()
                ]);
            }
        } while ($details != null || $time < 55);

        return true;
    }
}
