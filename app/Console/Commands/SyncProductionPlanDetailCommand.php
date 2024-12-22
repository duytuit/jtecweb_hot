<?php

namespace App\Console\Commands;

use App\Helpers\RedisHelper;
use App\Models\LogImport;
use App\Models\ProductionPlan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;

class SyncProductionPlanDetailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:production_plan_detail';

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
        $details = null;
        $checkAsync = RedisHelper::getKey('checkAsyncProductionPlanDetail');
        if($checkAsync == 1){
            RedisHelper::setKey('checkAsyncProductionPlanDetail',false);
            try {
                $curent_month = Carbon::now()->format('m');
                $file_path = '//192.168.207.6/JtecData/QUAN LY SAN XUAT/HANG/A1  KE HOACH SAN XUAT/A Năm 2024/Thang '.(int)$curent_month.'/Ke hoach san xuat thang  '.(int)$curent_month.'.xlsx';
                if (!file_exists($file_path)) {
                    LogImport::create([
                        'type' => 1,
                        'status' => 0,
                        'data' => 'ProductionPlanDetail',
                        'messages' => "Không tìm thấy file."
                    ]);
                    return false;
                }
                set_time_limit(0);
                $file_lot_no = (new FastExcel)->sheet(1)->withoutHeaders()->import($file_path);
                foreach ($file_lot_no as $key => $value) {
                    if ($key > 5) {
                    $productionPlan = ProductionPlan::where('lot_no',$value[1])->first();
                    if($productionPlan){
                        $plan_lot_no = json_decode($productionPlan->plan_lot_no);
                        if($plan_lot_no){
                            $plan_lot_no->a = $value[2];
                            $plan_lot_no->lot_no_denno_cat_dap = $value[3];
                            $plan_lot_no->lot_no_denno_cam = $value[4];
                            $plan_lot_no->lot_no_denno_lrap = $value[5];
                            $plan_lot_no->soluongsanxuat_pcs = $value[6];
                            $plan_lot_no->ma_edp = $value[7];
                            $plan_lot_no->ngaysanxuat = $value[8];
                            $plan_lot_no->ngayhoanthanh = $value[9];
                            $plan_lot_no->noisanxuat = $value[10];
                            $plan_lot_no->note1 = $value[11];
                            $plan_lot_no->toa = $value[12];
                            $plan_lot_no->khachhangle = $value[13];
                            $plan_lot_no->sldc = $value[14];
                            $plan_lot_no->suatay_edp = $value[15];
                            $plan_lot_no->suatay_xk = $value[16];
                            $plan_lot_no->tongsoluongdaycat = $value[17];
                            $plan_lot_no->soluongxuathang_cat_dap = $value[18];
                            $plan_lot_no->soluongxuathang_cam = $value[19];
                            $plan_lot_no->note2 = $value[20];
                            $plan_lot_no->hangkhong_noixoan = $value[21];
                            $plan_lot_no->buredo = $value[22];
                            $plan_lot_no->macon = $value[23];
                            $plan_lot_no->seq_cat_dap = $value[24];
                            $plan_lot_no->seq_cam = $value[25];
                            $plan_lot_no->seq_lrap = $value[26];
                            $plan_lot_no->makhachhang = $value[27];
                            $productionPlan->plan_lot_no = json_encode($plan_lot_no);
                            $productionPlan->save();
                        }else{
                            $plan_lot_no = [];
                            $plan_lot_no['a'] = $value[2];
                            $plan_lot_no['lot_no_denno_cat_dap'] = $value[3];
                            $plan_lot_no['lot_no_denno_cam'] = $value[4];
                            $plan_lot_no['lot_no_denno_lrap'] = $value[5];
                            $plan_lot_no['soluongsanxuat_pcs'] = $value[6];
                            $plan_lot_no['ma_edp'] = $value[7];
                            $plan_lot_no['ngaysanxuat'] = $value[8];
                            $plan_lot_no['ngayhoanthanh'] = $value[9];
                            $plan_lot_no['noisanxuat'] = $value[10];
                            $plan_lot_no['note1'] = $value[11];
                            $plan_lot_no['toa'] = $value[12];
                            $plan_lot_no['khachhangle'] = $value[13];
                            $plan_lot_no['sldc'] = $value[14];
                            $plan_lot_no['suatay_edp'] = $value[15];
                            $plan_lot_no['suatay_xk'] = $value[16];
                            $plan_lot_no['tongsoluongdaycat'] = $value[17];
                            $plan_lot_no['soluongxuathang_cat_dap'] = $value[18];
                            $plan_lot_no['soluongxuathang_cam'] = $value[19];
                            $plan_lot_no['note2'] = $value[20];
                            $plan_lot_no['hangkhong_noixoan'] = $value[21];
                            $plan_lot_no['buredo'] = $value[22];
                            $plan_lot_no['macon'] = $value[23];
                            $plan_lot_no['seq_cat_dap'] = $value[24];
                            $plan_lot_no['seq_cam'] = $value[25];
                            $plan_lot_no['seq_lrap'] = $value[26];
                            $plan_lot_no['makhachhang'] = $value[27];
                            $productionPlan->plan_lot_no = json_encode($plan_lot_no);
                            $productionPlan->save();
                        }

                    }else{
                        $plan_lot_no = [];
                        $plan_lot_no['a'] = $value[2];
                        $plan_lot_no['lot_no_denno_cat_dap'] = $value[3];
                        $plan_lot_no['lot_no_denno_cam'] = $value[4];
                        $plan_lot_no['lot_no_denno_lrap'] = $value[5];
                        $plan_lot_no['soluongsanxuat_pcs'] = $value[6];
                        $plan_lot_no['ma_edp'] = $value[7];
                        $plan_lot_no['ngaysanxuat'] = $value[8];
                        $plan_lot_no['ngayhoanthanh'] = $value[9];
                        $plan_lot_no['noisanxuat'] = $value[10];
                        $plan_lot_no['note1'] = $value[11];
                        $plan_lot_no['toa'] = $value[12];
                        $plan_lot_no['khachhangle'] = $value[13];
                        $plan_lot_no['sldc'] = $value[14];
                        $plan_lot_no['suatay_edp'] = $value[15];
                        $plan_lot_no['suatay_xk'] = $value[16];
                        $plan_lot_no['tongsoluongdaycat'] = $value[17];
                        $plan_lot_no['soluongxuathang_cat_dap'] = $value[18];
                        $plan_lot_no['soluongxuathang_cam'] = $value[19];
                        $plan_lot_no['note2'] = $value[20];
                        $plan_lot_no['hangkhong_noixoan'] = $value[21];
                        $plan_lot_no['buredo'] = $value[22];
                        $plan_lot_no['macon'] = $value[23];
                        $plan_lot_no['seq_cat_dap'] = $value[24];
                        $plan_lot_no['seq_cam'] = $value[25];
                        $plan_lot_no['seq_lrap'] = $value[26];
                        $plan_lot_no['makhachhang'] = $value[27];
                        ProductionPlan::create([
                            'code' => $value[1],
                            'plan_lot_no' => json_encode($plan_lot_no)
                        ]);
                    }
                    if(!isset($value[1]) || $value[1] ==null){
                        break;
                    }
                    }
                }
                RedisHelper::setKey('update_EmployeeProductionPlanDetail',json_encode(['time'=>Carbon::now()->format('H:i:s d-m-Y'),'status'=>1]));
            } catch (\Exception $e) {
                // print_r( $e->getLine() . '||' . $e->getTraceAsString());
                RedisHelper::setKey('update_EmployeeProductionPlanDetail',json_encode(['time'=>Carbon::now()->format('H:i:s d-m-Y'),'status'=>2]));
                LogImport::create([
                    'type' => 1,
                    'status' => 0,
                    'data' => json_encode($details),
                    'messages' => $e->getLine() . '||' . $e->getTraceAsString()
                ]);
            }
        }
        return true;
    }
}
