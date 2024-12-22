<?php

namespace App\Http\Controllers\Backend;

use App\Exports\OrderExport;
use App\Exports\PrintAccessoryExport;
use App\Helpers\ArrayHelper;
use App\Helpers\RedisHelper;
use App\Http\Controllers\Controller;
use App\Models\Accessory;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\LogImport;
use App\Models\Required;
use App\Models\SignatureSubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class VPPController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['ip_client'] = $_SERVER['REMOTE_ADDR'];
        $data['device'] = $_SERVER['HTTP_USER_AGENT'];
        $data['uuid'] = Str::uuid()->toString();
        $data['current_time'] = Carbon::now();
        $user = Auth::user();
        $employee = @$user->employee;
        if(!$employee){
            session()->flash('warning', "Hãy liên hệ quản trị để được thao tác");
            return redirect()->route('admin.requireds.create');
        }
        $configData = ArrayHelper::formTypeJobs()[ArrayHelper::from_type_rquired_accessory];
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['filter'] = $request->all();
        $curentDate = $request->search_date ? $request->search_date : Carbon::now()->format('d-m-Y');
        $data['filter']['search_date'] = $curentDate;
        $data['to_dept'] = $configData['to_dept'];
        $data['from_dept'] = $configData['from_dept'];
        $employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->whereIn('department_id',$data['from_dept'])->first();
        if(!$employeeDepartment){
            return redirect()->back()->with('warning','Hãy liên lạc với quản trị viên để được hỗ trợ');
        }
        $__employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->first();
        $type = @$request->type;
        $_query = Required::where('from_type', ArrayHelper::from_type_rquired_accessory)->where('required_department_id',$__employeeDepartment->department_id)->whereDate('created_at',Carbon::parse($curentDate)->format('Y-m-d'))->where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->required_department_id) && $request->required_department_id != null) {
                $query->where('required_department_id', $request->required_department_id);
            }
            if (isset($request->machine) && $request->machine != null) {
                $query->where('pc_name', $request->machine);
            }
        });
        if(isset($request->type) && $request->type != null){
            if($type == 111){
                $_query->where('type','<=', 1);
            }else if($type == 112){
                $_query->where('type','>=', 2);
                $_query->where('type', '<=', 4);
            }else{
                $_query->where('type', $type);
            }
        }
        $_query->orderBy('updated_at', 'desc');
        $data['lists'] = $_query->get();
        $data['groupMachineRequired'] = Required::groupMachineRequired();
        return view('backend.pages.requestVpp.index', $data);
    }
    public function index_confirm(Request $request)
    {
        $data['ip_client'] = $_SERVER['REMOTE_ADDR'];
        $data['device'] = $_SERVER['HTTP_USER_AGENT'];
        $data['uuid'] = Str::uuid()->toString();
        $data['current_time'] = Carbon::now();
        $user = Auth::user();
        $employee = @$user->employee;
        if(!$employee){
            session()->flash('warning', "Hãy liên hệ quản trị để được thao tác");
            return redirect()->route('admin.requireds.create');
        }
        $configData = ArrayHelper::formTypeJobs()[ArrayHelper::from_type_rquired_accessory];
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['filter'] = $request->all();
        $curentDate = $request->search_date ? $request->search_date : Carbon::now()->format('d-m-Y');
        $data['filter']['search_date'] = $curentDate;
        $data['to_dept'] = $configData['to_dept'];
        $data['from_dept'] = $configData['from_dept'];
        $employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->whereIn('department_id',$data['from_dept'])->first();
        if(!$employeeDepartment){
            return redirect()->back()->with('warning','Hãy liên lạc với quản trị viên để được hỗ trợ');
        }
        $__employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->first();
        $type = $request->type;
        $_query = Required::whereHas('signature_Submission',function ($query) use ($employee) {
            $query->where('status',0)
                  ->whereRaw('JSON_CONTAINS(approve_id,"'.$employee->id.'")');
        })->where('from_type', ArrayHelper::from_type_rquired_accessory)->where('required_department_id',$__employeeDepartment->department_id)->whereDate('created_at',Carbon::parse($curentDate)->format('Y-m-d'))->where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->required_department_id) && $request->required_department_id != null) {
                $query->where('required_department_id', $request->required_department_id);
            }
            if (isset($request->machine) && $request->machine != null) {
                $query->where('pc_name', $request->machine);
            }
        });
        if($type){
            if($type == 111){
                $_query->where('type','<=', 1);
            }else if($type == 112){
                $_query->where('type','>=', 2);
                $_query->where('type', '<=', 4);
            }else{
                $_query->where('type', $type);
            }
        }
        $_query->orderBy('updated_at', 'desc');
        $data['lists'] = $_query->get();
        $data['groupMachineRequired'] = Required::groupMachineRequired();
        return view('backend.pages.requestVpp.index_confirm', $data);
    }
    public function report(Request $request)
    {
        $data['ip_client'] = $_SERVER['REMOTE_ADDR'];
        $data['device'] = $_SERVER['HTTP_USER_AGENT'];
        $data['uuid'] = Str::uuid()->toString();
        $data['current_time'] = Carbon::now();
        $user = Auth::user();
        $employee = @$user->employee;
        if(!$employee){
            return redirect()->back()->with('warning','Hãy liên lạc với quản trị viên để được hỗ trợ');
        }
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['filter'] = $request->all();
        $curentDate = $request->search_date ? $request->search_date : Carbon::now()->format('d-m-Y');
        $type = $request->type ? $request->type : 0;
        $data['filter']['search_date'] = $curentDate;
        $__employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->first();
        $data['lists'] = Required::where('from_type', ArrayHelper::from_type_rquired_accessory)->where('required_department_id',$__employeeDepartment->department_id)->where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->from_date) && $request->from_date != null) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $query->whereDate('created_at','>=', $from_date);
            }
            if (isset($request->to_date) && $request->to_date != null) {
                $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('created_at','<=',$to_date);
            }
            if (isset($request->required_department_id) && $request->required_department_id != null) {
                $query->where('required_department_id', $request->required_department_id);
            }
        })->orderBy('updated_at', 'desc')->where('status', 1)->paginate($data['per_page']);

        // $data['departments'] = Department::all();
        return view('backend.pages.requestVpp.report', $data);
    }
    public function requiredWithDelete(Request $request)
    {
        $data['ip_client'] = $_SERVER['REMOTE_ADDR'];
        $data['device'] = $_SERVER['HTTP_USER_AGENT'];
        $data['uuid'] = Str::uuid()->toString();
        $data['current_time'] = Carbon::now();
        $user = Auth::user();
        $employee = @$user->employee;
        if(!$employee){
            return redirect()->back()->with('warning','Hãy liên lạc với quản trị viên để được hỗ trợ');
        }
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['filter'] = $request->all();
        $curentDate = $request->search_date ? $request->search_date : Carbon::now()->format('d-m-Y');
        $data['filter']['search_date'] = $curentDate;
        $__employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->first();

        $data['lists'] = Required::withTrashed()->where('from_type', ArrayHelper::from_type_rquired_accessory)->where('required_department_id',$__employeeDepartment->department_id)->where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->from_date) && $request->from_date != null) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $query->whereDate('created_at','>=', $from_date);
            }
            if (isset($request->to_date) && $request->to_date != null) {
                $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('created_at','<=',$to_date);
            }
            if (isset($request->required_department_id) && $request->required_department_id != null) {
                $query->where('required_department_id', $request->required_department_id);
            }
        })->whereNotNull('deleted_at')->orderBy('updated_at', 'desc')->paginate($data['per_page']);

        // $data['departments'] = Department::all();
        return view('backend.pages.requestVpp.deleted', $data);
    }
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $employee = @$user->employee;
        if(!$employee){
            return redirect()->back()->with('warning','Hãy liên lạc với quản trị viên để được hỗ trợ');
        }
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['filter'] = $request->all();
        $curentDate = $request->search_date ? $request->search_date : Carbon::now()->format('d-m-Y');
        $data['filter']['search_date'] = $curentDate;
        $__employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->first();
        $type = $request->type ? $request->type : 111;
        $_query = Required::where('from_type', ArrayHelper::from_type_rquired_accessory)->where('required_department_id',$__employeeDepartment->department_id)->where(function ($query) use ($request) {

            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->required_department_id) && $request->required_department_id != null) {
                $query->where('required_department_id', $request->required_department_id);
            }

        });
        $_query->orderBy('updated_at', 'desc');
        $data = $_query->get();
        return (new OrderExport($data))->download(Carbon::now()->format('H_i_s d_m_Y_').'congdoan.xlsx');
    }
    public function exportExcelReport(Request $request)
    {
        $user = Auth::user();
        $employee = @$user->employee;
        if(!$employee){
            return redirect()->back()->with('warning','Hãy liên lạc với quản trị viên để được hỗ trợ');
        }
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['filter'] = $request->all();
        $curentDate = $request->search_date ? $request->search_date : Carbon::now()->format('d-m-Y');
        $data['filter']['search_date'] = $curentDate;
        $__employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->first();
        $type = $request->type ? $request->type : 111;
        $_query = Required::where('from_type', ArrayHelper::from_type_rquired_accessory)->where('required_department_id',$__employeeDepartment->department_id)->where(function ($query) use ($request) {

            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->type) && $request->type != null) {
                $query->where('type', $request->type);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->from_date) && $request->from_date != null) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $query->whereDate('created_at','>=', $from_date);
            }
            if (isset($request->to_date) && $request->to_date != null) {
                $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('created_at','<=',$to_date);
            }
            if (isset($request->required_department_id) && $request->required_department_id != null) {
                $query->where('required_department_id', $request->required_department_id);
            }
        });
        $_query->orderBy('updated_at', 'desc');
        $data = $_query->get();
        return (new OrderExport($data))->download(Carbon::now()->format('H_i_s d_m_Y_').'congdoan.xlsx');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('requestVpp.create')) {
            return abort(403, 'You are not allowed to access this page !');
        }
        $data['ip_client'] = $_SERVER['REMOTE_ADDR'];
        $data['device'] = $_SERVER['HTTP_USER_AGENT'];
        $data['uuid'] = Str::uuid()->toString();
        $data['current_time'] = Carbon::now();
        $pc_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $data['filter']['selecMachine']=$request->input('selecMachine',$pc_name);
        $requiredType = ArrayHelper::from_type_vpp;
        $formTypeJobs = ArrayHelper::formTypeJobs()[$requiredType];
        $user = Auth::user();
        $employee = @$user->employee;
        if(!$employee){
            return redirect()->back()->with('warning',"Hãy liên hệ quản trị để được thao tác");
        }
        if(count($formTypeJobs['from_dept']) > 0){
            $employee_department = EmployeeDepartment::where('employee_id',$employee->id)->whereIn('department_id',$formTypeJobs['from_dept'])->first();
            if(!$employee_department){
                return redirect()->back()->with('warning',"Hãy liên hệ quản trị để được thao tác");
            }
        }
        $data['machineLists'] = ArrayHelper::list_machine;
        $data['employee'] = $employee;
        $data['formTypeJobs'] = $formTypeJobs;
        $data['departments'] = Employee::get_departments_by_id($employee->id);
        $data['employeeDepartment'] = EmployeeDepartment::where('employee_id',$employee->id)->first();

        return view('backend.pages.requestVpp.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $user = Auth::user();
        $employee = @$user->employee;
        if(!$employee){
            return $this->success([
                'status'=>false,
                'message'=> 'Liên lạc quản trị để được hỗ trợ',
            ]);
        }
        $requireCode = 'R_' . now()->format('Ymdhis');
        $formTypeJobs = ArrayHelper::formTypeJobs()[ArrayHelper::from_type_rquired_accessory];
        $data['formTypeJobs'] = $formTypeJobs;
        $__code = @$request->code??$request->searchcode;
        $pc_name =  $request->selecMachine??gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $accessory = Accessory::whereRaw("BINARY code = '$__code'")->where('type',2)->first();
        if(!$accessory){
            return $this->success([
                'status'=>false,
                'message'=> 'Mã linh kiện này không tồn tại',
            ]);
        }
        if($request->quantity == 0){
            return $this->success([
                'status'=>false,
                'message'=> 'Số lượng yêu cầu phải lớn hơn 0',
            ]);
        }
        if($request->material_norms == 0){
            return $this->success([
                'status'=>false,
                'message'=> 'Linh kiện chưa có định mức.',
            ]);
        }
        try {
            DB::beginTransaction();
            $required = Required::create([
                'required_department_id' => $request->department_id,
                'code_required' => $requireCode,
                'code' => $accessory->code,
                'size' => $request->size??0,
                'type' => $request->type??0,
                'order' => $request->order??0,
                'from_type'=>ArrayHelper::from_type_rquired_accessory,
                'quantity' =>$request->usage_status == 1 ? $request->quantity : $request->quantity / $accessory->material_norms, // 1:chẵn,0:lẻ
                'remaining' => $request->quantity,
                'created_by' => $employee->id,
                'receiving_department_ids' => json_encode($formTypeJobs['to_dept']),
                'usage_status' => $request->usage_status,
                'pc_name'=>$pc_name,
                'quantity_detail'=> $request->quantity,
            ]);

            //bộ phận yêu cầu
            $_position = EmployeeDepartment::where('department_id',$request->department_id)->where('employee_id',$employee->id)->pluck('positions')->first();
            // if($formTypeJobs['from_dept']){
            //     $from_dept = $formTypeJobs['from_dept'];
            //     foreach ($from_dept as $key => $dept_id) {
                    $confirm_from_dept = $formTypeJobs['confirm_from_dept'];
                    $confirm_by_from_dept = $formTypeJobs['confirm_by_from_dept'];
                    $_confirm_by_from_dept = [$employee->id];
                    if($confirm_from_dept == 0){ // chưa duyệt
                        foreach ($confirm_by_from_dept as $key1 => $value1) {
                            $_confirm_by_from_dept = EmployeeDepartment::where('department_id',$request->department_id)->where('positions', $value1)->pluck('employee_id')->toArray();
                            if(in_array($request->type,  $formTypeJobs['confirm_by_type']) && count($_confirm_by_from_dept) == 0){
                                DB::rollBack();
                                return $this->success([
                                    'status'=>false,
                                    'message'=> 'Bộ phận chưa có người [DUYỆT].',
                                ]);
                            }
                            SignatureSubmission::create([
                                'required_id' => $required->id,
                                'department_id' =>  $request->department_id,
                                'positions' => $value1,
                                'approve_id' => json_encode($_confirm_by_from_dept),
                                'status' => in_array($request->type,  $formTypeJobs['confirm_by_type']) ? 0 : 1,
                                'signature_id' =>  in_array($request->type,  $formTypeJobs['confirm_by_type']) ? 0 : $employee->id,
                                'type'=>ArrayHelper::from_dept
                            ]);
                        }
                    }else{
                        SignatureSubmission::create([
                            'required_id' => $required->id,
                            'department_id' =>  $request->department_id,
                            'positions' => $_position, // mặc định là worker duyệt
                            'approve_id' => json_encode($_confirm_by_from_dept),
                            'status' => $formTypeJobs['confirm_from_dept'],
                            'signature_id' => $employee->id,
                            'type'=>ArrayHelper::from_dept
                        ]);
                    }
            //     }
            // }

            //bộ phận tiếp nhận

            if($formTypeJobs['to_dept']){
                $to_dept = $formTypeJobs['to_dept'];
                foreach ($to_dept as $key => $dept_id) {
                    $confirm_to_dept = $formTypeJobs['confirm_to_dept'];
                    $confirm_by_to_dept = $formTypeJobs['confirm_by_to_dept'];
                    $_confirm_by_to_dept = [];
                    if($confirm_to_dept == 0){ // chưa duyệt
                        foreach ($confirm_by_to_dept as $key1 => $value1) {
                            $_confirm_by_to_dept = EmployeeDepartment::where('department_id',$dept_id)->where('positions', $value1)->pluck('employee_id')->toArray();
                            SignatureSubmission::create([
                                'required_id' => $required->id,
                                'department_id' => $dept_id,
                                'positions' => $value1,
                                'approve_id' => json_encode($_confirm_by_to_dept),
                                'status' => 0,
                                'signature_id' => 0,
                                'type'=>ArrayHelper::to_dept
                            ]);
                        }
                    }else{
                        SignatureSubmission::create([
                            'required_id' => $required->id,
                            'department_id' => $dept_id,
                            'positions' => 0,// mặc định là worker duyệt vì auto duyệt chưa xác định => cần cập nhật lại
                            'approve_id' => json_encode($_confirm_by_to_dept),
                            'status' => $formTypeJobs['confirm_to_dept'],// status:2
                            'signature_id' => 0,
                            'type'=>ArrayHelper::to_dept
                        ]);
                    }
                }
            }
            DB::commit();
            return $this->success([
                'status'=>true,
                'message'=> "Thêm mới thành công",
                'data'=> $required,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            LogImport::create([
                'type' => 1,
                'status' => 0,
                'data' => 'required',
                'messages' => $e->getLine().'||'.$e->getMessage()
            ]);
            return $this->success([
                'status'=>false,
                'message'=> $e->getMessage(),
            ]);
        }
    }
    public function checkRequired(Request $request)
    {
        $required = Required::where('code', @$request->code??$request->searchcode)->where('status',0)->where('required_department_id',$request->department_id)->whereDate('created_at',Carbon::now()->format('Y-m-d'))->orderBy('id','desc')->first();
        if(!$required){
            return $this->success([
                'status'=>false,
                'message'=> "Không tìm thấy yêu cầu",
            ]);
        }
        $employee = $required->employee;
        $department = $required->department;
        $required->created_at = $required->created_at->format('H:i:s d-m-Y');
        // $required->department_name = $department->name;
        // $required->user_name =  @$employee->first_name . ' ' . @$employee->last_name;
        return $this->success([
            'status'=>true,
            'message'=> $required,
        ]);
    }
    public function createPrintPdf(Request $request)
    {
        $required = Required::find($request->id);
        if(!$required){
            return $this->success([
                'status'=>false,
                'message'=> "Không tìm thấy yêu cầu",
            ]);
        }

        $rs = $this->printPdf($required);
        return $this->success([
            'status'=>true,
            'message'=> $rs['message'],
        ]);

    }
    public function printPdf($required)
    {
        $html =  view('qrcode.print-accessory', ['required' => $required])->render();
        $post_fields['Html'] = $html;
        $post_fields['PrinterName'] = 'SATO CG412';
        $post_fields['Landscape'] ='false';
        $post_fields['Width'] = '730';
        $post_fields['Height'] =  '930';
        $curl_handle = curl_init('http://192.168.207.6:8092/printpdffromhtml/html-pdf');
        curl_setopt($curl_handle, CURLOPT_HEADER, 0);
        curl_setopt($curl_handle, CURLOPT_VERBOSE, 0);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($curl_handle, CURLOPT_POST, true);
        @curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post_fields);
        $returned_data = curl_exec($curl_handle);
        curl_close($curl_handle);
        if($returned_data != 'thành công'){
            return [
                'status'=>true,
                'message'=>$returned_data,
            ];
        }
        return [
            'status'=>false,
            'message'=>$returned_data,
        ];
    }
    public function complete(Request $request)
    {
        $requireds = Required::find($request->id);
        $user = Auth::user();
        $employee = @$user->employee;
        if(!$employee){
            return $this->success([
                'status'=>false,
                'message'=> "Liên hệ quản trị để được hỗ trợ!",
            ]);
        }

        if (!$requireds) {
            return $this->success([
                'status'=>false,
                'message'=>"Yêu cầu không tồn tại !",
            ]);
        }

        if (($requireds->status == 1)) {
            return $this->success([
                'status'=>false,
                'message'=>"Yêu cầu đã được thực hiện!",
            ]);
        }
        $receiving_department_ids = $requireds->receiving_department_ids;
        $employee_department = EmployeeDepartment::where('employee_id', $employee->id)->first();
        if ((!$employee_department) || !in_array($employee_department->department_id, json_decode($receiving_department_ids))) {
            return $this->success([
                'status'=>false,
                'message'=>"Bạn không có trong bộ phận (Kho xuất)!",
            ]);
        }
        $remaining = $requireds->quantity - ($this->getQuantity($requireds)+(float)$request->quantity);
        $total_confirm_form = json_decode($requireds->confirm_form);
        $confirm_form['quantity']= (float)$request->quantity;
        $confirm_form['date']= Carbon::now();
        $confirm_form['user_id']= $employee->id;
        $confirm_form['note']= $request->note;
        $total_confirm_form[]=$confirm_form;
        $requireds->confirm_form = json_encode($total_confirm_form);
        $requireds->status = $remaining <= 0 ? 1 : 0;
        $requireds->remaining =  $remaining;
        $requireds->completed_by = $employee->id;
        $requireds->date_completed = Carbon::now();
        $accessory = $requireds->accessory;
        RedisHelper::queueSet('inventory_accessory', $requireds);
        $requireds->save();
        return $this->success([
            'status'=>true,
            'message'=>'Đã thực hiện thành công !!',
        ]);
    }
    public function getQuantity($required)
    {
        $confirm_form = json_decode($required->confirm_form);
        if($confirm_form){
            $sum=0;
            foreach ($confirm_form as $key => $value) {
                $sum+=$value->quantity;
            }
            return $sum;
        }
        return 0;
    }
    public function action(Request $request)
    {
        $user = Auth::user();
        $employee = @$user->employee;
        if (!isset($employee->id)) {
            return back()->with('error', 'Bạn không có quyền duyệt hoặc bỏ duyệt');
        }
        // dd($employee_id);
        $method = $request->input('method', '');
        // dd($method);
        if ($method == 'per_page') {
            $this->per_page($request);
            return back();
        }else if ($method == 'delete') {
            if (isset($request->ids)) {
                foreach ($request->ids as $key => $value) {
                    $required = Required::find($value);
                    $required->deleted_by = @$employee->id;
                    $required->save();
                    $required->delete();
                }
            } else {
                return back()->with('error', 'Bạn phải chọn yêu cầu trước khi duyệt.');
            }
            return back()->with('success', 'Đã xóa '.count($request->ids).' bản ghi thành công!');
        }else if ($method == 'forceDelete') {
            if (isset($request->ids)) {
                foreach ($request->ids as $key => $value) {
                    $required = Required::withTrashed()->find($value);
                    $required->forceDelete();
                }
            } else {
                return back()->with('error', 'Bạn phải chọn yêu cầu trước khi duyệt.');
            }
            return back()->with('success', 'Đã xóa '.count($request->ids).' bản ghi thành công!');
        }else if ($method == 'confirm_fromdept') {
            if (isset($request->ids)) {
                // dd($request->ids);
                foreach ($request->ids as $key => $value) {
                    $signature_submissions = SignatureSubmission::where('required_id', $value)->whereRaw('JSON_CONTAINS(approve_id,"'.$employee->id.'")')->where('status', 0)->where('type', 1)->first();
                    $signature_submissions->status = 1;
                    $signature_submissions->signature_id = $employee->id;
                    $signature_submissions->save();
                }
            } else {
                return back()->with('error', 'Bạn phải chọn yêu cầu trước khi duyệt.');
            }
            return back()->with('success', 'thành công!');
        }else if ($method == 'unconfirm_fromdept') {
            if (isset($request->ids)) {
                // dd($request->ids);
                foreach ($request->ids as $key => $value) {
                    $signature_submissions = SignatureSubmission::where('required_id', $value)->whereRaw('JSON_CONTAINS(approve_id,"'.$employee->id.'")')->where('status', 1)->where('type', 1)->first();
                    $signature_submissions->status = 0;
                    $signature_submissions->signature_id =0;
                    $signature_submissions->save();
                }
            } else {
                return back()->with('error', 'Bạn phải chọn yêu cầu trước khi duyệt.');
            }
            return back()->with('success', 'thành công!');
        }else if ($method == 'confirm') {
            if (isset($request->ids)) {
                // dd($request->ids);
                foreach ($request->ids as $key => $value) {
                    $required = Required::find($value);
                    if($required && $required->status > 0){
                        $content_form = json_decode($required->content_form);
                        $content_form->confirm_by =$employee->id;
                        $content_form->confirm_date = Carbon::now()->format('H:i:s d-m-Y');
                        $required->content_form = json_encode($content_form);
                        $required->save();
                    }
                }
            } else {
                return back()->with('error', 'Bạn phải chọn yêu cầu trước khi duyệt.');
            }
            return back()->with('success', 'thành công!');
        }
        else if ($method == 'unconfirm') {
            if (isset($request->ids)) {
                // dd($request->ids);
                foreach ($request->ids as $key => $value) {
                    $required = Required::find($value);
                    if($required && $required->status > 0){
                        $content_form = json_decode($required->content_form);
                        $content_form->confirm_by = null;
                        $content_form->confirm_date = null;
                        $required->content_form = json_encode($content_form);
                        $required->save();
                    }
                }
            } else {
                return back()->with('error', 'Bạn phải chọn yêu cầu trước khi duyệt.');
            }
            return back()->with('success', 'thành công!');
        }else if ($method == 'active_check') {
            if (isset($request->ids)) {
                // dd($request->ids);
                foreach ($request->ids as $key => $value) {
                    $signature_submissions = SignatureSubmission::where('required_id', $value)->where('status', 0)->first();
                    // dd($signature_submissions);
                    if (is_null($signature_submissions) || !$signature_submissions) {
                        return back()->with('error', 'Yêu cầu này đã được duyệt hoặc bạn không có quyền duyệt');
                    }
                    if (!in_array($employee->id, json_decode($signature_submissions->approve_id))) {
                        return back()->with('error', 'Yêu cầu này đã được duyệt hoặc bạn không có quyền duyệt');
                    }
                    $signature_submissions->status = 1;
                    $signature_submissions->signature_id = $employee->id;
                    $signature_submissions->save();
                }
            } else {
                return back()->with('error', 'Bạn phải chọn yêu cầu trước khi duyệt.');
            }
            return back()->with('success', 'thành công!');
        } else if ($method == 'inactive_check') {
            if (isset($request->ids)) {
                foreach ($request->ids as $key => $value) {
                    $signature_submissions = SignatureSubmission::where('required_id', $value)->where('status', 1)->first();
                    if (is_null($signature_submissions) || !$signature_submissions) {
                        return back()->with('error', 'Yêu cầu này chưa được duyệt.');
                    }
                    if (!$signature_submissions && !in_array($employee->id, json_decode($signature_submissions->approve_id))) {
                        return back()->with('error', 'Bạn không có quyền bỏ duyệt yêu cầu này!');
                    }
                    $signature_submissions->status = 0;
                    $signature_submissions->signature_id = 0;
                    $signature_submissions->save();
                }
            } else {
                return back()->with('error', 'Bạn phải chọn yêu cầu trước khi bỏ duyệt.');
            }
            return back()->with('success', 'thành công!');
        } else if ($method == 'delete') {
            if (isset($request->ids)) {
                foreach ($request->ids as $key => $value) {
                    $count_record = Required::find($value)->delete();
                }
            }
            return back()->with('success', 'đã xóa ' . count($request->ids) . ' bản ghi');
        } else {
            return back()->with('success', 'thành công!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Required  $required
     * @return \Illuminate\Http\Response
     */
    public function show(Required $required)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Required  $required
     * @return \Illuminate\Http\Response
     */
    public function edit(Required $required)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Required  $required
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('requestVpp.create')) {
            return abort(403, 'You are not allowed to access this page !');
        }
        $required = Required::find($id);
        $username = Auth::user()->username;
        $employee = Employee::where('code', $username)->firstOrFail();
        $department = Department::where('id', $employee->process_id)->firstOrFail();
        try {
            $required->required_department_id = $department->id;
            $required->code_required = $employee->code;
            $required->save();
            session()->flash('success', "successfully.");
            return redirect()->route('admin.requireds.index');
        } catch (\Exception $e) {
            session()->flash('error', "Failed to update: " . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function requireCheckListMachineCut(Request $request)
    {
        // dd($request->all());
        $machineLists = ArrayHelper::machineList();
        // dd($machineLists);
        $key = array_search($request->selecMachine, array_column($machineLists, 'name'));
        // dd($key);
        if (is_null($request->selecMachine) || $request->selecMachine == '') {
            session()->flash('error', "Bạn phải chọn máy kiểm tra trước khi thực hiện");
            return redirect()->route('admin.checkCutMachine.create');
        }
        // dd($machineLists[$key]['type']);
        // dd($key);
        $dataTables = ArrayHelper::formTypeJobs()[$machineLists[$key]['type']]['data_table'];
        // dd($dataTables);
        $dataTablesIds = ArrayHelper::formTypeJobs()[$machineLists[$key]['type']]['confirm_by_from_dept'];
        $dataTablesType = ArrayHelper::formTypeJobs()[2];
        // dd($dataTablesType);
        $dataTables['name_machine'] = $request->selecMachine;
        $answers = $request->answer;
        // dd($answers);
        $status = 1;
        $departmentId = $dataTablesType['from_dept'];
        // dd($departmentId);
        foreach ($answers as $key => $value) {
            if ($value == 0) {
                $status = 0;
            }
            if (!is_null($value) && $value !== '') {
                $dataTables['check_list'][$key]['answer'] = $value;
            } else {
                session()->flash('error', "Bạn phải kiểm tra hết tất cả nội dụng trước khi lưu");
            }
        }
        $json_data = json_encode($dataTables, JSON_UNESCAPED_UNICODE);
        //  dd($json_data);
        // dd($request->repair_history);
        try {
            DB::beginTransaction();
            $requireCode = 'R_' . now()->format('Ymdhis');
            $required = Required::create([
                'code_required' => $requireCode,
                'created_by' => Auth::user()->employee_id,
                'content_form' => $json_data,
                'required_department_id' => $request->departmentId,
                'code' => '',
                'content' => $request->repair_history,
                'from_type' => ArrayHelper::from_type_check_machine_cut,
                'status' => $status,
                'pc_name'=>$request->selecMachine,
            ]);

            //lưu dữ liệu vào signature_submissions table database
            foreach ($dataTablesIds as $dataTablesId) {
                $emp_dept = EmployeeDepartment::whereIn('department_id', $departmentId)->where('positions', $dataTablesId)->pluck('employee_id')->toArray();
                if (count($emp_dept) == 0) {
                    DB::rollBack();
                    return redirect()->back()->with('warning', 'Bộ phận của bạn chưa có người phê duyệt.');
                }
                $signature = SignatureSubmission::create([
                    'required_id' => $required->id,
                    'department_id' => $request->departmentId,
                    // 'content',
                    'positions' => $dataTablesId,
                    'approve_id' => json_encode($emp_dept),
                    // 'status',
                ]);
            }

            DB::commit();
            session()->flash('success', "successfully.");
            return redirect()->route('admin.checkCutMachine.index');
        } catch (\Exception $e) {
            session()->flash('error', "Failed to update: " . $e->getMessage());
            DB::rollBack();
            return redirect()->back()->withInput();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Required  $required
     * @return \Illuminate\Http\Response
     */
    public function destroy(Required $required)
    {
        //
    }
    public function showDataAccessorys(Request $request)
    {
        $accessorysCode = $request->input('selectedValue');
        // $_accessory = Accessory::where('code',$accessorysCode)->first();
        $_accessory =  Accessory::where('code','like',$accessorysCode.'%')->where('type',2)->first();
        Cache::store('redis')->forget('findAccessoryByCode_'.$_accessory->code);
        return response()->json($_accessory);
    }
    public function destroyCheckCutMachine(Request $request)
    {
        $id = $request->input('id');
        if (is_null($this->user) || !$this->user->can('requestVpp.delete')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }

        $requireds = Required::find($id);
        if (is_null($requireds)) {
            session()->flash('error', "Nội dung đã được xóa hoặc không tồn tại !");
            return redirect()->route('admin.checkCutMachine.index');
        }
        $requireds->deleted_at = Carbon::now();
        $requireds->deleted_by = Auth::id();
        $requireds->save();

        session()->flash('success', 'Đã xóa bản ghi thành công !!');
        return redirect()->route('admin.checkCutMachine.edit');
    }
    public function destroyTrash(Request $request)
    {
        $user = Auth::user();
        $employee = @$user->employee;
        $required = Required::find($request->id);
        if($required->status > 0){
            return $this->success([
                'status'=>false,
                'message'=>"Yêu cầu này! Đã được xử lý!",
            ]);
        }
        if (!$required) {
            return $this->success([
                'status'=>false,
                'message'=>"Yêu cầu này! Không tồn tại!",
            ]);
        }
        $required->deleted_by = @$employee->id;
        $required->save();
        $required->delete();
        return $this->success([
            'status'=>true,
            'message'=>"Đã xóa yêu cầu thành công!",
        ]);
    }
    public function ajaxSuggestions(Request $request){
        $where[] = ['type', '=', 2];
        if ($request->selectedValue) {
            $where[] = ['code', 'like', '%' . $request->selectedValue . '%'];
            return response()->json(Accessory::searchByAll(['where' => $where]));
        }
        return response()->json(Accessory::searchByAll(['select' => ['code'],'where' => $where]));
    }
}
