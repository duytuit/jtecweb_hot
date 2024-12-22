<?php

namespace App\Console\Commands;

use App\Helpers\RedisHelper;
use App\Models\Accessory;
use App\Models\EmployeeProductionPlan;
use App\Models\LogImport;
use App\Models\ProductionPlan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class SyncProductionPlanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:production_plan';

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
        $checkAsyncKTTM = RedisHelper::getKey('checkAsyncKTTM');
        if($checkAsyncKTTM == 1){
            RedisHelper::setKey('checkAsyncKTTM',false);
            try {
                if (!file_exists('//192.168.207.6/JtecData/SHARE/Le Tham/Kiểm tra - Đồ Gá/Kiểm tra - Đồ Gá/QL đồ gá kiểm tra thông mạch.xlsx')) {
                    LogImport::create([
                        'type' => 1,
                        'status' => 0,
                        'data' => 'đồng bộ kiểm tra thông mạch',
                        'messages' => "Không tìm thấy file."
                    ]);
                    return false;
                }
                set_time_limit(0);
                $details = (new FastExcel)->sheet(1)->withoutHeaders()->import('//192.168.207.6/JtecData/SHARE/Le Tham/Kiểm tra - Đồ Gá/Kiểm tra - Đồ Gá/QL đồ gá kiểm tra thông mạch.xlsx');
                if (count($details) == 0) {
                    LogImport::create([
                        'type' => 1,
                        'status' => 0,
                        'data' => 'đồng bộ kiểm tra thông mạch',
                        'messages' => "Không tìm thấy dữ liệu"
                    ]);
                }
                foreach ($details as $key => $value) {
                    if( $key >4){
                        if(@$value[1]){
                            $productionPlan = ProductionPlan::where(function($query) use ($value){
                                     if($value[2] == 0){
                                        $query->where('code',trim($value[1]));
                                     }else{
                                        $query->where('code',trim($value[1]).'-'.sprintf("%02s", $value[2]));
                                     }
                            })->whereNotNull('description')->whereNull('plan_lot_no')->first();
                            if($productionPlan){
                                $kttm = (array)json_decode($productionPlan->kttm);
                                if($kttm){
                                    $kttm[1]=@$value[5];//thùng mẫu
                                    $kttm[2]=@$value[7];//thời gian
                                    $kttm[3]=@$value[8];//ghi chú
                                    $kttm[4]=@$value[9];//hiện trạng
                                    $productionPlan->kttm = json_encode($kttm);
                                    $productionPlan->save();
                                }else{
                                    $kttm=[];
                                    $kttm[1]=@$value[5];//thùng mẫu
                                    $kttm[2]=@$value[7];//thời gian
                                    $kttm[3]=@$value[8];//ghi chú
                                    $kttm[4]=@$value[9];//hiện trạng
                                    $productionPlan->kttm = json_encode($kttm);
                                    $productionPlan->save();
                                }
                            }
                        }
                    }
                }
                RedisHelper::setKey('update_AsyncKTTM',json_encode(['time'=>Carbon::now()->format('H:i:s d-m-Y'),'status'=>1]));
            }catch (\Exception $e) {
                RedisHelper::setKey('update_AsyncKTTM',json_encode(['time'=>Carbon::now()->format('H:i:s d-m-Y'),'status'=>2]));
                LogImport::create([
                    'type' => 1,
                    'status' => 0,
                    'data' => 'đồng bộ kiểm tra thông mạch',
                    'messages' => $e->getLine() . '||' . $e->getTraceAsString()
                ]);
            }
        }
        $checkAsyncKTNQ = RedisHelper::getKey('checkAsyncKTNQ');
        if($checkAsyncKTNQ == 1){
            RedisHelper::setKey('checkAsyncKTNQ',false);
            try {
                if (!file_exists('//192.168.207.6/JtecData/KIEM TRA/2. FILE  HÀNG MẪU  検査/__KTNQ.xlsx')) {
                    LogImport::create([
                        'type' => 1,
                        'status' => 0,
                        'data' => 'đồng bộ kiểm tra ngoại quan',
                        'messages' => "Không tìm thấy file."
                    ]);
                    return false;
                }
                set_time_limit(0);
                $details = (new FastExcel)->sheet(1)->withoutHeaders()->import('//192.168.207.6/JtecData/KIEM TRA/2. FILE  HÀNG MẪU  検査/__KTNQ.xlsx');
                if (count($details) == 0) {
                    LogImport::create([
                        'type' => 1,
                        'status' => 0,
                        'data' => 'đồng bộ kiểm tra ngoại quan',
                        'messages' => "Không tìm thấy dữ liệu"
                    ]);
                    return false;
                }
                foreach ($details as $key => $value) {
                    if($key > 4){
                        if(@$value[1]){
                            $productionPlan = ProductionPlan::where('code',trim($value[1]))->whereNotNull('description')->whereNull('plan_lot_no')->first();
                            if($productionPlan){
                                $ktnq = (array)json_decode($productionPlan->ktnq);
                                if($ktnq){
                                    $ktnq[1]=@$ktnq[1];//bản vẽ
                                    $ktnq[2]=@$value[5];//thùng mẫu
                                    $ktnq[3]=@$value[7];//ghi chú trạng thái
                                    $ktnq[4]=@$value[11];//ghi chú
                                    $productionPlan->ktnq = json_encode($ktnq);
                                    $productionPlan->save();
                                }else{
                                    $ktnq=[];
                                    $ktnq[1]=null;//bản vẽ
                                    $ktnq[2]=@$value[5];//thùng mẫu
                                    $ktnq[3]=@$value[7];//ghi chú trạng thái
                                    $ktnq[4]=@$value[11];//ghi chú
                                    $productionPlan->ktnq = json_encode($ktnq);
                                    $productionPlan->save();
                                }

                            }
                        }
                    }
                }
                RedisHelper::setKey('update_AsyncKTNQ',json_encode(['time'=>Carbon::now()->format('H:i:s d-m-Y'),'status'=>1]));
            } catch (\Exception $e) {
                RedisHelper::setKey('update_AsyncKTNQ',json_encode(['time'=>Carbon::now()->format('H:i:s d-m-Y'),'status'=>2]));
                LogImport::create([
                    'type' => 1,
                    'status' => 0,
                    'data' => 'đồng bộ kiểm tra ngoại quan',
                    'messages' => $e->getLine() . '||' . $e->getTraceAsString()
                ]);
            }
        }
        $checkcheckAsyncProductionPlan = RedisHelper::getKey('checkAsyncProductionPlan');
        $checkAsyncProductionPlanHistory = RedisHelper::getKey('checkAsyncProductionPlanHistory');

        if($checkcheckAsyncProductionPlan == 1 || $checkAsyncProductionPlanHistory){
            //RedisHelper::setKey('checkAsyncProductionPlan',false);
            if($checkAsyncProductionPlanHistory){
                RedisHelper::delKey('checkAsyncProductionPlanHistory');
                $history_plan = RedisHelper::getKey($checkAsyncProductionPlanHistory);
                if($history_plan){
                    $details = json_decode(unserialize($history_plan));
                }
            }
            try {
                // EmployeeProductionPlan::whereNotNull('description')->whereNull('plan_lot_no')->forceDelete();
                RedisHelper::delKey('checkAsyncProductionPlan');
                EmployeeProductionPlan::whereNotNull('description')->whereNull('plan_lot_no')->update([
                    'flag_a'=>0
                ]);
                set_time_limit(0);
                $files = glob("//192.168.207.6/JtecData/QUAN LY SAN XUAT/VUI/コマツインドの出荷日程*.xlsx");
                foreach ($files as $__key => $__value) {
                    if($checkcheckAsyncProductionPlan == 1){
                        if (!file_exists($__value)) {
                            LogImport::create([
                                'type' => 1,
                                'status' => 0,
                                'data' => 'ProductionPlan',
                                'messages' => "Không tìm thấy file."
                            ]);
                            return false;
                        }
                        $details = (new FastExcel)->sheet(2)->withoutHeaders()->import($__value);
                        RedisHelper::setAndExpire('ke_hoach_san_xuat_'.Carbon::now()->format('Y_m_d_H_i_s'),json_encode($details->toArray()),60*60*48);
                    }
                    if (count($details) == 0) {
                        LogImport::create([
                            'type' => 1,
                            'status' => 0,
                            'data' => 'ProductionPlan',
                            'messages' => "Không tìm thấy dữ liệu"
                        ]);
                        return false;
                    }
                    foreach ($details as $key => $value) {
                        if($key == 0){
                            $Weekday=[
                              1=>$value[9],
                              2=>$value[10],
                              3=>$value[11],
                              4=>$value[12],
                              5=>$value[13],
                              6=>$value[14],
                              7=>$value[15],
                              8=>$value[16],
                              9=>$value[17],
                              10=>$value[18],
                            ];
                            Cache::put('productionPlanHeaderWeekday', $Weekday);
                        }
                        if ($key > 2) {
                            // LogImport::create([
                            //     'type' => 1,
                            //     'status' => 0,
                            //     'data' => json_encode($value),
                            //     'messages' => "test"
                            // ]);
                            if(@$value[1]){
                                $employeeProductionPlan  = EmployeeProductionPlan::where('code',trim($value[1]))->whereNotNull('description')->whereNull('plan_lot_no')->first();
                                try {
                                    $description = [];
                                        $description['code'] = @$value[1];
                                        $description['lot_no'] = @$value[2];
                                        $description['hangdangdo_cam'] = @$value[3];
                                        $description['hangdangdo_lrap'] = @$value[4];
                                        $description['hangdangdo_buredo'] = @$value[5];
                                        $description['hangdangdo_kttm'] = @$value[6];
                                        $description['hangdangdo_ktnq'] = @$value[7];
                                        $description['ton_kho'] = @$value[8];
                                        $description['thu2_le'] = @$value[9];
                                        $description['thu3_air'] = @$value[10];
                                        $description['thu4_le'] = @$value[11];
                                        $description['thu5_le'] = @$value[12];
                                        $description['thu5_air'] = @$value[13];
                                        $description['thu6_air'] = @$value[14];
                                        $description['thu6_sea_osaka'] = @$value[15];
                                        $description['thu6_sea_tokyo'] = @$value[16];
                                        $description['thu6_sea_tokyo_1'] = @$value[17];
                                        $description['thu6_sea_tokyo_2'] = @$value[18];
                                        $description['mau'] = @$value[19];
                                        $description['hangxuatchualam_dap'] = @$value[20];
                                        $description['hangxuatchualam_cam'] = @$value[21];
                                        $description['hangxuatchualam_lrap'] = @$value[22];
                                        $description['hangxuatchualam_buredo'] = @$value[23];
                                        $description['hangxuatchualam_kttm'] = @$value[24];
                                        $description['hangxuatchualam_ktnq'] = @$value[25];
                                        $description['don_gia'] = @$value[26];
                                        $description['so_luong'] = @$value[27];
                                        $description['gia_cong'] = @$value[29];
                                        $description['soluongdaycatchualam_dap'] = @$value[36];
                                        $description['soluongdaycatchualam_cam'] = @$value[37];
                                        $description['soluongdaycatchualam_lrap'] = @$value[38];
                                        $description['soluongdaycatchualam_buredo'] = @$value[39];
                                        $description['soluongdaycatchualam_kttm'] = @$value[40];
                                        $description['soluongdaycatchualam_ktnq'] = @$value[41];
                                        $description['tonglichxuat_dongia'] = @$value[42];
                                        $description['tonglichxuat_soluong'] = @$value[43];
                                        $description['tonglichxuat_soluongxuat'] = @$value[44];
                                        $description['soluonghangxuat_dap'] = @$value[45];
                                        $description['soluonghangxuat_cam'] = @$value[46];
                                        $description['soluonghangxuat_lrap'] = @$value[47];
                                        $description['soluonghangxuat_buredo'] = @$value[48];
                                        $description['soluonghangxuat_kttm'] = @$value[49];
                                        $description['soluonghangxuat_ktnq'] = @$value[50];
                                        $description['soluonghangxuatdaycat_dap'] = @$value[51];
                                        $description['soluonghangxuatdaycat_cam'] = @$value[52];
                                        $description['soluonghangxuatdaycat_lrap'] = @$value[53];
                                        $description['soluonghangxuatdaycat_buredo'] = @$value[54];
                                        $description['soluonghangxuatdaycat_kttm'] = @$value[55];
                                        $description['soluonghangxuatdaycat_ktnq'] = @$value[56];
                                        $description['loai_hang'] = @$value[57];
                                    if ($employeeProductionPlan) {
                                        $employeeProductionPlan->description = json_encode($description);
                                        $employeeProductionPlan->flag_a = 1;
                                        $employeeProductionPlan->save();
                                    } else {
                                        EmployeeProductionPlan::create([
                                            'code' => trim($value[1]),
                                            'lot_no' => trim($value[2]),
                                            'description' => json_encode($description)
                                        ]);
                                    }
                                } catch (\Exception $e) {
                                    LogImport::create([
                                        'type' => 1,
                                        'status' => 0,
                                        'data' => json_encode($value),
                                        'messages' => $e->getLine() . '||' . $e->getTraceAsString()
                                    ]);
                                    continue;
                                }
                            }

                        }
                    }
                }
                if($checkcheckAsyncProductionPlan == 1){
                   RedisHelper::setKey('update_EmployeeProductionPlan',json_encode(['time'=>Carbon::now()->format('H:i:s d-m-Y'),'status'=>1]));
                }
                if($checkAsyncProductionPlanHistory){
                    $time = explode('ke_hoach_san_xuat_',$checkAsyncProductionPlanHistory)[1];
                    $_time = explode('_',$time);
                    RedisHelper::setKey('update_EmployeeProductionPlan',json_encode(['time'=>$_time[3].":".$_time[4].":".$_time[5]." ".$_time[2]."-".$_time[1]."-".$_time[0],'status'=>1]));
                }
            } catch (\Exception $e) {
                // print_r( $e->getLine() . '||' . $e->getTraceAsString());
                RedisHelper::setKey('update_EmployeeProductionPlan',json_encode(['time'=>Carbon::now()->format('H:i:s d-m-Y'),'status'=>2]));
                LogImport::create([
                    'type' => 1,
                    'status' => 0,
                    'data' =>'ProductionPlan',
                    'messages' => $e->getLine() . '||' . $e->getTraceAsString()
                ]);
            }
        }
        $checkAsyncProductionPlanDetail = RedisHelper::getKey('checkAsyncProductionPlanDetail');
        if($checkAsyncProductionPlanDetail == 1){
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
                if (count($file_lot_no) == 0) {
                    LogImport::create([
                        'type' => 1,
                        'status' => 0,
                        'data' => 'ProductionPlanDetail',
                        'messages' => "Không tìm thấy dữ liệu"
                    ]);
                    return false;
                }
                ProductionPlan::whereNotNull('plan_lot_no')->whereNull('description')->delete();
                foreach ($file_lot_no as $key => $value) {
                    if ($key > 5) {
                      if(@$value[1]){
                        $productionPlan = ProductionPlan::where(['code'=>trim($value[1]),'order'=>$value[0]])->whereNotNull('plan_lot_no')->whereNull('description')->first();
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
                        $plan_lot_no['note2'] = @$value[20];
                        $plan_lot_no['hangkhong_noixoan'] = $value[21];
                        $plan_lot_no['buredo'] = $value[22];
                        $plan_lot_no['macon'] = $value[23];
                        $plan_lot_no['seq_cat_dap'] = $value[24];
                        $plan_lot_no['seq_cam'] = $value[25];
                        $plan_lot_no['seq_lrap'] = $value[26];
                        $plan_lot_no['makhachhang'] = $value[27];
                        if($productionPlan){
                            $productionPlan->plan_lot_no = json_encode($plan_lot_no);
                            $productionPlan->save();
                        }else{
                            ProductionPlan::create([
                                'order' => $value[0],
                                'code' => trim($value[1]),
                                'plan_lot_no' => json_encode($plan_lot_no)
                            ]);
                        }
                        if(!isset($value[1]) || $value[1] ==null){
                            break;
                        }
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
                    'data' => 'ProductionPlanDetail',
                    'messages' => $e->getLine() . '||' . $e->getTraceAsString()
                ]);
            }
        }
        $checkAsyncInvoice = RedisHelper::getKey('asyncInvoice');
        if($checkAsyncInvoice == 1){
            RedisHelper::setKey('asyncInvoice',false);
            try {
                Accessory::orderBy('id')->update([
                    'invoice_data'=>null
                ]);
                $____details = RedisHelper::queuePop(['asyncInvoiceData']);
                $____details = json_decode($____details);
                if (count($____details) == 0) {
                    LogImport::create([
                        'type' => 1,
                        'status' => 0,
                        'data' => 'asyncInvoiceData',
                        'messages' => 'không có dữ liệu'
                    ]);
                    return true;
                }
                $time = Carbon::now()->format('h:i:s d-m-Y');
                foreach ($____details as $key => $value) {
                    $invoiceData=[];
                    $accessory_dept=[];
                    if($key > 16){
                        $__code = trim($value[3]);
                        $accessory = Accessory::where('code',$__code)->first();
                        if($accessory){
                            Cache::store('redis')->forget('findAccessoryByCode_'.$accessory->code);
                            if($accessory->invoice_data){
                                $invoiceData = json_decode($accessory->invoice_data);
                                $invoiceData[]=[
                                    'pl_no'=>trim($value[1]),
                                    'goods'=>trim($value[2]),
                                    'item'=>trim($value[3]),
                                    'origin'=>trim($value[4]),
                                    'unit'=>trim($value[5]),
                                    'qty'=>trim($value[6]),
                                    'net_weight'=>trim($value[7]),
                                    'total_net_weight'=>trim($value[8]),
                                    'check'=>'',
                                    'checked_at'=>'',
                                    'checked_by'=>'',
                                    'created_at'=>$time,
                                ];

                                $accessory->invoice_data =json_encode($invoiceData);
                                $accessory->save();
                            }else{
                                $invoiceData[]=[
                                    'pl_no'=>trim($value[1]),
                                    'goods'=>trim($value[2]),
                                    'item'=>trim($value[3]),
                                    'origin'=>trim($value[4]),
                                    'unit'=>trim($value[5]),
                                    'qty'=>trim($value[6]),
                                    'net_weight'=>trim($value[7]),
                                    'total_net_weight'=>trim($value[8]),
                                    'check'=>'',
                                    'checked_at'=>'',
                                    'checked_by'=>'',
                                    'created_at'=>$time,
                                ];
                                $accessory->invoice_data =json_encode($invoiceData);
                                $accessory->save();
                            }
                        }else{
                            $invoiceData[]=[
                                'pl_no'=>trim($value[1]),
                                'goods'=>trim($value[2]),
                                'item'=>trim($value[3]),
                                'origin'=>trim($value[4]),
                                'unit'=>trim($value[5]),
                                'qty'=>trim($value[6]),
                                'net_weight'=>trim($value[7]),
                                'total_net_weight'=>trim($value[8]),
                                'check'=>'',
                                'checked_at'=>'',
                                'checked_by'=>'',
                                'created_at'=>$time,
                            ];
                            $accessory_dept[]=[
                                'location_c' => '1523',// mã công đoạn
                                'location_k' => '7',
                                'inventory' =>0
                            ];
                            $accessory_dept[]=[
                                'location_c' => '0111',// mã công đoạn
                                'location_k' => '7',
                                'inventory' =>0
                            ];
                            $accessory_dept[]=[
                                'location_c' => '1510',// mã công đoạn
                                'location_k' => '7',
                                'inventory' =>0
                            ];
                            $accessory_dept[]=[
                                'location_c' => '9997',// mã công đoạn
                                'location_k' => '7',
                                'inventory' =>0
                            ];
                            $accessory_dept[]=[
                                'location_c' => '1533',// mã công đoạn
                                'location_k' => '7',
                                'inventory' =>0
                            ];
                            $accessory_dept[]=[
                                'location_c' => '1521',// mã công đoạn
                                'location_k' => '7',
                                'inventory' =>0
                            ];
                            $accessory_dept[]=[
                                'location_c' => '9998',// mã công đoạn
                                'location_k' => '7',
                                'inventory' =>0
                            ];
                            $accessory_dept[]=[
                                'location_c' => '9995',// mã công đoạn
                                'location_k' => '7',
                                'inventory' =>0
                            ];
                            $accessory_dept[]=[
                                'location_c' => '1610',// mã công đoạn
                                'location_k' => '7',
                                'inventory' =>0
                            ];
                            Accessory::create([
                                'code'=> $__code,
                                'location_k'=> '7',
                                'location_c'=> '0111',
                                'location'=> '',
                                'material_norms'=> 0,
                                'accessory_dept'=> json_encode($accessory_dept),
                                'status'=>1,
                                'invoice_data'=>json_encode($invoiceData)
                            ]);
                        }
                    }
                }
                RedisHelper::setKey('update_asyncInvoice',json_encode(['time'=>Carbon::now()->format('H:i:s d-m-Y'),'status'=>1]));
            }catch(\Exception $e){
                LogImport::create([
                    'type' => 1,
                    'status' => 0,
                    'data' => 'asyncInvoiceData',
                    'messages' => $e->getLine() . '||' . $e->getTraceAsString()
                ]);
            }
        }
        return true;
    }
}
