<?php

namespace App\Http\Controllers\Backend;

use App\Exports\RequiredExport;
use App\Helpers\ArrayHelper;
use App\Helpers\RedisHelper;
use App\Helpers\UploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Accessory;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\Exam;
use App\Models\LogImport;
use App\Models\Required;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class WareHouseController extends Controller
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
            return redirect()->back()->with('warning','Hãy liên lạc với quản trị viên để được hỗ trợ');
        }
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['filter'] = $request->all();
        $curentDate = $request->search_date ? $request->search_date : Carbon::now()->format('d-m-Y');
        $data['filter']['search_date'] = $curentDate;
        $configData = ArrayHelper::formTypeJobs()[ArrayHelper::from_type_rquired_accessory];
        $data['to_dept'] = $configData['to_dept'];
        $employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->whereIn('department_id',$data['to_dept'])->first();
        if(!$employeeDepartment){
            return redirect()->back()->with('warning','Hãy liên lạc với quản trị viên để được hỗ trợ');
        }
        $type = $request->type ? $request->type : 111;
        $status = (isset($request->status) && $request->status != null) ? $request->status : 0;
        $_query = Required::where('from_type', ArrayHelper::from_type_rquired_accessory)->whereDate('created_at',Carbon::parse($curentDate)->format('Y-m-d'))->where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->required_department_id) && $request->required_department_id != null) {
                $query->where('required_department_id', $request->required_department_id);
            }
        });
        $_query->where('type','<=', 1);
        if (isset($request->locations) && $request->locations != null) {
            if($request->locations == 1){
                $_query->orderBy('location', 'desc');
            }else{
                $_query->orderBy('location', 'asc');
            }
        }
        if ($status == 0 || $status == 1) {
            $_query->where('status', $status);
        }
        $_query->orderBy('id', 'desc');
        $_query->orderBy('updated_at', 'desc');

        $data['lists'] = $_query->paginate(400);
        $data['departments'] = Department::all();
        return view('backend.pages.warehouses.index', $data);
    }
    public function index_ong(Request $request)
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
        $configData = ArrayHelper::formTypeJobs()[ArrayHelper::from_type_rquired_accessory];
        $data['to_dept'] = $configData['to_dept'];
        $employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->whereIn('department_id',$data['to_dept'])->first();
        if(!$employeeDepartment){
            return redirect()->back()->with('warning','Hãy liên lạc với quản trị viên để được hỗ trợ');
        }
        $type = $request->type ? $request->type : 111;
        $status = (isset($request->status) && $request->status != null) ? $request->status : 0;
        $_query = Required::where('from_type', ArrayHelper::from_type_rquired_accessory)->where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->required_department_id) && $request->required_department_id != null) {
                $query->where('required_department_id', $request->required_department_id);
            }
            if (isset($request->search_date) && $request->search_date != null) {
                $search_date = Carbon::parse($request->search_date)->format('Y-m-d');
                $query->whereDate('created_at', $search_date);
            }
        });
        $_query->where('type','>=', 2);
        $_query->where('type', '<=', 4);
        if (isset($request->locations) && $request->locations != null) {
            if($request->locations == 1){
                $_query->orderBy('location', 'desc');
            }else{
                $_query->orderBy('location', 'asc');
            }
        }
        if ($status == 0 || $status == 1) {
            $_query->where('status', $status);
        }
        $_query->orderBy('id', 'desc');
        $_query->orderBy('updated_at', 'desc');

        $data['lists'] = $_query->paginate(200);
        $data['departments'] = Department::all();
        return view('backend.pages.warehouses.index_ong', $data);
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
        $configData = ArrayHelper::formTypeJobs()[ArrayHelper::from_type_rquired_accessory];
        $data['to_dept'] = $configData['to_dept'];
        $employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->whereIn('department_id',$data['to_dept'])->first();
        if(!$employeeDepartment){
            return redirect()->back()->with('warning','Hãy liên lạc với quản trị viên để được hỗ trợ');
        }
        $type = $request->type ? $request->type : 111;
        $status = (isset($request->status) && $request->status != null) ? $request->status : 1;
        $_query = Required::where('from_type', ArrayHelper::from_type_rquired_accessory)->where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->from_date) && $request->from_date != null) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $query->whereDate('date_completed','>=', $from_date);
            }
            if (isset($request->to_date) && $request->to_date != null) {
                $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('date_completed','<=',$to_date.' 11:59:59');
            }
            if (isset($request->required_department_id) && $request->required_department_id != null) {
                $query->where('required_department_id', $request->required_department_id);
            }
        });
        if($type == 111){
            $_query->where('type','<=', 1);
        }
        if($type == 112){
            $_query->where('type','>=', 2);
            $_query->where('type', '<=', 4);
        }
        if (isset($request->locations) && $request->locations != null) {
            if($request->locations == 1){
                $_query->orderBy('location', 'desc');
            }else{
                $_query->orderBy('location', 'asc');
            }
        }
        if ($status == 0 || $status == 1) {

            $_query->where('status', $status);
        }
        $_query->orderBy('id', 'desc');
        $_query->orderBy('updated_at', 'desc');
        $data['lists'] = $_query->paginate($data['per_page']);
        $data['departments'] = Department::all();
        return view('backend.pages.warehouses.index1', $data);
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
        $type = $request->type ? $request->type : 111;
        $status = $request->status ? $request->status : 0;
        $_query = Required::where('from_type', ArrayHelper::from_type_rquired_accessory)->whereDate('created_at',Carbon::parse($curentDate)->format('Y-m-d'))->where(function ($query) use ($request) {

            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->required_department_id) && $request->required_department_id != null) {
                $query->where('required_department_id', $request->required_department_id);
            }

        });
        if ($status == 0 || $status == 1) {
            $_query->where('status', $status);
        }
        $_query->orderBy('updated_at', 'desc');
        $data = $_query->get();
        return (new RequiredExport($data))->download(Carbon::now()->format('H_i_s d_m_Y_').'kho.xlsx');
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
        $type = $request->type ? $request->type : 111;
        $status = (isset($request->status) && $request->status != null) ? $request->status : 1;
        $_query = Required::where('from_type', ArrayHelper::from_type_rquired_accessory)->where(function ($query) use ($request) {

            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->from_date) && $request->from_date != null) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $query->whereDate('date_completed','>=', $from_date);
            }
            if (isset($request->to_date) && $request->to_date != null) {
                $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('date_completed','<=',$to_date.' 11:59:59');
            }
            if (isset($request->required_department_id) && $request->required_department_id != null) {
                $query->where('required_department_id', $request->required_department_id);
            }
        });
        if($type == 111){
            $_query->where('type','<=', 1);
        }
        if($type == 112){
            $_query->where('type','>=', 2);
            $_query->where('type', '<=', 4);
        }
        if (isset($request->locations) && $request->locations != null) {
            if($request->locations == 1){
                $_query->orderBy('location', 'desc');
            }else{
                $_query->orderBy('location', 'asc');
            }
        }
        if ($status == 0 || $status == 1) {
            $_query->where('status', $status);
        }
        $_query->orderBy('id', 'desc');
        $_query->orderBy('updated_at', 'desc');
        $data = $_query->get();
       // dd($data);
        return (new RequiredExport($data))->download(Carbon::now()->format('H_i_s d_m_Y_').'kho.xlsx');
        return (new FastExcel($data))->download(Carbon::now()->format('H_i_s d_m_Y_').'kho.xlsx', function ($required) {
            $confirm_form = json_decode(@$required->confirm_form);
            $user = Employee::findEmployeeById(@$confirm_form[0]->user_id);
            if ($required->status == 0) {
                $status =  'Chưa xuất';
            } else if (@$confirm_form[0]->quantity < $required->quantity_detail) {
                $status =   'Đã xuất hàng lẻ';
            } else {
                $status =   'Đã xuất đủ hàng';
            }
            return [
                'ID'=> $required->id ,
                'Code'=> $required->code ,
                'Bộ phận yc'=> $required->department->name ,
                'Số cuộn yc'=> $required->quantity ,
                'Số lượng yc'=> $required->quantity_detail ,
                'Số lượng xuất'=> $required->quantity_detail - $required->remaining ,
                'Trạng thái'=> $status,
                'Thời gian yc'=>$required->created_at->format('Y-m-d H:i:s'),
                'Ngày xuất'=> @$confirm_form ? date('Y-m-d', strtotime(@$confirm_form[0]->date)):'',
                'Thời gian xuất'=>@$confirm_form ? date('Y-m-d H:i:s', strtotime(@$confirm_form[0]->date)):'',
                'Người xuất'=>@$user->code.' - '.@$user->first_name.' '.@$user->last_name
            ];
        });
    }
    public function action(Request $request)
    {
        $method = $request->input('method', '');
        if ($method == 'per_page') {
            $this->per_page($request);
            return back();
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function syncAccessory(Request $request)
    {
        set_time_limit(0);
        try {
            $sql = 'SELECT tb2."品目C",tb2."場所C",tb2."品目K",tb2."棚番" FROM(SELECT "品目C",MAX("SEQ") as "_seq" FROM "TAD_Z60J" GROUP BY "品目C") tb1 INNER JOIN "TAD_Z60J" tb2 ON tb1."品目C" = tb2."品目C" and tb1."_seq" = tb2.SEQ';
            $getList = DB::connection('oracle')->select($sql);
            echo "Đang thực hiện đồng bộ... <br>";
            foreach ($getList as $key => $value) {
              $_accessory =  Accessory::where(['code'=>trim($value->品目c),'location_c'=>trim($value->場所c),'location_k'=>trim($value->品目k),'location'=>trim($value->棚番)])->first();
              if(!$_accessory){
                $result= Accessory::create([
                    'code'=> trim($value->品目c),
                    'location_k'=> trim($value->品目k),
                    'location_c'=> trim($value->場所c),
                    'location'=> trim($value->棚番),
                    'status'=>1
                ]);
              }
            }
            echo "Đã đồng bộ xong. <br>";
        } catch (\Exception $e) {
            LogImport::create([
                'type' => 1,
                'status' => 0,
                'data' => "syncAccessory",
                'messages' => $e->getLine().'||'.$e->getMessage()
            ]);
        }
    }
    public function checkLocaltion(Request $request)
    {
        dd( $request->all());
        $accessory = Accessory::find($request->id);
        $user = Auth::user();
        $employee = @$user->employee;
        if(!$employee){
            return $this->success([
                'status'=>false,
                'message'=> "Liên hệ quản trị để được hỗ trợ!",
            ]);
        }
        if (!$accessory) {
            return $this->success([
                'status'=>false,
                'message'=>"Linh kiện không tồn tại !",
            ]);
        }
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
        // $receiving_department_ids = $requireds->receiving_department_ids;
        // $employee_department = EmployeeDepartment::where('employee_id', $employee->id)->first();
        // if ((!$employee_department) || !in_array($employee_department->department_id, json_decode($receiving_department_ids))) {
        //     return $this->success([
        //         'status'=>false,
        //         'message'=>"Bạn không có trong bộ phận (Kho xuất)!",
        //     ]);
        // }
        $remaining = $requireds->quantity_detail - ($this->getQuantity($requireds)+(float)$request->quantity);// (số cuộn * định mức)- (tổng số lần xuất)
        if($remaining < 0){
            return $this->success([
                'status'=>false,
                'message'=>"Bạn xuất quá số lượng yêu cầu!",
            ]);
        }
        $total_confirm_form = json_decode($requireds->confirm_form);
        $confirm_form['quantity']= (float)$request->quantity;
        $confirm_form['date']= Carbon::now();
        $confirm_form['user_id']= $employee->id;
        $confirm_form['full_name']= $employee->first_name.' '. $employee->last_name;
        $confirm_form['note']= $request->note;
        $total_confirm_form[]=$confirm_form;
        $requireds->confirm_form = json_encode($total_confirm_form);
        // $requireds->status = $remaining <= 0 ? 1 : 2;
        $requireds->status = 1;
        $requireds->remaining =  $remaining; // còn lại
        $requireds->completed_by = $employee->id;
        $requireds->date_completed = Carbon::now();
        $requireds->save();
        $requireds->department;
        // if($requireds->required_department_id == 7){ // nếu là bộ phận dập thì set lệnh in phiếu
        //     RedisHelper::queueSet('print_required', $requireds);
        // }
        // $html =  view('qrcode.print-accessory', ['required' => $requireds])->render();
        // RedisHelper::queueSet('print_required', $html);

        return $this->success([
            'status'=>true,
            'message'=>'Đã xuất linh kiện '.$requireds->code.' thành công !!',
            'data'=>$requireds,
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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function show(Accessory $accessory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('accessory.edit')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }

        $accessory = Accessory::find($id);
        return view('backend.pages.accessorys.edit', compact('accessory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('accessory.edit')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }

        $accessory = Accessory::find($id);
        if (is_null($accessory)) {
            session()->flash('error', "The page is not found !");
            return redirect()->route('admin.blogs.index');
        }
        try {
            $accessory->material_norms=$request->material_norms;
            $accessory->unit=$request->unit;
            if (!is_null($request->image)) {
                $accessory->image = UploadHelper::upload('image', $request->image, $accessory->code . '-' . time(), 'public/assets/images/accessory');
            }
            $accessory->save();
            session()->flash('success', 'Sửa thành công !!');
            return redirect()->route('admin.accessorys.index');
        } catch (\Exception $e) {
            session()->flash('sticky_error', $e->getMessage());
            return back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Accessory $accessory)
    {
        //
    }
}
