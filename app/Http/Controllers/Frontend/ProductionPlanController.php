<?php
namespace App\Http\Controllers\Frontend;

use App\Helpers\ArrayHelper;
use App\Helpers\RedisHelper;
use App\Helpers\ReturnPathHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeProductionPlan;
use App\Models\LogImport;
use App\Models\ProductionPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;

class ProductionPlanController extends Controller
{
    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       // Phân trang
       $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
       $data['keyword'] = $request->input('keyword', null);
       $data['advance'] = 0;
       if (count($request->except('keyword')) > 0) {
           // Tìm kiếm nâng cao
           $data['advance'] = 1;
           $data['filter'] = $request->all();
       }
       $productionPlan = ProductionPlan::where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->where('plan_lot_no', 'like','%'.(int)trim(str_replace('00','',$request->keyword)).'%');
            }
           if (isset($request->status) && $request->status != null) {
               $query->where('status', $request->status);
           }
       })->whereNull('description')->whereNotNull('plan_lot_no')->orderBy('id','desc')->paginate($data['per_page']);

       $data['asyncProductionPlanDetail'] = RedisHelper::getKey('update_EmployeeProductionPlanDetail');
       if (isset($request->keyword) && $request->keyword != null) {

           foreach ($productionPlan as $key => $value) {
                $plan_lot_no = json_decode($value->plan_lot_no);
                if($plan_lot_no){
                    $scan = json_decode(@$plan_lot_no->scan);
                    if($scan){
                        $scan[] = Carbon::now()->format('H:i:s d-m-Y');
                        $plan_lot_no->scan = json_encode($scan);
                        $value->plan_lot_no = json_encode($plan_lot_no);
                        $value->save();
                    }else{
                        $scan[] = Carbon::now()->format('H:i:s d-m-Y');
                        $plan_lot_no->scan = json_encode($scan);
                        $value->plan_lot_no = json_encode($plan_lot_no);
                        $value->save();
                    }
                }
           }
        }
       $data['lists'] = $productionPlan;
       return view('frontend.pages.productionPlan.index', $data);
    }
    public function viewMobileProductionPlan(Request $request)
    {
        // Phân trang
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['keyword'] = $request->input('keyword', null);
        $data['advance'] = 0;
        if (count($request->except('keyword')) > 0) {
            // Tìm kiếm nâng cao
            $data['advance'] = 1;
            $data['filter'] = $request->all();
        }
        $data['lists'] = ProductionPlan::where(function ($query) use ($request) {
             if (isset($request->keyword) && $request->keyword != null) {
                 $query->where('code', 'like','%'.$request->keyword.'%');
             }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
        })->whereNotnull('description')->paginate($data['per_page']);
        $data['asyncProductionPlan'] = RedisHelper::getKey('update_EmployeeProductionPlan');
        $data['productionPlanHeaderWeekday']= Cache::get('productionPlanHeaderWeekday');
        return view('frontend.pages.productionPlan.thongmach_ngoaiquan_mobile', $data);
    }
    public function viewProductionPlan(Request $request)
    {
        // Phân trang
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['keyword'] = $request->input('keyword', null);
        $array_code = explode(',', $request->keyword);
        if(count($array_code) == 0){
            return back()->with('error','không tìm thấy dữ liệu');
        }
        $data['filter'] = $request->all();
        $data['filter']['bp'] = $request->get('bp',1);
        for ($i=1; $i <= 14; $i++) {
            $data['filter'][$i] = $request->input('filter_'.$i,1);
        }
        $history_plan = Redis::keys('*ke_hoach_san_xuat_*');
        rsort($history_plan);
        $data['history_plan']=$history_plan;
        $query = ProductionPlan::where(function ($query) use ($request,$array_code) {
             if (isset($request->keyword) && $request->keyword != null) {
                // dd(trim(@$array_code[2]));
                    $query->where('code',trim(@$array_code[2]));
                    //  ->orWhere('plan_lot_no', 'like','%'.(int)trim(str_replace('00','',$request->keyword)).'%');
                    //    ->orWhere('kttm', 'like','%'.$request->keyword.'%')
                    //    ->orWhere('ktnq', 'like','%'.$request->keyword.'%')
                    //    ->orWhere('dap1', 'like','%'.$request->keyword.'%')
                    //    ->orWhere('dap2', 'like','%'.$request->keyword.'%')
                    //    ->orWhere('cam', 'like','%'.$request->keyword.'%')
                    //    ->orWhere('cat', 'like','%'.$request->keyword.'%');
             }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->product_code) && $request->product_code != null) {
                $query->whereRaw('JSON_EXTRACT(description, "$.loai_hang") = ?', [$request->product_code]);
            }
        })->whereNotnull('description')->whereNull('plan_lot_no');

        if (isset($request->filter_1) && $request->filter_1 != null) {
            if($request->filter_1 == 2){
                $query->whereRaw('JSON_EXTRACT(description, "$.thu2_le") > ?', [0]);
                $query->whereRaw('JSON_EXTRACT(description, "$.thu2_le") <> ?', [""]);
            }
        }
        if (isset($request->filter_2) && $request->filter_2 != null) {
            if($request->filter_2 == 2){
                $query->whereRaw('JSON_EXTRACT(description, "$.thu3_air") > ?', [0]);
                $query->whereRaw('JSON_EXTRACT(description, "$.thu3_air") <> ?', [""]);
            }
        }
        if (isset($request->filter_3) && $request->filter_3 != null) {
            if($request->filter_3 == 2){
                $query->whereRaw('JSON_EXTRACT(description, "$.thu4_le") > ?', [0]);
                $query->whereRaw('JSON_EXTRACT(description, "$.thu4_le") <> ?', [""]);
            }
        }
        if (isset($request->filter_4) && $request->filter_4 != null) {
            if($request->filter_4 == 2){
                $query->whereRaw('JSON_EXTRACT(description, "$.thu5_le") > ?', [0]);
                $query->whereRaw('JSON_EXTRACT(description, "$.thu5_le") <> ?', [""]);
            }
        }
        if (isset($request->filter_5) && $request->filter_5 != null) {
            if($request->filter_5 == 2){
                $query->whereRaw('JSON_EXTRACT(description, "$.thu5_air") > ?', [0]);
                $query->whereRaw('JSON_EXTRACT(description, "$.thu5_air") <> ?', [""]);
            }
        }
        if (isset($request->filter_6) && $request->filter_6 != null) {
            if($request->filter_6 == 2){
                $query->whereRaw('JSON_EXTRACT(description, "$.thu6_air") > ?', [0]);
                $query->whereRaw('JSON_EXTRACT(description, "$.thu6_air") <> ?', [""]);
            }
        }
        if (isset($request->filter_7) && $request->filter_7 != null) {
            if($request->filter_7 == 2){
                $query->whereRaw('JSON_EXTRACT(description, "$.thu6_sea_osaka") > ?', [0]);
                $query->whereRaw('JSON_EXTRACT(description, "$.thu6_sea_osaka") <> ?', [""]);
            }
        }
        if (isset($request->filter_8) && $request->filter_8 != null) {
            if($request->filter_8 == 2){
                $query->whereRaw('JSON_EXTRACT(description, "$.thu6_sea_tokyo") > ?', [0]);
                $query->whereRaw('JSON_EXTRACT(description, "$.thu6_sea_tokyo") <> ?', [""]);
            }
        }

        if (isset($request->filter_13) && $request->filter_13 != null) {
            if($request->filter_13 == 2){
                $query->whereRaw('JSON_EXTRACT(description, "$.hangxuatchualam_kttm") < ?', [0]);
                $query->whereRaw('JSON_EXTRACT(description, "$.hangxuatchualam_kttm") <> ?', [""]);
            }
        }


        if (isset($request->filter_14) && $request->filter_14 != null) {
            if($request->filter_14 == 2){
                $query->whereRaw('JSON_EXTRACT(description, "$.hangxuatchualam_ktnq") < ?', [0]);
                $query->whereRaw('JSON_EXTRACT(description, "$.hangxuatchualam_ktnq") <> ?', [""]);
            }
        }

        $data['lists'] = $query->where('flag_a',1)->paginate($data['per_page']);
        $data['asyncProductionPlan'] = RedisHelper::getKey('update_EmployeeProductionPlan');
        $data['update_AsyncKTTM'] = RedisHelper::getKey('update_AsyncKTTM');
        $data['update_AsyncKTNQ'] = RedisHelper::getKey('update_AsyncKTNQ');
        $data['productionPlanHeaderWeekday']= Cache::get('productionPlanHeaderWeekday');
        $data['productionPlanKTNQ']= Cache::get('productionPlanKTNQ');
        $data['productionPlanKTTM']= Cache::get('productionPlanKTTM');
        return view('frontend.pages.productionPlan.thongmach_ngoaiquan', $data);
    }
    public function file_info(Request $request)
    {
        if($request->file_path){
            $data['file_detail'] = ArrayHelper::convertPdfToBase64($request->file_path);
            // $outputPath = storage_path('app/compressed.pdf');
            // $this->compressPdf($request->file_path, $outputPath);
            return view('frontend.pages.productionPlan.file_info', $data);
        }
        if($request->folder_path){
            $data['files'] = scandir($request->folder_path);
            $data['dirPath'] = $request->folder_path;

            return view('frontend.pages.productionPlan.file_info', $data);
        }
    }
    private function compressPdf($inputFile, $outputFile)
    {
        // Ghostscript command for compressing the PDF
        $gsCommand = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$outputFile $inputFile";
        // Execute the command
        exec($gsCommand, $output, $returnVar);
        if ($returnVar !== 0) {
            // Handle error
            throw new \Exception('PDF compression failed');
        }
    }
    public function asyncProductionPlan(){
        RedisHelper::setKey('checkAsyncProductionPlanDetail',true);
        return redirect()->back()->with('success','Đang bắt đầu xử lý đồng bộ.');
    }

    public function asyncViewProductionPlan(Request $request){

        if($request->history_plan){
            RedisHelper::setKey('checkAsyncProductionPlanHistory',$request->history_plan);
            return redirect()->back()->with('success','Đang bắt đầu xử lý đồng bộ lịch sử.');
        }else{
            RedisHelper::setKey('checkAsyncProductionPlan',true);
            return redirect()->back()->with('success','Đang bắt đầu xử lý đồng bộ.');
        }
    }

    public function asyncKTNQ(){
        RedisHelper::setKey('checkAsyncKTNQ',true);
        return redirect()->back()->with('success','Đang bắt đầu xử lý đồng bộ.');
    }
    public function asyncKTTM(){
        RedisHelper::setKey('checkAsyncKTTM',true);
        Artisan::call("sync:production_plan");
        return redirect()->back()->with('success','Đang bắt đầu xử lý đồng bộ.');
    }

    public function action(Request $request)
    {
        $method = $request->input('method', '');
        if ($method == 'per_page') {
            $this->per_page($request);
            return back();
        }
    }

}
