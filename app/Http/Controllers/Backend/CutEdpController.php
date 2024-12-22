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
use App\Models\SignatureSubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CutEdpController extends Controller
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
        $user = Auth::user();
        $employee = @$user->employee;
        if(!$employee){
            return redirect()->back()->with('warning','Hãy liên lạc với quản trị viên để được hỗ trợ');
        }
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['filter'] = $request->all();
        $curentDate = $request->search_date ? $request->search_date : Carbon::now()->format('d-m-Y');
        $data['filter']['search_date'] = $curentDate;
        $configData = ArrayHelper::formTypeJobs()[ArrayHelper::from_type_cut_edp];
        $data['to_dept'] = $configData['to_dept'];
        $_query = Required::where('type',0)->where('from_type', ArrayHelper::from_type_cut_edp)->whereDate('created_at',Carbon::parse($curentDate)->format('Y-m-d'))->where(function ($query) use ($request) {
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
        $data['lists'] = $_query->paginate($data['per_page']);
        $data['departments'] = Department::all();
        return view('backend.pages.cutEdp.index', $data);
    }
    public function detail(Request $request)
    {
        $user = Auth::user();
        $employee = @$user->employee;
        if(!$employee){
            return redirect()->back()->with('warning','Hãy liên lạc với quản trị viên để được hỗ trợ');
        }
        $_edps = Required::find($request->id);
        if(!$_edps){
            return redirect()->back()->with('warning','Không tìm thấy thông tin');
        }
        $edps[]=$_edps;

        return view('backend.pages.cutEdp.detail', ['edps' => $edps]);
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
        $data['filter']['search_date'] = $curentDate;
        $configData = ArrayHelper::formTypeJobs()[ArrayHelper::from_type_rquired_accessory];
        $data['to_dept'] = $configData['to_dept'];
        $employeeDepartment = EmployeeDepartment::where('employee_id', $employee->id)->whereIn('department_id',$data['to_dept'])->first();
        if(!$employeeDepartment){
            return redirect()->back()->with('warning','Hãy liên lạc với quản trị viên để được hỗ trợ');
        }
        $_query = Required::where('type',0)->where('from_type', ArrayHelper::from_type_rquired_accessory)->where(function ($query) use ($request) {
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
        });
        if (isset($request->locations) && $request->locations != null) {
            if($request->locations == 1){
                $_query->orderBy('location', 'desc');
            }else{
                $_query->orderBy('location', 'asc');
            }
        }
        $_query->orderBy('updated_at', 'desc');
        $data['lists'] = $_query->paginate($data['per_page']);
        $data['departments'] = Department::all();
        return view('backend.pages.cutEdp.index1', $data);
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
        $data = Required::where('type',0)->where('from_type', ArrayHelper::from_type_rquired_accessory)->whereDate('created_at',Carbon::parse($curentDate)->format('Y-m-d'))->where(function ($query) use ($request) {

            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->required_department_id) && $request->required_department_id != null) {
                $query->where('required_department_id', $request->required_department_id);
            }

        })->orderBy('updated_at', 'desc')->get();
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
        $curentDate = $request->search_date ? $request->search_date : Carbon::now()->format('d-m-Y');
        $data['filter']['search_date'] = $curentDate;
        $data = Required::where('type',0)->where('from_type', ArrayHelper::from_type_rquired_accessory)->whereDate('created_at',Carbon::parse($curentDate)->format('Y-m-d'))->where(function ($query) use ($request) {

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
        })->orderBy('updated_at', 'desc')->get();
        return (new RequiredExport($data))->download(Carbon::now()->format('H_i_s d_m_Y_').'kho.xlsx');
    }
    public function create()
    {
        $data['current_time'] = Carbon::now();
        $requiredType = ArrayHelper::from_type_cut_edp;
        $formTypeJobs = ArrayHelper::formTypeJobs()[$requiredType];
        $user = Auth::user();
        $employee = @$user->employee;
        if(!$employee){
            return redirect()->back()->with('warning',"Hãy liên hệ quản trị để được thao tác");
        }
        $data['employee'] = $employee;
        $data['formTypeJobs'] = $formTypeJobs;
        $data['departments'] = Employee::get_departments_by_id($employee->id);
        //dd($data['departments']);
        $data['employeeDepartment'] = EmployeeDepartment::where('employee_id',$employee->id)->first();
        return view('backend.pages.cutEdp.create',$data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       //dd($request->all());
       $user = Auth::user();
       $employee = @$user->employee;
       if(!$employee){
           return $this->success([
               'status'=>false,
               'message'=> 'Liên lạc quản trị để được hỗ trợ',
           ]);
       }
       $requireCode = 'R_' . now()->format('Ymdhis');
       $formTypeJobs = ArrayHelper::formTypeJobs()[ArrayHelper::from_type_cut_edp];
       $data['formTypeJobs'] = $formTypeJobs;
       if($request->quantity == 0){
           return $this->success([
               'status'=>false,
               'message'=> 'Số lượng yêu cầu phải lớn hơn 0',
           ]);
       }
       $pc_name =  gethostbyaddr($_SERVER['REMOTE_ADDR']);
       $edp = DB::connection('oracle_toa_set')->table('TDCJSIJI')->where('HINCD', 'like','%'.$request->hincd.'%')->where('SENBAN',$request->senban)->first();
       if(!$edp){
            return $this->success([
                'status'=>false,
                'message'=> 'Không tìm thấy mã sản phẩm.',
            ]);
       }
       $content_form = utf8_encode("$edp->sentyo,$edp->kawaa,$edp->kawab,$request->quantity,$request->lot_no,0,$edp->hincd,$edp->senban,$edp->sensyu,$edp->tascda,$edp->gumcda,$edp->tascdb,$edp->gumcdb,,$edp->biko");
       $confirm_form = [
            'hincd'=> $edp->hincd,
            'senban'=> $edp->senban,
            'sensyu'=> $edp->sensyu,
            'lot_no'=> $request->lot_no,
            'edp'=>$edp
       ];

       try {
           DB::beginTransaction();
           $required = Required::create([
               'required_department_id' => $request->department_id,
               'code_required' => $requireCode,
               'code' => $edp->hincd,
               'order' => $request->order??0,
               'from_type'=>ArrayHelper::from_type_cut_edp,
               'created_by' => $employee->id,
               'receiving_department_ids' => json_encode($formTypeJobs['to_dept']),
               'usage_status' => 0,
               'quantity' => $request->quantity,
               'content_form'=>$content_form,
               'confirm_form'=>json_encode($confirm_form),
               'pc_name'=>$pc_name,
               'content'=>$request->content,
           ]);

           //bộ phận yêu cầu
           $_position = EmployeeDepartment::where('department_id',$request->department_id)->where('employee_id',$employee->id)->pluck('positions')->first();
                   $confirm_from_dept = $formTypeJobs['confirm_from_dept'];
                   $confirm_by_from_dept = $formTypeJobs['confirm_by_from_dept'];
                   $_confirm_by_from_dept = [$employee->id];
                   if($confirm_from_dept == 0){ // chưa duyệt
                       foreach ($confirm_by_from_dept as $key1 => $value1) {
                           $_confirm_by_from_dept = EmployeeDepartment::where('department_id',$request->department_id)->where('positions', $value1)->pluck('employee_id')->toArray();
                           SignatureSubmission::create([
                               'required_id' => $required->id,
                               'department_id' =>  $request->department_id,
                               'positions' => $value1,
                               'approve_id' => json_encode($_confirm_by_from_dept),
                               'status' => $formTypeJobs['confirm_from_dept'],
                               'signature_id' => 0,
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
                               'status' => $formTypeJobs['confirm_to_dept'],
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
                           'status' => $formTypeJobs['confirm_to_dept'],
                           'signature_id' => 0,
                           'type'=>ArrayHelper::to_dept
                       ]);
                   }
               }
           }
           DB::commit();
           if($request->print == "true"){
                $required->status = 1;
                $required->save();
                $_required[]=$required;
                $this->printPdf1($_required);
           }
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
               'data' => 'order_edp',
               'messages' => $e->getLine().'||'.$e->getMessage()
           ]);
           return $this->success([
               'status'=>false,
               'message'=> $e->getMessage(),
           ]);
       }
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

    public function action(Request $request)
    {
        $method = $request->input('method', '');
        if ($method == 'per_page') {
            $this->per_page($request);
            return back();
        }else if ($method == 'print_selected') {
            if (isset($request->ids)) {
                $edps=[];
                foreach ($request->ids as $key => $value) {
                    $edps[]=$value;
                    if(count($edps) == 5){
                        $requireds = Required::whereIn('id',$edps)->get();
                        if($requireds){
                            $html = $this->printPdf($requireds);
                            RedisHelper::queueSet('print_edp', $html);
                        }
                        $edps=[];
                    }
                }
                if(count($edps) > 0){
                    $requireds = Required::whereIn('id',$edps)->get();
                    if($requireds){
                        $html = $this->printPdf($requireds);
                        RedisHelper::queueSet('print_edp', $html);
                    }
                }
                Required::whereIn('id',$request->ids)->update([
                    'status'=>1
                ]);
                RedisHelper::setKey('startPrintEdp',true);
            }
            return back()->with('success', 'thành công!');
        }else if ($method == 'delete') {
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

    public function printPdf($edps)
    {
        return view('qrcode.edp_v1', ['edps' => $edps])->render();
    }
    public function printPdf1($edps)
    {
        $html =  view('qrcode.edp_v1', ['edps' => $edps])->render();
        $post_fields['Html'] = $html;
        $post_fields['PrinterName'] = 'RICOH Pro 8300S PCL 6';
        $post_fields['Landscape'] ='false';
        $post_fields['Width'] = '827';
        $post_fields['Height'] =  '1169';
        $curl_handle = curl_init('http://192.168.207.6:8092/printpdffromhtml/html-pdf-v2');
        curl_setopt($curl_handle, CURLOPT_HEADER, 0);
        curl_setopt($curl_handle, CURLOPT_VERBOSE, 0);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($curl_handle, CURLOPT_POST, true);
        @curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post_fields);
        $returned_data = curl_exec($curl_handle);
        curl_close($curl_handle);
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
    public function ajaxGetSelectByHINCD(Request $request)
    {
        if ($request->hincd || $request->senban) {
            $where[] = ['HINCD', 'like', '%' . $request->hincd . '%'];
            $where[] = ['SENBAN','=',$request->senban];
            return response()->json($this->searchByHINCD(['where' => $where]));
        }
        return response()->json($this->searchByHINCD());
    }

    public function ajaxGetSelectBySENBAN(Request $request)
    {
        if ($request->search) {
            $where[] = ['SENBAN', 'like', '%' . $request->search . '%'];
            $where[] = ['HINCD', 'like', '%' . $request->hincd . '%'];
            return response()->json($this->searchBySENBAN(['where' => $where]));
        }
        return response()->json($this->searchBySENBAN());
    }
    public function ajaxGetSelectByLotNo(Request $request)
    {
        if ($request->search) {
            $where[] = ['発注SEQ', 'like',$request->search . '%'];
            // $where[] = ['品目C', 'like', '%' .substr($request->hincd,1)  . '%'];
            return response()->json($this->searchByLotNo(['where' => $where,'select'=>['発注seq','品目c']]));
        }
        return response()->json($this->searchByLotNo(['select'=>['発注seq','品目c']]));
    }
    public function ajaxGetSelectBySENSYU(Request $request)
    {
        if ($request->search) {
            $where[] = ['SENSYU', 'like', '%' . $request->search . '%'];
            $where[] = ['HINCD', 'like', '%' . $request->hincd . '%'];
            return response()->json($this->searchBySENSYU(['where' => $where,'select'=>['sensyu']]));
        }
        return response()->json($this->searchBySENSYU(['select'=>['sensyu']]));
    }
    public function searchByHINCD(array $options = [])
    {
        $default = [
            'select'   => '*',
            'where'    => [],
            'orwhere'    => [],
            'per_page' => 20,
        ];

        $options = array_merge($default, $options);
        extract($options);

        $model = DB::connection('oracle_toa_set')->table('TDCJSIJI')->select($options['select']);
        if ($options['where']) {
            $model = $model->where(function($query) use($options){
                                   $query->where($options['where']);
            });
        }
        return $model->first();
    }
    public function searchBySENBAN(array $options = [])
    {
        $default = [
            'select'   => '*',
            'where'    => [],
            'orwhere'    => [],
            'per_page' => 20,
            'groupBy' => 'senban',
        ];

        $options = array_merge($default, $options);
        extract($options);

        $model = DB::connection('oracle_toa_set')->table('TDCJSIJI')->select($options['select']);
        if ($options['where']) {
            $model = $model->where(function($query) use($options){
                                   $query->where($options['where']);
            });
        }
        return $model->groupBy($options['groupBy'])->paginate($options['per_page']);
    }
    public function searchByLotNo(array $options = [])
    {
        $default = [
            'select'   => '*',
            'where'    => [],
            'orwhere'    => [],
            'per_page' => 20,
        ];

        $options = array_merge($default, $options);
        extract($options);

        $model = DB::connection('oracle')->table('DFW_H10F')->select($options['select']);
        if ($options['where']) {
            $model = $model->where(function($query) use($options){
                                   $query->where($options['where']);
            });
        }
        return $model->paginate($options['per_page']);
    }
    public function searchBySENSYU(array $options = [])
    {
        $default = [
            'select'   => '*',
            'where'    => [],
            'orwhere'    => [],
            'per_page' => 20,
            'groupBy' => 'sensyu',
        ];

        $options = array_merge($default, $options);
        extract($options);

        $model = DB::connection('oracle')->table('TDCJSIJI')->select($options['select']);
        if ($options['where']) {
            $model = $model->where(function($query) use($options){
                                   $query->where($options['where']);
            });
        }
        return $model->groupBy($options['groupBy'])->paginate($options['per_page']);
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

}
