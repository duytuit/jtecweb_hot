<?php

namespace App\Http\Controllers\Frontend;

use App\Exports\DetailReportExport1;
use App\Exports\DetailReportExport;
use App\Helpers\ArrayHelper;
use App\Helpers\item;
use App\Helpers\RedisHelper;
use App\Http\Controllers\Controller;
use App\Imports\EmpImport;
use App\Imports\ProductionPlanImport;
use App\Models\Accessory;
use App\Models\Admin;
use App\Models\Asset;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\Exam;
use App\Models\LogImport;
use App\Models\ProductionPlan;
use App\Models\Required;
use App\Models\SignatureSubmission;
use App\Models\uploadData;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\File;
use Mike42\Escpos\ImagickEscposImage;

class FrontPagesController extends Controller
{
    /**
     * homePage
     *
     * HomePage of Application
     *
     * @return void
     */
    public function index()
    {
        return view('frontend.pages.index');
    }
    public function maintenance(Request $request)
    {
        if($request->key=='true'){
            Cache::put('maintenance', true);
        }
        if($request->key=='false'){
            Cache::put('maintenance', false);
        }
        $rs = Cache::get('maintenance');
        dd($rs);
    }
    public function syncDepartment(Request $request)
    {
       $depart = [
        'PD-Buredo',
        'PD-Kho Nhập',
        'PD-Kho xuất',
        'PD-Kĩ thuật',
        'PD-KTQN',
        'PD-KTTM',
        'PD-Lắp ráp',
        'PD-Buredo',
        'PD-Cắm',
        'PD-Cắt',
        'PD-Dập',
        'PD-QLSX',
        'GA- Humanresorce'
       ];
       foreach ($depart as $key => $value) {
          $_depart = Department::where('name','like','%'.$value.'%')->first();
          if(!$_depart){
                Department::create([
                    'code' => time(),
                    'name' => $value,
                    'parent_id' => 0,
                    'status' => 1,
                    'created_by' => Auth::user()->id,
                ]);
          }
       }
    }
    public function syncEmployee(Request $request)
    {
        $details = null;
        $begin_date_company = null;
        $__key=null;
        $emp_dept=null;
        $emp=null;
        try {
            set_time_limit(0);
            $details = (new FastExcel)->sheet(2)->withoutHeaders()->import('//192.168.207.6/jtecdata/QUAN LY SAN XUAT/TRAM TRAN/Quản Lý Người/DSCN/Theo dõi nhân sự nhà máy.xlsx');
            if (count($details) == 0) {
                LogImport::create([
                    'type' => 1,
                    'status' => 0,
                    'data' => json_encode($details),
                    'messages' => "Không tìm thấy dữ liệu nhân sự"
                ]);
                dd('thất bại');
            }
            $newarray_answer = [];
            foreach ($details as $key => $value) {
               if($key > 17){
                  try {
                     $_value = json_encode($value);
                     $_value = json_decode($_value);
                     LogImport::create([
                        'type' => 1,
                        'status' => 0,
                        'data' =>  $value[3],
                        'messages' =>  $value[4]
                    ]);
                    // if($value[3] == 'PD'){
                    //     dd($value);
                    // }
                    //   DB::beginTransaction();
                    //  $begin_date_company = $value[6];
                    //  $end_date_company = $value[8];
                    //  $birthday = null;

                    //  if($this->validateDate($value[6])){
                    //     $begin_date_company = $value[6]->format('Y-m-d');
                    //  }
                    //  if($this->validateDate($value[8])){
                    //     $end_date_company = $value[8]->format('Y-m-d');
                    //  }

                    // $emp = Employee::where('code',(int) trim($value[1]))->first();

                    // $dept = Department::where('name',trim((string)$value[3]))->first();
                    // if($emp){
                    //     $emp_dept = EmployeeDepartment::where('employee_id', $emp->id)->first();
                    // }
                    // if($value[3] =='PD-NQ'){
                    //     $dept = Department::where('name','PD-KTQN')->first();
                    //  }

                    // $__key=array_search($value[5], array_column(json_decode(json_encode(ArrayHelper::positions()),TRUE), 'name'));

                    // if (!$emp) {

                    //     $parts = explode(" ", $value[2]);
                    //     if (count($parts) > 1) {
                    //         $lastname = array_pop($parts);
                    //         $firstname = implode(" ", $parts);
                    //     } else {
                    //         $firstname = $value;
                    //         $lastname = " ";
                    //     }
                    //     // Tạo nhân viên
                    //     $emp = Employee::create([
                    //         'code' => (int) trim($value[1]),
                    //         'first_name' => $firstname,
                    //         'last_name' => $lastname,
                    //         'begin_date_company' => $begin_date_company,
                    //         'end_date_company' => $end_date_company,
                    //         'status' => 1,
                    //         'created_by' => 1,
                    //         'birthday' => $birthday,
                    //         'worker' => 3,  // phân quyền login khi mới tạo tài khoản

                    //     ]); // Tạo một đối tượng Employee mới
                    //     $emp_dept= EmployeeDepartment::create([
                    //         'employee_id' => $emp->id,
                    //         'department_id' => $dept->parent_id,
                    //         'created_by' => 1,
                    //         'process_id' =>$dept->id,
                    //         'positions' =>ArrayHelper::positions()[$__key]['id'],
                    //     ]);

                    //     //Tạo tài khoản
                    //     $admin = Admin::create([
                    //         'first_name' => $firstname,
                    //         'last_name' => $lastname,
                    //         'username' => $value[1],
                    //         'email' => $value[1] . 'exam@exam.com',
                    //         'password' => Hash::make($value[1]),
                    //         'status' => 1,
                    //         'created_at' => Carbon::now(),
                    //         'created_by' => 1,
                    //         'updated_at' => Carbon::now(),
                    //     ]);
                    //     // Assign Roles
                    //     $admin->assignRole('Worker');
                    // }else{

                    //     $emp->begin_date_company =  $begin_date_company;
                    //     $emp->end_date_company =  $end_date_company;
                    //     $emp->birthday = $birthday;
                    //     $emp->save();
                    // }
                    // if (!$emp_dept) {

                    //     EmployeeDepartment::create([
                    //         'employee_id' => $emp->id,
                    //         'department_id' => $dept->parent_id,
                    //         'process_id' =>  $dept->id,
                    //         'created_by' => 1,
                    //         'positions' =>ArrayHelper::positions()[$__key]['id'],
                    //     ]);

                    // }else{
                    //     $emp_dept->process_id =  $dept->id;
                    //     $emp_dept->positions =  ArrayHelper::positions()[$__key]['id'];
                    //     $emp_dept->save();
                    // }
                    // DB::commit();
                } catch (\Exception $th) {
                    DB::rollBack();
                    LogImport::create([
                        'type' => 1,
                        'status' => 0,
                        'data' => json_encode($value),
                        'messages' => $th->getLine() . '||' . $th->getTraceAsString()
                    ]);
                    continue;
                }
               }
            }
            dd($newarray_answer);
            RedisHelper::setKey('Sync_Employee',json_encode(['time'=>Carbon::now(),'status'=>1]));
        } catch (\Exception $e) {
            DB::rollBack();
            print_r($e->getTraceAsString());
            RedisHelper::setKey('Sync_Employee',json_encode(['time'=>Carbon::now(),'status'=>2]));
            LogImport::create([
                'type' => 1,
                'status' => 0,
                'data' => json_encode($details),
                'messages' => $e->getLine() . '||' . $e->getTraceAsString()
            ]);
        }

       dd('thành công');
    }
    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        try {
            return (DateTime::createFromFormat($format, $date) !== false);
        } catch (\Throwable $th) {
            return  false;
        }
    }
    public function detailReport(Request $request)
    {

        if ($request->type == 1) {
            $data['title'] = 'Thi lần 1 đạt';
        }
        if ($request->type == 2) {
            $data['title'] = 'Thi lần 1 chưa đạt( thi lại )';
        }
        if ($request->type == 3) {
            $data['title'] = 'Thi lần 1 chưa đạt( đào tạo lại )';
        }
        if ($request->type == 4) {
            $data['title'] = 'Chưa thi lần 1';
        }
        if ($request->type == 5) {
            $data['title'] = 'Thi lần 2 đạt';
        }
        if ($request->type == 6) {
            $data['title'] = 'Thi lần 2 chưa đạt( thi lại )';
        }
        if ($request->type == 7) {
            $data['title'] = 'Thi lần 2 chưa đạt( đào tạo lại )';
        }
        if ($request->type == 8) {
            $data['title'] = 'Chưa thi lần 2';
        }
        if ($request->type == 4 || $request->type == 8) {
            $data['lists'] = Employee::whereIn('code', array_column($request->emp, 'code'))->get();
        } else {
            $data['lists'] = Exam::whereIn('id', array_column($request->emp, 'id'))->get();
        }

        return (new DetailReportExport($data))->download('detail-report.xlsx');
    }
    public function detailReport1(Request $request)
    {

        if ($request->type == 1) {
            $data['title'] = 'Thi lần 1 đạt';
        }
        if ($request->type == 2) {
            $data['title'] = 'Thi lần 1 chưa đạt( thi lại )';
        }
        if ($request->type == 3) {
            $data['title'] = 'Thi lần 1 chưa đạt( đào tạo lại )';
        }
        if ($request->type == 4) {
            $data['title'] = 'Chưa thi lần 1';
        }
        if ($request->type == 5) {
            $data['title'] = 'Thi lần 2 đạt';
        }
        if ($request->type == 6) {
            $data['title'] = 'Thi lần 2 chưa đạt( thi lại )';
        }
        if ($request->type == 7) {
            $data['title'] = 'Thi lần 2 chưa đạt( đào tạo lại )';
        }
        if ($request->type == 8) {
            $data['title'] = 'Chưa thi lần 2';
        }
        if ($request->type == 4 || $request->type == 8) {
            $data['lists'] = Employee::whereIn('code', array_column($request->emp, 'code'))->get();
        } else {
            $data['lists'] = Exam::whereIn('id', array_column($request->emp, 'id'))->get();
        }

        return (new DetailReportExport1($data))->download('detail-report.xlsx');
    }
    public function check_device(Request $request)
    {
        $data['ip_client'] = $_SERVER['REMOTE_ADDR'];
        $data['device'] = $_SERVER['HTTP_USER_AGENT'];
        $data['uuid'] = Str::uuid()->toString();
        $data['current_time'] = Carbon::now();
        return view('frontend.pages.check_device',$data);
    }
    public function check_device_realtime(Request $request)
    {
        return view('frontend.pages.check_device_realtime');
    }
    public function remote_lamp(Request $request)
    {
        return view('frontend.pages.remote_lamp');
    }
    public function clearCache()
    {
        Artisan::call("cache:clear");
        Artisan::call("view:clear");
    }
    public function viewDataAssemble(Request $request)
    {
        $user = Auth::user();
        $employee = @$user->employee;
        if($employee){
            $data['employee'] =$employee;
        }
        $data['fileBase64'] ='';
        $data['working_hour'] = '';
        $data['code'] = '';
        $data['lot_no'] = '';
        $data['index'] = '';
        if(!$request->code){
            $data['message'] = 'Chưa có dữ liệu!';
            return view('frontend.pages.view_data_assemble',$data);
        }
        $array_code = explode(',', $request->code);
        if(count($array_code) == 0){
            $data['message'] = 'Chưa có dữ liệu!';
            return view('frontend.pages.view_data_assemble',$data);
        }
        $DFW_H10F = DB::connection('oracle')->table('DFW_H10F')->select('品目C','発注SEQ')->where(function($query) use($request,$array_code){
            $query->where('発注SEQ', 'like',trim(substr($array_code[0],2,8)).'%')
                  ->orWhere('発注SEQ', 'like',(int)trim(str_replace('  ','',$request->code)).'%');
        })->first();
        if($DFW_H10F){
            $data['code'] = $DFW_H10F->品目c;
            $data['lot_no'] = $DFW_H10F->発注seq;
            $data['index'] = trim(substr($array_code[0],12,5));
            $DFW_MP0M = DB::connection('oracle')->table('DFW_MP0M')->select('加工時間')->where('手配先C','like','1523%')->where('親品目C','like',trim($DFW_H10F->品目c).'%')->first();
            if($DFW_MP0M){
                $data['working_hour'] = sprintf("%04s", $DFW_MP0M->加工時間)  ;
            }
            $_uploadData = uploadData::where('code','like',trim($DFW_H10F->品目c).'%')->first();
            if($_uploadData){
                // $_url = str_replace('/','\\',str_replace('//192.168.207.6','D:/',$_uploadData->url));
                // $data['fileBase64'] =ArrayHelper::convertPdfToBase64($_url);
                $data['url']= $_uploadData->url;
                return view('frontend.pages.view_data_assemble',$data);
            }
        }
        $data['message'] = 'Chưa có dữ liệu!';
        return view('frontend.pages.view_data_assemble',$data);
    }
    public function viewDataAssemblePdf(Request $request)
    {
        if(!$request->code){
            $data['message'] = 'Chưa có dữ liệu!';
            return view('frontend.pages.view_data_assemble_v2',$data);
        }
        $_uploadData = uploadData::where('code','like',$request->code.'%')->first();
        if($_uploadData){
            $data['url']= $_uploadData->url;
            return view('frontend.pages.view_data_assemble_v2',$data);
        }
        $data['message'] = 'Chưa có dữ liệu!';
        return view('frontend.pages.view_data_assemble_v2',$data);
    }
    public function assembleStore(Request $request)
    {
        try {
            $user = Auth::user();
            $employee = @$user->employee;
            if($employee){
                $employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->first();
            }
            $requireCode = 'R_' . now()->format('Ymdhis');
            $pc_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            DB::beginTransaction();

            $content_form['code'] = $request->code;
            $content_form['lot_no'] = $request->lot_no;
            $content_form['index'] = $request->index;
            $content_form['working_hour'] = $request->working_hour;
            $content_form['start_time'] = $request->start_time;
            $content_form['end_time'] = $request->end_time;
            $content_form['complete_time'] = $request->complete_time;
            $content_form['quantity'] = $request->quantity;
            $content_form['pc_name'] = $pc_name;

            $required = Required::create([
                'required_department_id' => $employeeDepartment->department_id ?? 0,
                'code_required' => $requireCode,
                'code' => '',
                'quantity' => (int)$request->quantity,
                'created_by' => @$employee->id ?? 0,
                'date_completed' => Carbon::now(),
                'completed_by' => @$employee->id ?? 0,
                'usage_status' => 1,
                'content_form' => json_encode($content_form),
                'status' => 0,
                'from_type' => ArrayHelper::from_type_rquired_assemble,
                'content' => '',
            ]);
            SignatureSubmission::create([
                'required_id' =>  @$required->id,
                'department_id' => $employeeDepartment->department_id ?? 0,
                'positions' => 0,
                'approve_id' => json_encode([]),
                'status' => 0,
                'signature_id' => 0,
            ]);
            DB::commit();
           return $this->success(['susses'=>true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(['error' => $e->getLine(), 'detail' => $e->getTraceAsString()]);
        }
    }
    public function check_device_store(Request $request)
    {
        try {
            $user = Auth::user();
            $employee = @$user->employee;
            if($employee){
                $employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->first();
            }
            $requireCode = 'R_' . now()->format('Ymdhis');
            $required = Required::where('type',0)
                                      ->where(['from_type'=>ArrayHelper::from_type_check_device_v2])
                                      ->where('created_at','like',Carbon::now()->format('Y-m-d').'%')
                                      ->whereRaw('JSON_EXTRACT(content_form, "$.ip_client") = ?', [$request->ip_client])->orderBy('id','desc')->first();

            DB::beginTransaction();
            if($required){
                $content_form = json_decode($required->content_form);
                if($request->status == 'out' && $content_form->status == 'in'){
                    $content_form->status=$request->status;
                    $content_form->time_out=$request->current_time;
                    $content_form->status_out=1;
                    $required->content_form=json_encode($content_form);
                    $required->save();
                }
                if($request->status == 'in' && $content_form->status == 'out'){
                    $content_form->room=$request->room;
                    $content_form->username=$request->username;
                    $content_form->ip_client=$request->ip_client;
                    $content_form->device=$request->device;
                    $content_form->status=$request->status;
                    $content_form->time_out=null;
                    $content_form->status_out=0;
                    $content_form->time_in=$request->current_time;
                    $content_form->status_in=1;
                    $required = Required::create([
                        'required_department_id' =>$employeeDepartment->department_id??0,
                        'code_required' => $requireCode,
                        'code' => '',
                        'quantity' => 1,
                        'created_by' => @$employee->id??0,
                        'date_completed'=> Carbon::now(),
                        'completed_by'=>@$employee->id??0,
                        'usage_status' => 1,
                        'content_form' => json_encode($content_form),
                        'status' => 0,
                        'from_type' => ArrayHelper::from_type_check_device_v2,
                        'content' =>'',
                    ]);
                    SignatureSubmission::create([
                        'required_id' =>  @$required->id,
                        'department_id' =>$employeeDepartment->department_id??0,
                        'positions' => 0,
                        'approve_id' => json_encode([]),
                        'status' => 0,
                        'signature_id' => 0,
                    ]);
                }

            }else{
                if ($request->status == 'in') {

                    $content_form['room'] = $request->room;
                    $content_form['username'] = $request->username;
                    $content_form['ip_client'] = $request->ip_client;
                    $content_form['device'] = $request->device;
                    $content_form['status'] = $request->status;
                    $content_form['time_out'] = null;
                    $content_form['status_out'] = 0;
                    if ($request->status == 'in') {
                        $content_form['time_in'] = $request->current_time;
                        $content_form['status_in'] = 1;
                    }
                    $required = Required::create([
                        'required_department_id' => $employeeDepartment->department_id ?? 0,
                        'code_required' => $requireCode,
                        'code' => '',
                        'quantity' => 1,
                        'created_by' => @$employee->id ?? 0,
                        'date_completed' => Carbon::now(),
                        'completed_by' => @$employee->id ?? 0,
                        'usage_status' => 1,
                        'content_form' => json_encode($content_form),
                        'status' => 0,
                        'from_type' => ArrayHelper::from_type_check_device_v2,
                        'content' => '',
                    ]);
                    SignatureSubmission::create([
                        'required_id' =>  @$required->id,
                        'department_id' => $employeeDepartment->department_id ?? 0,
                        'positions' => 0,
                        'approve_id' => json_encode([]),
                        'status' => 0,
                        'signature_id' => 0,
                    ]);
                }
            }
            DB::commit();
           return $this->success(['susses'=>true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(['error' => $e->getLine(), 'detail' => $e->getTraceAsString()]);
        }
    }
    public function clearKeysRedis(Request $request)
    {
        $allKey = Redis::keys('*' . $request->key . '*');
        if($allKey){
            $result = Redis::del($allKey);
        }
        $allKey = Redis::connection('default')->keys('*' . $request->key . '*');
        if($allKey){
            $result = Redis::connection('default')->del($allKey);
        }
    }

    public function getKeysRedis(Request $request)
    {
        $resuls = Redis::get('jtec_hn_database__ip_192.168.207.168');
        dd($resuls);
        $exam = Redis::keys('*_ip_*');
        dd($exam);
        if(count($exam)>0){
             foreach ($exam as $key => $value) {

             }
        }
    }
    public function exam(Request $request)
    {
        $data['arrayExamPd'] = ArrayHelper::arrayExamPd()[$request->type];
        return view('frontend.pages.exam',$data);
    }

    // New exam
    public function examNew(Request $request)
    {
        $cycle_name = Carbon::now()->format('mY');
        $new_cycle_name = Carbon::parse(substr((int)$cycle_name, 1, 4) . '-' . substr((int)$cycle_name, 0, 1) . '-1')->subMonths(1)->format('Y-m-d');
        $data['arrayExamPd'] = ArrayHelper::arrayExamPd()[$request->type];
        $getEmployeeBeginOneMonth = Employee::whereDate('begin_date_company', '>=', $new_cycle_name)->Where('code', $request->code)->first();
        if (!$getEmployeeBeginOneMonth) {
            return redirect()->back()->with('warning', 'Mã code không hợp lệ. Bạn cần cập nhật lại ngày bắt đầu làm việc.');
        } else {
            return view('frontend.pages.examNew',$data);
        }
    }

    public function test()
    {
        $fruits = ArrayHelper::arrayExamPd();
        shuffle($fruits);
        dd($fruits);
    }
    public function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    static function getWifiSSID()
    {
        $output = shell_exec('netsh wlan show interfaces');
        if ($output) {
            if (preg_match('/SSID\s*:\s*(.*)/i', $output, $matches)) {
                return $matches[1];
            } else {
                return 'SSID not found';
            }
        } else {
            return 'Command failed';
        }
    }
    public function fgetCPULoad(){
        $load = null;

        $cmd = 'wmic cpu get loadpercentage /all';
        @exec($cmd, $output);

        if ($output){
            foreach ($output as $line){
                if ($line && preg_match("/^[0-9]+\$/", $line)){
                    $load = $line;
                    break;
                    }
                } // /foreach output
        } // /IF $output

        return $load;
    } // /fgetCPULoad()


    /*
    *  GET TotalHd Space in bytes
    *
    *  @return int $TotalHD
    */

    public function fGetTotalHD( ){
        $UnitPath   = substr($_SERVER['DOCUMENT_ROOT'], 0, 2);
	    $TotalHD    = disk_total_space($UnitPath);
        return $TotalHD;
    }// /fGetTotalHD()


    /*
    *  GET fGetHWVersion
    *
    *  @return string
    */

    public function fGetHWVersion( ){
        return 'Not Available';
    }// /fGetHWVersion()




    /*
    *  GET fGetReleaseDistrib
    *
    *  @return string
    */

    public function fGetReleaseDistrib( ){
        return 'Not Available';
    }// /fGetReleaseDistrib()




    /*
    *  GET fGetNumCPUs
    *
    *  @return integer
    */

    public function fGetNumCPUs( ){
        return 1; // Not available for Windows
    }// /fGetNumCPUs()





    /*
    *  GET TotalFreeHd Space in bytes
    *
    *  @return int $TotalFreeHD
    */

    public function fGetTotalFreeHD( ){
        $UnitPath   = substr($_SERVER['DOCUMENT_ROOT'], 0, 2);
	    $TotalFreeHD    = disk_free_space($UnitPath);
        return $TotalFreeHD;
    }// /fGetTotalFreeHD()



    /*
    *  GET TotalUsedHd Space in bytes
    *
    *  @return int $TotalUsedHD
    */

    public function fGetTotalUsedHD( ){
	    $TotalUsedHD    = $this->fGetTotalHD() - $this->fGetTotalFreeHD();
        return $TotalUsedHD;
    }// /fGetTotalUsedHD()



    /*
    *  GET MemResources
    *
    *  @return array $items
    */

    public function fGetMemResources( ){
        @exec( 'systeminfo', $output );

		foreach ( $output as $value ) {
			if ( preg_match( '|Total Physical Memory\:([^$]+)|', $value, $m ) ) {
				$MemTotal = trim( $m[1] );
				$MemTotal = str_replace('.', '', $MemTotal);
				$MemTotal = str_replace(' MB', '', $MemTotal);
				$MemTotal = (int)$MemTotal;
				$MemTotal *= 1024; // Mb 2 kb
				$MemTotal = (string)$MemTotal;
				} else if ( preg_match( '|Available Physical Memory\:([^$]+)|', $value, $m ) ) {
				$MemFree = trim( $m[1] );
				$MemFree = str_replace('.', '', $MemFree);
				$MemFree = str_replace(' MB', '', $MemFree);
				$MemFree = (int)$MemFree;
				$MemFree *= 1024; // Mb 2 kb
				$MemFree = (string)$MemFree;
				}
			} // /Foreach
        $MemResources = [['MemTotal']=> $MemTotal, ['MemFree']=> $MemFree, ['MemAvailable']=> ($MemTotal-$MemFree) ];

        return $MemResources;
    }// /fGetMemResources()




    /*
    *  GET Uptime String
    *
    *  @return string $uptime
    */

    public function fGetUptime( ){
        $uptime = '';

        @exec( 'systeminfo', $output );

		foreach ( $output as $value ) {
			if ( preg_match( '|System Boot Time\:([^$]+)|', $value, $m ) ) {
				$uptime = 'Uptime From '.trim($m[1]);
				}
			} // /Foreach

        return $uptime;
    }// /fGetUptime()




    /*
    *  GET OS Version
    *
    *  @return string $OsVersion
    */

    public function fGetOSVersion( ){
        $OSName = '';

        @exec( 'systeminfo', $output );

		foreach ( $output as $value ) {
			if ( preg_match( '|OS Name\:([^$]+)|', $value, $m ) ) {
				$OSName = trim( $m[1] );
				}
			} // /Foreach

        return $OSName;
    }
    public function updatePermision(){
         /**
         * @var array Admin User Permissions group wise
         */
        $permissionGroups = [
            'dashboard' => [
                'dashboard.view',
            ],

            'settings' => [
                'settings.view',
                'settings.edit',
            ],

            'permission' => [
                'permission.view',
                'permission.create',
                'permission.edit',
                'permission.delete',
            ],

            'admin' => [
                'admin.view',
                'admin.create',
                'admin.edit',
                'admin.delete',
            ],

            'admin_profile' => [
                'admin_profile.view',
                'admin_profile.edit',
            ],

            'role_manage' => [
                'role.view',
                'role.create',
                'role.edit',
                'role.delete',
            ],

            'user' => [
                'user.view',
                'user.create',
                'user.edit',
                'user.delete',
            ],

            'category' => [
                'category.view',
                'category.create',
                'category.edit',
                'category.delete',
            ],

            'page' => [
                'page.view',
                'page.create',
                'page.edit',
                'page.delete',
            ],

            'service' => [
                'service.view',
                'service.create',
                'service.edit',
                'service.delete',
            ],

            'booking_request' => [
                'booking_request.view',
                'booking_request.edit',
                'booking_request.delete',
            ],

            'blog' => [
                'blog.view',
                'blog.create',
                'blog.edit',
                'blog.delete',
            ],

            'slider' => [
                'slider.view',
                'slider.create',
                'slider.edit',
                'slider.delete',
            ],

            'exam' => [
                'exam.view',
                'exam.create',
                'exam.edit',
                'exam.delete',
            ],

            'department' => [
                'department.view',
                'department.create',
                'department.edit',
                'department.delete',
            ],

            'activity' => [
                'activity.view',
                'activity.create',
                'activity.edit',
                'activity.delete',
            ],

            'checkdevice' => [
                'checkdevice.view',
                'checkdevice.create',
                'checkdevice.edit',
                'checkdevice.delete',
            ],

            'asset' => [
                'asset.view',
                'asset.create',
                'asset.edit',
                'asset.delete',
            ],

            'assemble' => [
                'assemble.view',
                'assemble.create',
                'assemble.edit',
                'assemble.delete',
            ],

            'campaign' => [
                'campaign.view',
                'campaign.create',
                'campaign.edit',
                'campaign.delete',
            ],

            'campaign_detail' => [
                'campaign_detail.view',
                'campaign_detail.create',
                'campaign_detail.edit',
                'campaign_detail.delete',
            ],

            'comment' => [
                'comment.view',
                'comment.create',
                'comment.edit',
                'comment.delete',
            ],

            'cronjob' => [
                'cronjob.view',
                'cronjob.create',
                'cronjob.edit',
                'cronjob.delete',
            ],
            'checkTension' => [
                'checkTension.view',
                'checkTension.create',
                'checkTension.edit',
                'checkTension.delete',
            ],
            'productvt' => [
                'productvt.view',
                'productvt.create',
                'productvt.edit',
                'productvt.delete',
            ],
            'employee' => [
                'employee.view',
                'employee.create',
                'employee.edit',
                'employee.delete',
            ],

            'productionPlan' => [
                'productionPlan.view',
                'productionPlan.create',
                'productionPlan.edit',
                'productionPlan.delete',
            ],

            'employee_department' => [
                'employee_department.view',
                'employee_department.create',
                'employee_department.edit',
                'employee_department.delete',
            ],

            'log_import' => [
                'log_import.view',
                'log_import.create',
                'log_import.edit',
                'log_import.delete',
            ],

            'required' => [
                'required.view',
                'required.create',
                'required.edit',
                'required.confirm',
                'required.delete',
            ],

            'cutedp' => [
                'cutedp.view',
                'cutedp.create',
                'cutedp.edit',
                'cutedp.delete',
            ],

            'warehouse' => [
                'warehouse.view',
                'warehouse.ong',
                'warehouse.create',
                'warehouse.edit',
                'warehouse.delete',
            ],

            'warehouse_v2' => [
                'warehouse_v2.view',
                'warehouse_v2.ong',
                'warehouse_v2.create',
                'warehouse_v2.edit',
                'warehouse_v2.delete',
            ],

            'accessory' => [
                'accessory.view',
                'accessory.create',
                'accessory.edit',
                'accessory.delete',
            ],

            'signature_submission' => [
                'signature_submission.view',
                'signature_submission.create',
                'signature_submission.edit',
                'signature_submission.delete',
            ],

            'tracking' => [
                'tracking.view',
                'tracking.delete',
            ],

            'notifications' => [
                'email_notification.view',
                'email_notification.edit',
                'email_message.view',
                'email_message.edit',
            ],

            'contacts' => [
                'contact.view',
                'contact.create',
            ],

            'module' => [
                'module.view',
                'module.create',
                'module.edit',
                'module.delete',
                'module.toggle',
            ],

            'question' => [
                'question.view',
                'question.create',
                'question.edit',
                'question.delete',
                'question.toggle',
            ],

            'dynamicColumn' => [
                'dynamicColumn.view',
                'dynamicColumn.create',
                'dynamicColumn.edit',
                'dynamicColumn.delete',
                'dynamicColumn.toggle',
            ],

            'checkCutMachine' => [
                'checkCutMachine.view',
                'checkCutMachine.create',
                'checkCutMachine.edit',
                'checkCutMachine.delete',
            ],

            'requestForm' => [
                'requestForm.view',
                'requestForm.create',
                'requestForm.edit',
                'requestForm.delete',
            ],
            'requestVpp' => [
                'requestVpp.view',
                'requestVpp.create',
                'requestVpp.edit',
                'requestVpp.delete',
                'requestVpp.confirm',
            ],
            'test_exam' => [
                'test_exam.view',
                'test_exam.create',
                'test_exam.edit',
                'test_exam.delete',
            ],
            'tool' => [
                'tool.view',
                'tool.create',
                'tool.edit',
                'tool.delete',
            ],
            'upload_data' => [
                'upload_data.view',
                'upload_data.create',
                'upload_data.edit',
                'upload_data.delete',
            ],
        ];

        // Assign group wise permissions
        foreach ($permissionGroups as $groupKey => $permissionGroup) {
            foreach ($permissionGroup as $permissionName) {
                $permission = Permission::where('guard_name', 'admin')->where('group_name', $groupKey)->where('name', $permissionName)->first();
                if (empty($permission)) {
                    $permission = Permission::create([
                        'guard_name' => 'admin',
                        'group_name' => $groupKey,
                        'name' => $permissionName,
                    ]);
                }
            }
        }
        $roleSuperAdmin = Role::where('guard_name', 'admin')->where('name', 'Super Admin')->first();
        $permission = Permission::all();
        foreach ($permission as $key => $value) {
            $roleSuperAdmin->givePermissionTo($value);
            $value->assignRole($roleSuperAdmin);
        }
    }
    public function updateEmployeeProductionPlan(){
        RedisHelper::setKey('checkAsyncProductionPlan',true);
        echo "Đang bắt đầu đồng bộ";
    }
    function title(Printer $printer, $str)
    {
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
        $printer -> text($str);
        $printer -> selectPrintMode();
    }
    function getPCname()
    {
        $pc_name =  gethostbyaddr($_SERVER['REMOTE_ADDR']);
        dd($pc_name);
    }
    public function test1()
    {
        dd(config('system'));
        // $edp = DB::connection('oracle_toa_set')->table('TDCJSIJI')->where('HINCD', 'like','%6219818422-01%')->where('SENBAN','201')->first();
        // dd($edp);
        $required = Required::withTrashed()->find(22445);
        //$edp->employee;
        $edps[]=$required;
        $html =  view('qrcode.edp', ['edps' => $edps])->render();
        dd($html);
        $accessory = DB::table('accessories')->paginate(50);
        return response($accessory);
        $sdfd = "nguyễn duy tú";
        $gffg = explode('tú',$sdfd);
        dd($gffg[0]);
        // $fgfdg =  DB::table('accessories')->select('code')->orderBy('id')->get();
        // dd($fgfdg);
        $dsfgf =  RedisHelper::get('check_duplicate_order');
         dd($dsfgf);
        // $fdgfdgfdgf=483;
        // $fdgfdg =  SignatureSubmission::whereRaw('JSON_CONTAINS(approve_id,"'.$fdgfdgfdgf.'")')->first();
        // dd($fdgfdg);
        // $edp = DB::connection('oracle_toa_set')->table('TDCJSIJI')->where('HINCD', 'like','%3600079-02%')->where('SENBAN',62)->first();
        // dd($edp);
        // $dfgfdg = "W AVSS0.5FBR";
        // $accessory = Accessory::whereRaw("BINARY code = '$dfgfdg'")->get();
        // dd($accessory);
        // $dfgfdg = "0102480640";
        // $dfgf324dg = "0102480640";
        // if($dfgfdg === $dfgf324dg){
        //     echo "đúng";
        // }else{
        //     echo "sai";
        // }
        // $useragent = $_SERVER['HTTP_USER_AGENT'];
        // $info = get_browser($useragent);
        // dd($info);
        // $files = glob("//192.168.207.6/JtecData/QUAN LY SAN XUAT/VUI/コマツインドの出荷日程*.xlsx");
        // dd($files);
        // $files = glob("//192.168.207.6/jtecdata/JTEC_PD_PROGAM/duytuit/vui/コマツインドの出荷日程*.xlsx");
        // dd($files);
       //$sql = "SELECT 場所c,棚番 FROM TAD_Z60M WHERE 品目C = 'AESSX0.75FB'";
       //$getList = DB::connection('oracle')->select($sql);
       //dd($getList);
       //$dsfds = RedisHelper::queueRange('print_required1232',0,30);
       //dd($dsfds);
        //  $dfgfg = Required::where(['from_type'=>0,'type'=>0])->limit(15)->get();
        //  foreach ($dfgfg as $key => $value) {
        //      RedisHelper::queueSet('print_required1232', $value);
        //  }
         //dd('thành công');


        // $query->whereRaw('CASE WHEN JSON_VALID(JSON_EXTRACT(content_form, "$.location")) THEN JSON_EXTRACT(description, "$.thu2_le") ELSE null END', [0]);
        // $ids = Required::where(['from_type'=>0,'type'=>0])->whereRaw('JSON_VALUE(content_form, "$.location") = 0')->pluck('id');
        // $fdgf =  Required::where(['from_type'=>0,'type'=>0])->whereNotIn('id',$ids)->get();
        // foreach ($fdgf as $key => $value) {
        //     $accessory = Accessory::where('code', $value->code)->first();
        //     if(!$accessory){
        //         continue;
        //     }
        //     $content_form=[];
        //     $sql = "SELECT * FROM TAD_Z60M WHERE 品目C = '$accessory->code'";
        //     $getList = DB::connection('oracle')->select($sql);

        //     $location = array_filter($getList, fn ($element) => $element->場所c == '0111');
        //     if(count($location)>0){
        //         $location = current($location);
        //         $content_form['location'] = trim($location->棚番);
        //     }
        //     $location_order = array_filter($getList, fn ($element) => $element->場所c == '1510');
        //     if(count($location_order)>0){
        //         $location_order = current($location_order);
        //         $content_form['location_order'] = trim($location_order->棚番);
        //     }

        //     $content_form['code'] = $accessory->code;
        //     $content_form['quantity'] = $value->quantity;
        //     $content_form['size'] = $accessory->material_norms;
        //     $content_form['unit_price'] = $accessory->unit;
        //     $content_form['location_c'] = $accessory->location_c;
        //     $content_form['usage_status'] = $value->usage_status;
        //     DB::table('requireds')->where('id',$value->id)->update([
        //         'content_form'=>json_encode($content_form)
        //     ]);
        // }
        // dd('thành công');
        // $sql = "SELECT * FROM TAD_Z60M WHERE 品目C = 'W AVSS0.5FB'";
        // $getList = DB::connection('oracle')->select($sql);
        // $array_answer = array_filter($getList, fn ($element) => $element->場所c == '0111');
        // dd($array_answer);
        // $array_answer = current($array_answer);
        // dd($array_answer);
        //  $store = DB::connection('oracle_toa_set')->table('TDCJSIJI_TOA')
        //    ->where('HINCD', 'like', '0111%')
        //    ->where('品目K', 'like', '7'.'%')
        //    ->where('品目C', 'like', 'AVS2R%')->orderBy('品目C')->orderBy('年月度','desc')->first();
        // dd($store);
        // dd((int)trim(str_replace('  ','','2970083 0')));
        // $dfgfd ="ke_hoach_san_xuat_24_09_26_09_22_02";
        // $dfgdfg = explode('ke_hoach_san_xuat_',$dfgfd)[1];
        // $fdgdfgfd = explode('_',$dfgdfg);
        // dd($fdgdfgfd[5].":".$fdgdfgfd[4].":".$fdgdfgfd[3]." ".$fdgdfgfd[2].":".$fdgdfgfd[1].":".$fdgdfgfd[0]);
        //RedisHelper::setKey('update_EmployeeProductionPlan',json_encode(['time'=>$fdgdfgfd[5].":".$fdgdfgfd[4]." ".$fdgdfgfd[3].":".$fdgdfgfd[2].":".$fdgdfgfd[1].":".$fdgdfgfd[0],'status'=>1]));
        // $allKey = Redis::keys('*ke_hoach_san_xuat*');
        // dd($allKey);
        //$details = (new FastExcel)->sheet(2)->withoutHeaders()->import('//192.168.207.6/JtecData/QUAN LY SAN XUAT/VUI/コマツインドの出荷日程.xlsx');
        //dd($details->toArray());
        //RedisHelper::setAndExpire('ke_hoach_san_xuat_'.Carbon::now()->format('Y_m_d_h_i_s'),json_encode($details->toArray()),60*60*24);
        //192.168.207.6/JtecData/SHARE/Le Tham/Kiểm tra - Đồ Gá/Kiểm tra - Đồ Gá
        // $details = (new FastExcel)->sheet(2)->withoutHeaders()->import('//192.168.207.6/JtecData/QUAN LY SAN XUAT/VUI/コマツインドの出荷日程.xlsx');
        // dd($details);
        // $data['productionPlanKTNQ']= Cache::get('productionPlanKTNQ');
        // $data['productionPlanKTTM']= Cache::get('productionPlanKTTM');
        // dd( $data['productionPlanKTNQ']);
        // dd($details[5]);
        // ===kiểm tra thông mạch====
        // set_time_limit(0);
        // $details = (new FastExcel)->sheet(6)->withoutHeaders()->import('//192.168.207.6/JtecData/SHARE/Le Tham/Kiểm tra - Đồ Gá/Kiểm tra - Đồ Gá/検査治具依頼リスト（新） JTEC⇔PD(2024.08.14.xlsx');
        // if (count($details) == 0) {
        //     LogImport::create([
        //         'type' => 1,
        //         'status' => 0,
        //         'data' => 'đồng bộ kiểm tra thông mạch',
        //         'messages' => "Không tìm thấy dữ liệu"
        //     ]);
        //    echo "Không tìm thấy dữ liệu";
        // }
        // foreach ($details as $key => $value) {
        //     if( $key >4){
        //         if(@$value[1]){
        //             $productionPlan = ProductionPlan::where('code',trim($value[1]))->whereNotNull('description')->first();
        //             if($productionPlan){
        //                 $kttm = (array)json_decode($productionPlan->kttm);
        //                 if($kttm){
        //                     $kttm[1]=@$value[5];//thùng mẫu
        //                     $kttm[2]=@$value[7];//thời gian
        //                     $kttm[3]=@$value[8];//ghi chú
        //                     $kttm[4]=@$value[9];//hiện trạng
        //                     $productionPlan->kttm = json_encode($kttm);
        //                     $productionPlan->save();
        //                 }else{
        //                     $kttm=[];
        //                     $kttm[1]=@$value[5];//thùng mẫu
        //                     $kttm[2]=@$value[7];//thời gian
        //                     $kttm[3]=@$value[8];//ghi chú
        //                     $kttm[4]=@$value[9];//hiện trạng
        //                     $productionPlan->kttm = json_encode($kttm);
        //                     $productionPlan->save();
        //                 }
        //             }
        //         }
        //     }
        // }
        // =========kiểm tra ngoại quan=================================================================
        // set_time_limit(0);
        // $details = (new FastExcel)->sheet(1)->withoutHeaders()->import('//192.168.207.6/JtecData/KIEM TRA/2. FILE  HÀNG MẪU  検査/__KTNQ.xlsx');
        // // dd($details[5]);
        // if (count($details) == 0) {
        //     LogImport::create([
        //         'type' => 1,
        //         'status' => 0,
        //         'data' => 'đồng bộ kiểm tra ngoại quan',
        //         'messages' => "Không tìm thấy dữ liệu"
        //     ]);
        //    echo "Không tìm thấy dữ liệu";
        // }
        // foreach ($details as $key => $value) {
        //     if($key > 4){
        //         if(@$value[1]){
        //             $productionPlan = ProductionPlan::where('code',trim($value[1]))->whereNotNull('description')->first();
        //             if($productionPlan){
        //                 $ktnq = (array)json_decode($productionPlan->ktnq);
        //                 if($ktnq){
        //                     $ktnq[1]=@$ktnq[1];//bản vẽ
        //                     $ktnq[2]=@$value[5];//thùng mẫu
        //                     $ktnq[3]=@$value[7];//ghi chú trạng thái
        //                     $ktnq[4]=@$value[11];//ghi chú
        //                     $productionPlan->ktnq = json_encode($ktnq);
        //                     $productionPlan->save();
        //                 }else{
        //                     $ktnq=[];
        //                     $ktnq[1]=null;//bản vẽ
        //                     $ktnq[2]=@$value[5];//thùng mẫu
        //                     $ktnq[3]=@$value[7];//ghi chú trạng thái
        //                     $ktnq[4]=@$value[11];//ghi chú
        //                     $productionPlan->ktnq = json_encode($ktnq);
        //                     $productionPlan->save();
        //                 }

        //             }
        //         }
        //     }
        // }
        // dd(file_exists('//192.168.207.6/JtecData/QUAN LY SAN XUAT/HANG/A1  KE HOACH SAN XUAT/A Năm 2024/Thang '.(int)$curent_month.'/Ke hoach san xuat thang  '.(int)$curent_month.'.xlsx'));
      // dd((string)(int)trim(str_replace('00','',$gfdgdfg)));
        // $dfsgsdfg ="[{\"quantity\":2,\"date\":\"2024-08-12T08:57:47.450340Z\",\"user_id\":225,\"note\":null}]";
        // $sdf = json_decode($dfsgsdfg);
        // $sdf[0]->inventory_accessory=34;
        // dd(1234);
        // dd(ArrayHelper::BILL_NEW);
        // $result = file_exists('D:\jtecdata\JTEC_PD_PROGAM\CMSWeb\data_laprap\17M0643221-58045c5c-7e50-4a39-b48b-69fa3d4e4f91.pdf');
        // dd( $result);

        // dd(public_path('public\assets\frontend\document.pdf'));


        // $key=array_search("S Leader", array_column(json_decode(json_encode(ArrayHelper::positions()),TRUE), 'name'));
        // dd(ArrayHelper::positions()[$key]);
        // RedisHelper::setKey('Sync_Employee',json_encode(['time'=>Carbon::now(),'status'=>1]));

        // $fdsgfdg =  RedisHelper::getKey('Sync_Employee');

        // dd($fdsgfdg);

    //    $dfgdg = Cache::get('maintenance');
    //    dd($dfgdg);
    //     Cache::put('maintenance', false);
        // $dfg = Artisan::call('sync:production_plan');
        // dd($dfg);
        // $dfdfg = RedisHelper::getKey('update_EmployeeProductionPlan');
        // dd($dfdfg);
      //  exec("cd D:/JtecData/JTEC_PD_PROGAM/CMSWeb/jtecweb && nohup php artisan sync:production_plan --daemon &", $r2);

    //    exec('nohup php artisan sync:production_plan > /dev/null &');

            // exec("nohup php artisan sync:production_plan --daemon &", $r2);
        // $collection =  Excel::load('D:/JtecData/QUAN LY SAN XUAT/VUI/コマツインドの出荷日程  HT MỚI.xlsx', function($file) {
        //     dd($file);
        // });
        // $collection =   Excel::load('D:/JtecData/QUAN LY SAN XUAT/VUI/コマツインドの出荷日程  HT MỚI.xlsx', function($file) {
        // })->store('xls');
        // dd($collection);
        // $mytime = Carbon::now();
        // $counting_time = $mytime->diffInDays(Carbon::parse('2024-04-07 09:56:46'));
        // dd($counting_time);
        // $dfsgfdgfd= [1=>'Kích thước dây', 2=>'Mã sản phẩm', 3=>'Số dây', 4=>'Chủng loại dây',5=> 'Giá để tanshi', 6=>'Tên lot',7=> 'Tanshi A', 8=>'Đầu chuốt Tanshi A', 9=>'Tanshi B', 10=>'Đầu chuốt Tanshi B', 11=>'Nội dung cần chú ý',12=> 'Số lượng dây cắt',13=> 'Kí hiệu của lót hàng', 14=>'Tên mối nối', 15=>'Kích thước dây sau xoắn',16=> 'Cost QR',17=> 'Maku dây'];
        // foreach ($dfsgfdgfd as $key => $value) {
        //    dd($key);
        // }
        // dd(round(99.825));
        //$this->updatePermision();
       // dd(gethostbyaddr($_SERVER['REMOTE_ADDR']));
        // $this->addAsset();
    //     dd($_SERVER["HTTP_USER_AGENT"]);
        // $pc_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        //  dd(str_replace("MT-","", $pc_name));
    //     $ip = getenv('HTTP_CLIENT_IP');
    //     $ip = getenv('HTTP_CLIENT_IP');
    //    $ipconfig =   shell_exec ("ipconfig/all");
    //     echo $ipconfig.'</br>';
    //     $localIP = getHostByName(getHostName());
    //     echo 'Local IP: ' . $localIP . '<br>';

        // $wifiSSID =self::get_server_memory_usage();

       // echo 'Wi-Fi SSID: '. self::fGetMemResources();
        // $dfgdfg =trim(str_replace('_','',str_replace('/', "_\_", '//192.168.207.6/jtecdata/PDF GOP/CAM/SƠ ĐỒ CẮM/ĐẦU 1/12H/12HD010K.pdf')));
        // dd( $dfgdfg);
        // // tồn tháng 4
        //  $store = DB::connection('oracle')->table('DFW_Z20F')
        //     ->where('場所C', 'like', '0111%')
        //     ->where('品目K', 'like', '7'.'%')
        //     ->where('品目C', 'like', 'AVS2R%')->orderBy('品目C')->orderBy('年月度','desc')->first();
        //  dd($store);
        // Nhập và Xuất
        // $store = DB::connection('oracle')->table('DFW_Z30F')
        // ->where('場所C', 'like', '0111%')
        // ->where('品目K', 'like', '7'.'%')
        // ->where('在庫受払日', 'like', Carbon::now()->format('Y/m').'%')
        // ->where('新規登録日', 'like', Carbon::now()->format('Y/m').'%')
        // ->where('品目C', 'like', 'AVS2R%')->orderBy('品目C')->orderBy('新規登録日','desc')->get();
        //  dd($store);

        // Nhập ok
        //  $store = DB::connection('oracle')->table('DFW_H30F')
        // ->where('在庫場所C', 'like', '0111%')
        // ->where('品目K', 'like', '7'.'%')
        // ->where('品目C', 'like', 'AVS2R%')->orderBy('品目C')->orderBy('新規登録日','desc')->limit(100)->get();
        //  dd($store);
        // $accessory = Accessory::where('location_k', 7)->orderBy('id')->limit(100)->get();
        // foreach ($accessory as $key => $value) {
        //     RedisHelper::queueSet('inventory_accessory', $value);
        // }
        // $date =explode("/",'20/09/1985');
        // dd(  'R_'.now()->format('Ymdhis'));
        // $this->add_employee_to_department();
        // $this->add_user_and_pass();
       // $this->remaneTable();
        // $this->add_employeeTableId_to_admin_table_employee_id();
        // $this->addEmployee();
        // $this->updateBeginDate();
        // $this->updateType();
        // $this->updateMission();
        //$this->updateScoresAndStatus();
        // $dsgfg= Exam::all();
        // return (new ExamExport( $dsgfg))->download('exam.xlsx');
        //return Exam::query()->get()->downloadExcel('query-download.xlsx')->allFields();

        // $this->addEmployee();
        //dd($fruits);
        // mảng cần tìm
        //$key=array_search("R", array_column(json_decode(json_encode($fruits),TRUE), 'answer'));
        //dd($fruits[$key]);
        //unset($fruits[$key]);
        //$firstThreeElements = array_slice($fruits, 0, 3);
        // dd($firstThreeElements);

        // hàm đảo đáp án
        // $fruits = ArrayHelper::arrayExamPd();
        // $arrayFiltered = array_filter($fruits, fn($element) => $element['answer'] == "R");
        // $array_answer = array_column($fruits, 'answer');
        // $arrayFiltered = array_filter($array_answer, fn($element) => $element != "R");
        // $firstThreeElements = array_slice($arrayFiltered, 0, 3);
        // array_push($firstThreeElements, "R");
        // shuffle($firstThreeElements);
        // dd(count($arrayFiltered));
    }
    public function add_employee_to_department()
    {
        $employees = Employee::all();
        foreach ($employees as $employee) {
            $department = EmployeeDepartment::create([
                'employee_id' => $employee->id,
                'department_id' => 1,
                'positions' => 0,
                'created_by' => 1,
            ]);
            // return $this->success(compact('department'));
        }
    }

    public function add_employeeTableId_to_admin_table_employee_id()
    {
        $employees = Employee::all();
        foreach ($employees as $employee) {
            // echo $employee->code;
            $admin = Admin::Where('username', $employee->code)->first();
            // echo $admin;
            if ($admin) {
                $admin->employee_id = $employee->id;
                $admin->save();
            }
        }
    }
    public function add_user_and_pass()
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            $admin = Admin::Where('username', $employee->code)->first();
            if (!$admin) {
                $admin = new Admin();
                $admin->first_name = $employee->first_name;
                $admin->last_name = $employee->last_name;
                $admin->username = $employee->code;
                $admin->email = @$employee->email ? @$employee->email : $employee->code . 'exam@exam.com';
                $admin->password = Hash::make($employee->code);
                $admin->status = 1;
                $admin->created_at = Carbon::now();
                $admin->created_by = Auth::id();
                $admin->updated_at = Carbon::now();
                $admin->save();

                // Assign Roles
                $admin->assignRole('Worker');
            }
        }
    }

    public function remaneTable()
    {
        if (Schema::hasColumn('signature_submissions', 'sign_instead')) {
            Schema::table('signature_submissions', function (Blueprint $table) {
                $table->renameColumn('sign_instead', 'signature_id');
            });
        }

        if (Schema::hasColumn('employees', 'image')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->renameColumn('image', 'avatar');
            });
        }
    }
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $emp = Employee::where('code', $request->manhanvien)->first();
            if ($emp) {
                $emp_dept = EmployeeDepartment::where('employee_id', $emp->id)->first();
            }
            $groupQuestion =$request->type != 2 ? ArrayHelper::arrayExamPd()[$request->type] : ArrayHelper::groupQuestion1();
            if (!$request->exists('answer')) {
                return $this->error(['error', 'chưa chọn đáp án']);
            }
            $results = 0;
            $fail_aws = [];
            $totalQuestion = 0;
            $scores = 0;
            foreach ($groupQuestion['data'] as $questionItem) {
                $arrayExam = $questionItem['questions'];
                if($questionItem['random'] > 0){
                    $totalQuestion += $questionItem['random'];
                }else{
                    $totalQuestion += count($arrayExam);
                }
                $check_multiple_answer = 1;

                foreach ($request->answer as $key => $item) {
                    $_answer = 0;
                    $array_answer = array_filter($arrayExam, fn ($element) => $element['id'] == $key);
                    $array_answer = current($array_answer);
                    if ($array_answer && count($array_answer['multiple_answer']) > 0) {
                        $multiple_answer = $array_answer['multiple_answer'];
                        $point_answer = $array_answer['point'] / count($array_answer['multiple_answer']);
                        if($array_answer['type_input'] == 'checkbox'){
                            foreach ($item as $key_2 => $value_2) {
                                 $check_answer = array_filter($multiple_answer, fn ($element) => $element == $value_2);
                                if (count($check_answer) == 0) {
                                    $check_multiple_answer = 0;
                                 }else{
                                    $scores += $point_answer;
                                 }
                            }

                        }else{
                            if(count($multiple_answer) > 0){
                                foreach ($multiple_answer as $key1 => $value1) {
                                    if ($item[$value1] != ($key1)) {
                                        $check_multiple_answer = 0;
                                    }else{
                                        $scores += $point_answer;
                                     }
                                }
                            }
                        }
                        if ($check_multiple_answer == 1) {
                            $results++;
                            $_answer = 1;
                            // $scores += $array_answer['point'];
                        }
                    } elseif ($array_answer && $array_answer['answer'] == $item) {
                        $results++;
                        $_answer = 1;
                        $scores += $array_answer['point'];
                    }

                    $fail_aws[$key] = [
                        'id' => $key,
                        'result' => $_answer,
                        'answer' => $item,
                        'code' => $request->manhanvien,
                        'name' => $emp ? $emp->first_name . ' ' . $emp->last_name : $request->manhanvien,
                    ];
                }
            }

            $mytime = Carbon::now();
            $counting_time = $mytime->diffInSeconds(Carbon::parse($request->count_timer));
            $cycle_name = Carbon::parse($request->ngaykiemtra)->format('mY');
            $ngaykiemtra = Carbon::parse($request->ngaykiemtra);

            $conversionDates = ArrayHelper::conversionDate();
            $examinations = 1;
            $date_examinations = [];
            foreach ($conversionDates as $key => $value) {
                if (($value[0] <= $ngaykiemtra->day) && ($ngaykiemtra->day <= $value[1])) {
                    $date_examinations[] = $ngaykiemtra->year . '-' . $ngaykiemtra->month . '-' . $value[0];
                    $date_examinations[] = $value[1] == 100 ? $ngaykiemtra->endOfMonth()->format('Y-m-d') : $ngaykiemtra->year . '-' . $ngaykiemtra->month . '-' . $value[1];
                    $examinations = $key;
                }
            }
            $check_status = Exam::where(['code' => $request->manhanvien, 'cycle_name' => $cycle_name, 'examinations' => $examinations, 'status' => 1,'type'=>$request->type])->count();
            if ($check_status > 0) {
                return $this->success(['message' => 'Bạn đã thi đạt. Hãy chờ đợt thi tiếp theo']);
            }
            $exam_status_fail = Exam::where(['code' => $request->manhanvien, 'cycle_name' => $cycle_name, 'examinations' => $examinations, 'status' => 0,'type'=>$request->type])->orderBy('id', 'desc')->first();
            if ($exam_status_fail) {
                $check_time = $mytime->diffInSeconds($exam_status_fail->created_at);
                if ($check_time < 86400) {
                    return $this->success(['message' => 'Bạn thi chưa đạt lúc:<br>[' . $exam_status_fail->created_at . ']<br>Sau 2 ngày bạn mới có thể thi lại.']);
                }
            }

            $mission = Exam::where(['code' => $request->manhanvien, 'cycle_name' => $cycle_name, 'examinations' => $examinations,'type'=>$request->type])->count();

            $exam = Exam::create([
                'name' => $emp ? $emp->first_name . ' ' . $emp->last_name : $request->manhanvien, //tên nhân viên
                'code' => $request->manhanvien, // mã nhân viên
                'sub_dept' => @$emp_dept ? @$emp_dept->department_id : 0, // công đoạn
                'cycle_name' => $cycle_name, // kỳ thi
                'create_date' => $request->ngaykiemtra, // ngày làm bài thi
                'results' => $results, // tổng số câu trả lời đúng
                'total_questions' => $totalQuestion, // tổng số câu hỏi
                'counting_time' => gmdate('i:s', $counting_time), // thời gian làm bài
                'limit_time' => $groupQuestion['time'], // tổng số câu hỏi
                'data' => json_encode($request->answer), // tổng số câu hỏi
                'status' => $scores >= $groupQuestion['scores'][0] ? 1 : 0, // 0:chưa duyệt,1:đã duyệt
                'mission' => $mission + 1, // số lần thi
                'scores' => $scores, // điểm thi
                'examinations' => $examinations, // đợt thi
                'date_examinations' => json_encode($date_examinations), // khoảng thời gian thi
                'type' => $request->type,
                'fail_aws' => json_encode($fail_aws),
                'newbie' => @$request->newbie??null
            ]);
            return $this->success(compact('exam', 'groupQuestion'));
        } catch (\Exception $e) {
            return $this->error(['error'=> $e->getLine(),'sdfdsf44'=>$e->getTraceAsString()]);
        }
    }
    public function updateType()
    {
        Exam::where('type', 0)->update(['type' => 1]);
        // echo 'thanhf cong';
    }
    public function addAsset()
    {
        $devicesList = ArrayHelper::devicesList();
        foreach ($devicesList as $key => $value) {
            $asset = Asset::where('name',trim($value['name']))->first();
            $asset->model= $value['model'];
            $asset->color= $value['color'];
            $asset->save();
        }
        dd('thành công.');
    }
    public function storeNew(Request $request)
    {
        // dd($request->all());
        $emp = Employee::where(['code' => $request->manhanvien])->first();
        $emp_dept = EmployeeDepartment::where('employee_id', $emp->id)->first();
        $groupQuestion = ArrayHelper::groupQuestion();
        $results = 0;
        $scores = 0;
        $totalQuestion = 0;
        foreach ($groupQuestion as $questionItem) {
            $arrayExam = $questionItem['question'];
            $totalQuestion += $questionItem['quantity_question'];
            foreach ($request->answer as $key => $item) {
                $array_answer = array_filter($arrayExam, fn ($element) => $element['id'] == $key);
                if (count($array_answer) > 0 && current($array_answer)['answer'] == $item) {
                    $results++;
                    $scores = $scores + $questionItem['point'];
                }
            }
        }
        $mytime = Carbon::now();
        $counting_time = $mytime->diffInSeconds(Carbon::parse($request->count_timer));
        $cycle_name = Carbon::parse($request->ngaykiemtra)->format('mY');
        $ngaykiemtra = Carbon::parse($request->ngaykiemtra);

        $conversionDates = ArrayHelper::conversionDate();
        $examinations = 1;
        $date_examinations = [];
        foreach ($conversionDates as $key => $value) {
            if (($value[0] <= $ngaykiemtra->day) && ($ngaykiemtra->day <= $value[1])) {
                $date_examinations[] = $ngaykiemtra->year . '-' . $ngaykiemtra->month . '-' . $value[0];
                $date_examinations[] = $value[1] == 100 ? $ngaykiemtra->endOfMonth()->format('Y-m-d') : $ngaykiemtra->year . '-' . $ngaykiemtra->month . '-' . $value[1];
                $examinations = $key;
            }
        }
        $mission = Exam::where(['code' => $request->manhanvien, 'cycle_name' => $cycle_name, 'examinations' => $examinations])->count();
        try {
            $exam = Exam::create([
                'name' => $emp ? $emp->first_name . ' ' . $emp->last_name : $request->manhanvien, //tên nhân viên
                'code' => $request->manhanvien, // mã nhân viên
                'sub_dept' => @$emp_dept ? @$emp_dept->department_id : 0, // công đoạn
                'cycle_name' => $cycle_name, // kỳ thi
                'create_date' => $request->ngaykiemtra, // ngày làm bài thi
                'results' => $results, // tổng số câu trả lời đúng
                'total_questions' => $totalQuestion, // tổng số câu hỏi
                'counting_time' => gmdate('i:s', $counting_time), // thời gian làm bài
                'limit_time' => '05:00', // tổng số câu hỏi
                'data' => json_encode($request->answer), // tổng số câu hỏi
                'status' => $scores > 79 ? 1 : 0, // 0:chưa duyệt,1:đã duyệt
                'mission' => $mission + 1, // số lần thi
                'scores' => $scores, // điểm thi
                'examinations' => $examinations, // đợt thi
                'date_examinations' => json_encode($date_examinations), // khoảng thời gian thi
                'type' => $request->type,
            ]);
            return $this->success(compact('exam'));
        } catch (\Exception $e) {
            return $this->error(['error', $e->getMessage()]);
        }
    }

    public function addEmployee()
    {
        $employee = [
            10142 => "Đàm Thị Hương",
            10352 => "Nguyễn Thị Kim Hoa",
            130206 => "Thịnh Thị Thái",
            130207 => "Ngô Thị Đậu",
            130323 => "Hoàng Thị Tình",
            130907 => "Nguyễn Thị Mỹ Hưởng",
            130947 => "Vũ Thị Yến",
            130976 => "Hoàng Thị Quyết",
            131102 => "Nguyễn Thị Anh",
            131107 => "Ngô Thị Hồng Nhung",
            131108 => "Phan Thị Loan",
            140204 => "Đinh Thị Trì",
            140303 => "Nguyễn Thị Lan",
            140322 => "Nguyễn Thị Lâm",
            140326 => "Trần Thị Thùy",
            140328 => "Lê Thị Hoa",
            140416 => "Dương Thị Dung",
            140519 => "Lâm Thị Thu Hằng",
            140787 => "Thân Thị Mai",
            1407100 => "Nguyễn Thị Minh Ngọc",
            1410119 => "Trần Thị Thu Thảo",
            141182 => "Phan Thị Loan",
            141197 => "Bùi Thị Nhâm",
            151106 => "Lưu Thị Phương",
            160417 => "Đoàn Thị Hạnh",
            160711 => "Hồ Thị Lê",
            160886 => "Nguyễn Minh Hòa",
            160933 => "Đặng Thị Hồng",
            1609182 => "Ngô Thị Huyền Trang",
            161125 => "Phùng Thị Thương",
            161176 => "Đinh Thị Nhã",
            1702134 => "Vũ Thị Như Quỳnh",
            170320 => "Nguyễn Thị Lý",
            170485 => "Vũ Thị Hương",
            170494 => "Lê Thị Kiền",
            170495 => "Ngô Thị Hiểu",
            170560 => "Bùi Trọng Doanh",
            170610 => "Đàm Văn Ninh",
            170648 => "Phùng Thị Phượng",
            170665 => "Lê Thị Yến",
            170751 => "Cao Thị Ánh Nguyệt",
            1707127 => "Trịnh Thị Thuần",
            170866 => "Đặng Khánh Linh",
            171002 => "Đinh Thị Hương",
            171050 => "Nguyễn Thị Tính",
            180371 => "Nguyễn Thị Hằng Nga",
            180393 => "Vi Thanh Sơn",
            1803145 => "Bùi Văn Anh",
            180817 => "Đinh Hoàng Giang",
            180823 => "Nguyễn Ngọc Ánh",
            180906 => "Võ Thị Hồng Nhung",
            181044 => "Nguyễn Văn Việt",
            181091 => "Nguyễn Thị Hợp",
            190457 => "Lê Thị Thúy Hồng",
            190529 => "Hoàng Thế Đạt",
            210301 => "Trần Thị Như Quỳnh",
            2103129 => "Nguyễn Văn Thanh",
            2103229 => "Vũ Thị Hằng",
            211059 => "Nguyễn Văn Tuấn",
            211068 => "Nguyễn Văn Tuân",
            220440 => "Lê Văn Chương",
            220454 => "Đoàn Đức Võ",
            2209109 => "Nguyễn Văn Tuyền",
            2211158 => "Nguyễn Thị Thuỳ",
            230209 => "Nguyễn Văn Nam",
            2302221 => "Lục Văn Châu",
            230317 => "Võ Văn Thật",
            230322 => "Đoàn Ngọc Oanh",
            230394 => "Phạm Văn Nam",
            2303152 => "Lê Đình Văn",
            230573 => "Lưu Thị Hương Ly",
            230867 => "Trần Thị Thảo",
            231164 => "Hoàng Thị Yên",
            231166 => "Đỗ Thị Nhung",
            231218 => "Nguyễn Văn Quân",
            240317 => "Nguyễn Thị Thúy",
            // 24031656 =>    "Nguyễn Thị Thúy 123",
            // 240341656 =>    "Nguyễn Thị Thúy 1235",
            240405 => "Nguyễn Thị Ngọc Lan",
            240406 => "Nguyễn Thị Thu",
            240407 => "Lê Thị Thanh Thủy",
            240408 => "Bùi Thị Lụa",
            240410 => "Lê Thị Thu Huyền",
            240411 => "Nguyễn Thị Trang",
            240412 => "Trương Thị Ánh",
            240415 => "Lê Ngọc Mai",
            240416 => "Nguyễn Phạm Tường Vy",
            240417 => "Đinh Thị Hằng",
            240424 => "Nguyễn Thị Nhiên",
            10006 => 'Lê Thị Lý',
            10068 => 'Nguyễn Thị Loan',
            10243 => 'Hoàng Thị Quý',
            10269 => 'Lê Thị Phương',
            10426 => 'Vũ Thị Thảo',
            10444 => 'Bùi Thị Duyên',
            130429 => 'Đào Thị Tân ',
            130749 => 'Lê Thị Lương',
            140361 => 'Lê Thị Thoan',
            140507 => 'Lý Thị Mai ',
            140526 => 'Nguyễn Thị Lan',
            140612 => 'Lê Thị Hồng Thu',
            140730 => 'Nguyễn Thị Minh Luyến',
            140773 => 'Nguyễn Thu Hoài',
            140932 => 'Trần Thị Thu Thủy',
            1409102 => 'Nguyễn Thị Phương',
            141069 => 'Phạm Thị Mai',
            151237 => 'Nguyễn Thị Kim Oanh',
            1606142 => 'Đoàn Thị Thu Hà',
            160761 => 'Nguyễn Thị Quỳnh',
            160902 => 'Lê Thị Lan',
            160905 => 'Đỗ Thị Thủy',
            160975 => 'Lê Đức Hạnh',
            160979 => 'Nguyễn Tuấn Dương',
            1609113 => 'Lê Thị Phượng',
            1609122 => 'Trần Thị Yến',
            1609160 => 'Trương Thị Miền',
            161002 => 'Ngô Thúy Hường',
            1703178 => 'Nguyễn Thị Huyên',
            170644 => 'Nguyễn Thị Phương',
            170714 => 'Lưu Thị Thu Trang',
            170872 => 'Nguyễn Thị Xuân',
            170916 => 'Lại Thị Xuân',
            170933 => 'Nguyễn Thị Vân',
            170953 => 'Nguyễn Văn Hương',
            170955 => 'Nguyễn Anh Sơn',
            171027 => 'Đặng Thị Tâm',
            171215 => 'Phạm Thị Hoa',
            180315 => 'Hoàng Nhật Tuấn',
            180330 => 'Hoàng Thị Hồng',
            180341 => 'Đặng Thị Duyên',
            180377 => 'Trần Thị Hòa',
            1803148 => 'Lê Công Tý',
            180409 => 'Bùi Thị Hoa',
            180429 => 'Đinh Thị Vân Anh',
            180502 => 'Ngô Thị Hương',
            180526 => 'Nguyễn Thị Nga',
            180529 => 'Ngô Thị Hồng Duyên',
            180573 => 'Nguyễn Thu Vân',
            180602 => 'Trịnh Thị Hà',
            180711 => 'Lê Thị Luyến',
            181231 => 'Nguyễn Thị Duyên',
            190320 => 'Hà Thị Hằng',
            190354 => 'Lê Như Đức',
            190364 => 'Trương Mai Phương',
            190366 => 'Ngô Thị Loan',
            190451 => 'Nguyễn Thị Việt Trinh',
            191101 => 'Trần Thanh Hương',
            191223 => 'Trần Thị Hằng',
            200205 => 'Phạm Thị Lan Anh',
            200906 => 'Nguyễn Vân Anh',
            200910 => 'Nguyễn Thị Thúy',
            200915 => 'Nguyễn Thị Hường',
            200918 => 'Nguyễn Thị Yến',
            200920 => 'Phạm Thị Trang',
            201201 => 'Kiều Thị Thúy Oanh',
            210317 => 'Nguyễn Thị Thảo',
            210366 => 'Ngô Thị Hoa',
            210369 => 'Lỗ Thị Dư',
            2103133 => 'Nguyễn Xuân Hòa',
            210431 => 'Mào Thị Nhung',
            210432 => 'Lò Thị Điểm',
            210444 => 'Bùi Văn Hòe',
            210468 => 'Dương Bích Nguyên',
            210480 => 'Nguyễn Thị Hà',
            220226 => 'Nguyễn Thị Nga',
            220306 => 'Bùi Văn Hải',
            220339 => 'Nguyễn Thị Hiền',
            220341 => 'Hoàng Thị Huyền',
            220560 => 'Nguyễn Trí Đăng',
            220626 => 'Nguyễn Minh Tuyến',
            220634 => 'Nguyễn Thị Tuyết',
            220637 => 'Nguyễn Đình Phong',
            220715 => 'Hà Thị Thùy',
            220753 => 'Nguyễn Thị Thu Phương',
            220765 => 'Nguyễn Thị Hương',
            220881 => 'Nguyễn Thuỳ Dung',
            220988 => 'Đặng Thị Chăng',
            220990 => 'Trương Thị Mơ',
            221063 => 'Bùi Văn Phương',
            221065 => 'Nguyễn Thị Vân',
            221140 => 'Vàng Thị Bích Thu',
            2211159 => 'Nguyễn Văn Tiến',
            230201 => 'Lò Thị Thinh',
            230231 => 'Vương Thị Hồng Thanh',
            230290 => 'Đặng Minh Tiến',
            230294 => 'Hoàng Văn Nhất',
            2302112 => 'Nguyễn Văn Thuyên',
            2302326 => 'Bùi Thị Thành',
            230410 => 'NguyễnThị Hằng',
            230411 => 'Lê Thị Nhàn',
            230502 => 'Phạm Thị Thu Hương',
            230503 => 'Nguyễn Thu Thảo',
            230662 => 'Lê Việt Anh',
            230865 => 'Đỗ Thị Thu Hiền',
            230918 => 'Trần Thị Trà My',
            231007 => 'Nguyễn Quốc Trường Sơn',
            231016 => 'Nguyễn Mạnh Hưng',
            231056 => 'Hoàng Thị Biên',
            231057 => 'Hoàng Thị Mỹ Lệ',
            231215 => 'Trần Thị Ánh Ngọc',
            240207 => 'Nguyễn Thị Hiển',
            240301 => 'Hoàng Ngọc Ánh',
            240307 => 'Hà Thị Kim Cúc',
            240409 => 'Nguyễn Ngọc Lan',
            240414 => 'Trần Thị An',
            240421 => 'Bùi Thị Hương',
        ];

        foreach ($employee as $key => $value) {
            $emp = Employee::where('code', $key)->first();
            $parts = explode(" ", $value);
            if (count($parts) > 1) {
                $lastname = array_pop($parts);
                $firstname = implode(" ", $parts);
            } else {
                $firstname = $value;
                $lastname = " ";
            }
            if (!$emp) {

                $emp = Employee::create([
                    'code' => $key,
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'created_by' => 1,
                ]);
            }
            $emp->status_exam = 1;
            $emp->save();
            $admin = Admin::where('username', $key)->first();
            if (!$admin) {
                //Tạo tài khoản
                $admin = Admin::create([
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'username' => $key,
                    'email' => $key . 'exam@exam.com',
                    'password' => Hash::make($key),
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'created_by' => 1,
                    'updated_at' => Carbon::now(),
                ]);
                // Assign Roles
                $admin->assignRole('Worker');
            }
        }
    }

    public function updateScoresAndStatus()
    {
        $fdgfdgf = Exam::where('type',1)->get();
        foreach ($fdgfdgf as $key => $value) {
            $scores = round(($value->results / $value->total_questions) * 100);
            $value->update([
                'scores' => $scores + 1,
                'status' => $scores > 95 ? 1 : 0,
            ]);
        }
    }
    public function asyncDir()
    {
       $this->copyDirectory('D:/JtecData/JTEC_PD_PROGAM/CMSWeb/jtecweb','C:/xampp/htdocs/jtec-cms.local');
       echo "Files and folders copied successfully!";
    }
    public function copyDirectory($src, $dst) {
          // Open the source directory
        $dir = opendir($src);

        // Create the destination directory if it does not exist
        if (!file_exists($dst)) {
            mkdir($dst, 0755, true);
        }

        // Loop through the files in the source directory
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    // Recursively copy subdirectory
                    $this->copyDirectory($src . '/' . $file, $dst . '/' . $file);
                } else {
                    // Copy the file and overwrite if it exists
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }

        // Close the directory
        closedir($dir);
    }
    public function ImportEmpPost(Request $request)
    {
        set_time_limit(0);
        $data = Excel::toCollection(new EmpImport, request()->file('import_file'));
        foreach ($data[0] as $key => $value) {
            if ($key > 0) {
                try {
                    $emp = Employee::where('code',trim($value[0]))->first();
                    $dept = Department::where('name', $value[2])->first();
                    $emp_dept = null;
                    if (!$dept) {
                        $dept = Department::create([
                            'code' => time(),
                            'name' => $value[2],
                            'parent_id' => 0,
                            'status' => 1,
                            'created_by' => 1,
                        ]);
                    }
                    if (!$emp) {
                        echo 'Thành công!'.trim($value[0]);

                        $parts = explode(" ", $value[1]);
                        if (count($parts) > 1) {
                            $lastname = array_pop($parts);
                            $firstname = implode(" ", $parts);
                        } else {
                            $firstname = $value;
                            $lastname = " ";
                        }
                        // Tạo nhân viên
                        // $begin_date_company =@$value[5]? explode("/", $value[5]):;
                        // $birthday = explode("/", $value[3]);
                        $emp = Employee::create([
                            'code' =>trim($value[0]),
                            'first_name' => $firstname,
                            'last_name' => $lastname,
                            // 'begin_date_company' => $begin_date_company[2] . '-' . $begin_date_company[1] . '-' . $begin_date_company[0],
                            'status' => 1,
                            'created_by' => 1,
                            // 'birthday' => $birthday[2] . '-' . $birthday[1] . '-' . $birthday[0],
                            'worker' => 3,

                        ]); // Tạo một đối tượng Employee mới
                        $emp_dept = EmployeeDepartment::where('employee_id', $emp->id)->where('department_id',$dept->id)->first();
                        if(!$emp_dept){
                            EmployeeDepartment::create([
                                'employee_id' => $emp->id,
                                'department_id' => $dept->id,
                                'created_by' => 1,
                            ]);
                        }
                        $admin = Admin::where('username',trim($value[0]))->first();
                        if(!$admin){
                            //Tạo tài khoản
                            $admin = Admin::create([
                                'first_name' => $firstname,
                                'last_name' => $lastname,
                                'username' =>trim($value[0]),
                                'email' => $value[0] . 'exam@exam.com',
                                'password' => Hash::make($value[0]),
                                'status' => 1,
                                'created_at' => Carbon::now(),
                                'created_by' => 1,
                                'updated_at' => Carbon::now(),
                                'employee_id'=>$emp->id
                            ]);
                            // Assign Roles
                            $admin->assignRole('Worker');
                        }
                    }else{
                        $emp_dept = EmployeeDepartment::where('employee_id', $emp->id)->where('department_id',$dept->id)->first();
                    }
                    if (!$emp_dept) {
                        EmployeeDepartment::create([
                            'employee_id' => $emp->id,
                            'department_id' => $dept->id,
                            'created_by' => 1,
                        ]);
                    }

                } catch (\Exception $e) {
                    echo $e->getMessage().$e->getLine();
                    dd(1);
                }
            }
        }
    }
    public function ImportEmpPostOld(Request $request)
    {
        set_time_limit(0);
        $data = Excel::toCollection(new EmpImport, request()->file('import_file'));
        $sdfsd=[];
        foreach ($data[0] as $key => $value) {
                try {
                    $__code= trim($value[1]);
                    $asset =  Accessory::where('code',$__code)->first();
                    if($asset){
                        $asset->material_norms = trim($value[4]); //định mức
                        $asset->save();
                    }
                    echo 'Thành công!';
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    dd(1);
                }
        }
        // RedisHelper::setKey('inventory_accessorysdfd',json_encode($sdfsd) );
        // dd( $sdfsd);
    }

    public function ImportEmpPostOne(Request $request)
    {
        set_time_limit(0);
        $data = Excel::toCollection(new EmpImport, request()->file('import_file'));
        $sdfsd=[];

        foreach ($data[0] as $key => $value) {
            if($key > 0){
                $accessory_dept=[];
                try {
                    $__code= trim($value[1]);
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
                        'note_type'=> trim($value[2]),
                        'type'=>2,
                        'unit'=>trim($value[3]),
                    ]);
                    echo 'Thành công!';

                } catch (\Exception $e) {
                    echo $e->getMessage();
                    dd(1);
                }
            }

        }
    }

    public function updateCreateDate()
    {
        $fdgfdgf = Employee::all();
        foreach ($fdgfdgf as $key => $value) {

            $fdgf = Exam::select('id')->where('code', $value->code)
                ->where('cycle_name', 42024)
                ->where('examinations', 1)
                ->where('status', 1)
                ->groupBy('code')->count();
            echo $fdgf . '============</br>';
            if ($fdgf > 1) {
                $fdgf345 = Exam::select('code', DB::raw('MAX(id) as _id'))->where('code', $value->code)
                    ->where('cycle_name', 42024)
                    ->where('examinations', 1)
                    ->where('status', 1)
                    ->groupBy('code')
                    ->first();
                $fdgdfg = Exam::find($fdgf345->_id);
                $fdgdfg->update([
                    'examinations' => 2,
                    'create_date' => '2024-04-19',
                    'mission' => 1, // số lần thi
                ]);
                echo $fdgdfg->id . ' code ' . $fdgdfg->code . '</br>';
            }
        }
    }
    public function updateExaminations()
    {
        $fdgfdgf = Exam::all();
        foreach ($fdgfdgf as $key1 => $value1) {
            $conversionDates = ArrayHelper::conversionDate();
            $examinations = 0;
            $date_examinations = [];
            $ngaykiemtra = Carbon::parse($value1->create_date);
            foreach ($conversionDates as $key => $value) {
                if (($value[0] <= $ngaykiemtra->day) && ($ngaykiemtra->day <= $value[1])) {
                    $date_examinations[] = $ngaykiemtra->year . '-' . $ngaykiemtra->month . '-' . $value[0];
                    $date_examinations[] = $value[1] == 100 ? $ngaykiemtra->endOfMonth()->format('Y-m-d') : $ngaykiemtra->year . '-' . $ngaykiemtra->month . '-' . $value[1];
                    $examinations = $key;
                }
            }
            $value1->update([
                'examinations' => $examinations, // điểm thi
                'date_examinations' => json_encode($date_examinations), // điểm thi
            ]);
        }
    }
    public function updateMission()
    {
        $fdgfdgf = Exam::orderBy('code')->orderBy('cycle_name')->orderBy('created_at')->get();
        $mission = 1;
        $code = 0;
        foreach ($fdgfdgf as $key => $value) {
            if ($value->code != $code) {
                $code = $value->code;
                $mission = 1;
            } else {
                $mission++;
            }
            $value->update([
                'mission' => $mission,
            ]);
        }
    }
    public function updateCode()
    {
        $fdgfdgf = Exam::all();
        $employee = [
            10142 => "Đàm Thị Hương",
            10352 => "Nguyễn Thị Kim Hoa",
            130206 => "Thịnh Thị Thái",
            130207 => "Ngô Thị Đậu",
            130323 => "Hoàng Thị Tình",
            130907 => "Nguyễn Thị Mỹ Hưởng",
            130947 => "Vũ Thị Yến",
            130976 => "Hoàng Thị Quyết",
            131102 => "Nguyễn Thị Anh",
            131107 => "Ngô Thị Hồng Nhung",
            131108 => "Phan Thị Loan",
            140204 => "Đinh Thị Trì",
            140303 => "Nguyễn Thị Lan",
            140322 => "Nguyễn Thị Lâm",
            140326 => "Trần Thị Thùy",
            140328 => "Lê Thị Hoa",
            140416 => "Dương Thị Dung",
            140519 => "Lâm Thị Thu Hằng",
            140787 => "Thân Thị Mai",
            1407100 => "Nguyễn Thị Minh Ngọc",
            1410119 => "Trần Thị Thu Thảo",
            141182 => "Phan Thị Loan",
            141197 => "Bùi Thị Nhâm",
            151106 => "Lưu Thị Phương",
            160417 => "Đoàn Thị Hạnh",
            160711 => "Hồ Thị Lê",
            160886 => "Nguyễn Minh Hòa",
            160933 => "Đặng Thị Hồng",
            1609182 => "Ngô Thị Huyền Trang",
            161125 => "Phùng Thị Thương",
            161176 => "Đinh Thị Nhã",
            1702134 => "Vũ Thị Như Quỳnh",
            170320 => "Nguyễn Thị Lý",
            170485 => "Vũ Thị Hương",
            170494 => "Lê Thị Kiền",
            170495 => "Ngô Thị Hiểu",
            170560 => "Bùi Trọng Doanh",
            170610 => "Đàm Văn Ninh",
            170648 => "Phùng Thị Phượng",
            170665 => "Lê Thị Yến",
            170751 => "Cao Thị Ánh Nguyệt",
            1707127 => "Trịnh Thị Thuần",
            170866 => "Đặng Khánh Linh",
            171002 => "Đinh Thị Hương",
            171050 => "Nguyễn Thị Tính",
            180371 => "Nguyễn Thị Hằng Nga",
            180393 => "Vi Thanh Sơn",
            1803145 => "Bùi Văn Anh",
            180817 => "Đinh Hoàng Giang",
            180823 => "Nguyễn Ngọc Ánh",
            180906 => "Võ Thị Hồng Nhung",
            181044 => "Nguyễn Văn Việt",
            181091 => "Nguyễn Thị Hợp",
            190457 => "Lê Thị Thúy Hồng",
            190529 => "Hoàng Thế Đạt",
            210301 => "Trần Thị Như Quỳnh",
            2103129 => "Nguyễn Văn Thanh",
            2103229 => "Vũ Thị Hằng",
            211059 => "Nguyễn Văn Tuấn",
            211068 => "Nguyễn Văn Tuân",
            220440 => "Lê Văn Chương",
            220454 => "Đoàn Đức Võ",
            2209109 => "Nguyễn Văn Tuyền",
            2211158 => "Nguyễn Thị Thuỳ",
            230209 => "Nguyễn Văn Nam",
            2302221 => "Lục Văn Châu",
            230317 => "Võ Văn Thật",
            230322 => "Đoàn Ngọc Oanh",
            230394 => "Phạm Văn Nam",
            2303152 => "Lê Đình Văn",
            230573 => "Lưu Thị Hương Ly",
            230867 => "Trần Thị Thảo",
            231164 => "Hoàng Thị Yên",
            231166 => "Đỗ Thị Nhung",
            231218 => "Nguyễn Văn Quân",
            240317 => "Nguyễn Thị Thúy",
        ];
        foreach ($fdgfdgf as $key => $value) {
            foreach ($employee as $key1 => $value1) {
                if ($value1 == $value->name) {
                    $value->update([
                        'code' => $key1,
                    ]);
                }
            }
        }
    }
    public function updateBeginDate()
    {
        $fdgfdgf = Employee::all();
        $employee = [
            240317 => "11/3/2024",
            240405 => "16/4/2024",
            240406 => "16/4/2024",
            240407 => "16/4/2024",
            240408 => "16/4/2024",
            240410 => "22/4/2024",
            240411 => "22/4/2024",
            240412 => "22/4/2024",
            240415 => "22/4/2024",
            240416 => "22/4/2024",
            240417 => "22/4/2024",
            240424 => "22/4/2024",
        ];
        foreach ($fdgfdgf as $key => $value) {
            foreach ($employee as $key1 => $value1) {
                if ($key1 == $value->code) {
                    $beginDate = Carbon::createFromFormat('d/m/Y', $value1);
                    $value->update([
                        'begin_date_company' => $beginDate->format('Y-m-d'),
                    ]);
                }
            }
        }
    }
}
