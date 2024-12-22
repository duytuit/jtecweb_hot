<?php

namespace App\Console\Commands;

use App\Models\Accessory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncAccessoryOracleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:accessory';

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
        try {
            $getList = DB::connection('oracle')->table('TAD_Z60M')->select('品目c','品目k','場所c','棚番')->get();
            foreach ($getList as $key => $value) {
              $_accessory =  Accessory::where(['code'=>trim($value->品目c),'location_c'=>trim($value->場所c),'location_k'=>trim($value->品目k),'location'=>trim($value->棚番)])->first();
              //echo json_encode($_accessory)."\n";
              if(!$_accessory){
                $result= Accessory::create([
                    'code'=> trim($value->品目c),
                    'location_k'=> trim($value->品目k),
                    'location_c'=> trim($value->場所c),
                    'location'=> trim($value->棚番),
                    'status'=>1
                ]);
                echo json_encode($result)."\n";
              }else{
                echo "Đã tồn tại bản ghi \n";
              }
            }
        } catch (\Exception $e) {
            dd($e);
        }
        return true;
    }
}
