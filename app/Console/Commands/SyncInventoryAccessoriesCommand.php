<?php

namespace App\Console\Commands;

use App\Helpers\RedisHelper;
use App\Models\Accessory;
use App\Models\LogImport;
use App\Models\Required;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SyncInventoryAccessoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:inventory_accessory';

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

        do {
            $get_detail = null;
            try {
                $details = RedisHelper::queuePop(['inventory_accessory']);
                if ($details == null) {
                    break;
                }
                $get_detail = $details;
                $details = json_decode($details);
                $accessory = $details->accessory;
                echo $accessory->code."\n";
                $accessory_dept = json_decode($accessory->accessory_dept);
                if($accessory_dept){
                    $_accessory_dept = $accessory_dept;
                    $unit = null;
                    $DFW_Z30F = DB::connection('oracle')->table('DFW_Z30F')->select('単位')
                    ->where('品目C', 'like', $accessory->code . '%')->first();
                    if ($DFW_Z30F) {
                        $unit = trim($DFW_Z30F->単位);
                    }
                    foreach ($accessory_dept as $key_1 => $value_1) {
                        $sql = "SELECT 現在在庫数 FROM V_DFW_Z11_040QF_0 WHERE 品目C ='$accessory->code' AND 場所C = '$value_1->location_c'";
                        $getList = DB::connection('oracle')->select($sql);
                        if(count($getList)>0){
                            $_accessory_dept[$key_1]->inventory = $getList[0]->現在在庫数;
                        }
                    }
                    $_accessory = Accessory::find($accessory->id);
                    if ($_accessory) {
                        $_accessory->accessory_dept = json_encode($_accessory_dept);
                        $_accessory->unit = $unit;
                        $_accessory->save();
                    }
                }
                $ids = Required::where(['from_type'=>0,'type'=>0])->whereRaw('JSON_VALUE(content_form, "$.location") = 0')->pluck('id');
                $__requireds =  Required::where(['from_type'=>0,'type'=>0])->whereNotIn('id',$ids)->get();
                foreach ($__requireds as $key => $value) {
                    $content_form=[];
                    $sql = "SELECT * FROM TAD_Z60M WHERE 品目C = '$accessory->code'";
                    $getList = DB::connection('oracle')->select($sql);

                    $location = array_filter($getList, fn ($element) => $element->場所c == '0111');
                    if(count($location)>0){
                        $location = current($location);
                        $content_form['location'] = trim($location->棚番);
                    }
                    $location_order = array_filter($getList, fn ($element) => $element->場所c == '1510');
                    if(count($location_order)>0){
                        $location_order = current($location_order);
                        $content_form['location_order'] = trim($location_order->棚番);
                    }

                    $content_form['code'] = $accessory->code;
                    $content_form['quantity'] = $value->quantity;
                    $content_form['size'] = $accessory->material_norms;
                    $content_form['unit_price'] = $accessory->unit;
                    $content_form['location_c'] = $accessory->location_c;
                    $content_form['usage_status'] = $value->usage_status;
                    DB::table('requireds')->where('id',$value->id)->update([
                        'content_form'=>json_encode($content_form)
                    ]);
                }
                Cache::store('redis')->forget('findAccessoryByCode_'.$accessory->code);
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

        return true;
    }
}
