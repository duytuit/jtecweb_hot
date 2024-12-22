<?php

namespace App\Console\Commands;

use App\Helpers\RedisHelper;
use App\Models\Accessory;
use App\Models\LogImport;
use App\Models\Required;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncInventoryAccessoriesV2Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:inventory_accessory_v2';

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
                    foreach ($accessory_dept as $key_1 => $value_1) {
                        $DFW_Z20F = DB::connection('oracle')->table('DFW_Z20F')->select('当月在庫数')
                            ->where('場所C', 'like', $value_1->location_c . '%')
                            ->where('品目K', 'like', $value_1->location_k . '%')
                            ->where('品目C', 'like', $accessory->code . '%')->orderBy('品目C')->orderBy('年月度', 'desc')->first();
                        $inventory = (int)$_accessory_dept[$key_1]->inventory;
                        $unit = $accessory->unit;
                        if ($DFW_Z20F) {
                            $inventory =  (int)trim($DFW_Z20F->当月在庫数);
                            $DFW_Z30F = DB::connection('oracle')->table('DFW_Z30F')->select('単位', '品目c', '受払seq2', '数量')
                                ->where('場所C', 'like', $value_1->location_c . '%')
                                ->where('品目K', 'like', $value_1->location_k . '%')
                                ->where('在庫受払日', 'like', Carbon::now()->format('Y/m') . '%')
                                ->where('新規登録日', 'like', Carbon::now()->format('Y/m') . '%')
                                ->where('品目C', 'like', $accessory->code . '%')->orderBy('品目C')->orderBy('新規登録日', 'desc')->get();

                            if ($DFW_Z30F->count() > 0) {
                                foreach ($DFW_Z30F as $key => $value) {
                                    $unit = trim($value->単位);
                                    if (trim($value->品目c) == $accessory->code) {
                                        if (trim($value->受払seq2) == '1') { // Xuất
                                            $inventory = $inventory - (int)trim($value->数量);
                                        }
                                        if (trim($value->受払seq2) == '0') { // Nhập
                                            $inventory = $inventory + (int)trim($value->数量);
                                        }
                                    }
                                }
                            }
                            if ($inventory != $_accessory_dept[$key_1]->inventory) {
                                echo  $inventory . "\n";
                                $_accessory = Accessory::find($accessory->id);
                                if ($_accessory) {
                                    $_accessory_dept[$key_1]->inventory = $inventory;
                                    $_accessory->accessory_dept = json_encode($_accessory_dept);
                                    $_accessory->inventory = $value_1->location_c == '0111' ? $inventory : $_accessory->inventory;
                                    $_accessory->unit = $unit;
                                    $_accessory->save();

                                }
                            }
                            $content_form = json_decode($details->content_form);
                            $content_form->inventory_accessory = $inventory;
                            DB::table('requireds')->where('id', $details->id)->update([
                                'content_form' => json_encode($content_form)
                            ]);
                        }
                    }
                }
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
