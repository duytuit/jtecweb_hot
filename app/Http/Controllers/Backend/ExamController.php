<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ExamExport;
use App\Exports\AuditExport;
use App\Helpers\ArrayHelper;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;
use Yajra\DataTables\Facades\DataTables;

class ExamController extends Controller
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

        if (is_null($this->user) || !$this->user->can('exam.view')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        // Phân trang
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['keyword'] = $request->input('keyword', null);
        $data['advance'] = 0;
        $data['arrayExamPd'] = ArrayHelper::arrayExamPd();
        $arrayExamPd = $data['arrayExamPd'];
        $type = $request->exam_type ? $request->exam_type : 1;
        if (count($request->except('keyword')) > 0) {
            // Tìm kiếm nâng cao
            $data['advance'] = 1;
            $data['filter'] = $request->all();
        }
        $current_cycleName = $request->cycle_name ? (int)$request->cycle_name : Carbon::now()->format('mY');
        $data['cycleName'] =$request->cycle_name ? (int)$request->cycle_name : $current_cycleName;
        $data['cycleNames'] = ArrayHelper::cycleName();
        $data['depts'] = Department::where('status',1)->get();
        $data['emp'] = Employee::select('code')->where('status', 1)->where('status_exam', 1)->whereHas('employeeDepartment',function ($query) use ($request){
            if (isset($request->dept) && $request->dept != null) {
                $query->where('department_id', $request->dept);
            }
        })->pluck('code');
        if(strlen($current_cycleName) == 5){
            $convert_date = substr($current_cycleName, 1, 4) . '-' . substr($current_cycleName, 0, 1);
        }else{
            $convert_date = substr($current_cycleName, 2, 4) . '-' . explode(substr($current_cycleName, 2, 4),$current_cycleName)[0];
        }

        $data['filter']['cycle_name'] =$request->cycle_name ? (int)$request->cycle_name : $current_cycleName;
        // dd($data['filter']['cycle_name']);
        $data['filter']['exam_type'] =$type;
         // lấy ra nhân viên vào đợt 1 (nv vào lớn hơn ngày 15 thì không lấy)
         $getEmployeeBeginWorking1 = Employee::select('code')->where('status', 1)->where('status_exam', 1)
         ->where(function ($query) use ($request,$convert_date) {
             if (isset($request->cycle_name) && $request->cycle_name != null) {
                 $query->whereDate('begin_date_company', '>=', Carbon::parse($convert_date . '-15'));
             }else{
                $query->whereDate('begin_date_company', '>=', Carbon::now()->format('Y-m') . '-15');
             }
         })->whereHas('employeeDepartment',function ($query) use ($request){
            if (isset($request->dept) && $request->dept != null) {
                $query->where('department_id', $request->dept);
            }
        })->pluck('code');
        // lấy ra nhân viên nghỉ trước đợt thi 1
        $getEmployeeWorkingMission1 = Employee::select('code')->where('status', 1)->where('status_exam', 1)
            ->where(function ($query) use ($request,$convert_date) {
                if (isset($request->cycle_name) && $request->cycle_name != null) {
                    $query->whereDate('end_date_company', '<=', Carbon::parse($convert_date . '-1'));
                }else{
                    $query->whereDate('end_date_company', '<=', Carbon::now()->format('Y-m') . '-1');
                 }
            })->whereHas('employeeDepartment',function ($query) use ($request){
                if (isset($request->dept) && $request->dept != null) {
                    $query->where('department_id', $request->dept);
                }
            })->pluck('code');

        // lấy ra nhân viên nghỉ trước đợt thi 2
        $getEmployeeWorkingMission2 = Employee::select('code')->where('status', 1)->where('status_exam', 1)
            ->where(function ($query) use ($request,$convert_date) {
                if (isset($request->cycle_name) && $request->cycle_name != null) {
                    $query->whereDate('end_date_company', '<=', Carbon::parse($convert_date . '-15'));
                }else{
                    $query->whereDate('end_date_company', '<=', Carbon::now()->format('Y-m') . '-15');
                 }
            })->whereHas('employeeDepartment',function ($query) use ($request){
                if (isset($request->dept) && $request->dept != null) {
                    $query->where('department_id', $request->dept);
                }
            })->pluck('code');
           // dd($getEmployeeWorkingMission1.'-'.$getEmployeeWorkingMission2);
        $data['emp_pass_1'] = Exam::where('type', $type)->select('id', 'code')->whereIn('code', $data['emp'])
            ->whereNotIn('code', $getEmployeeWorkingMission1)
            ->whereNotIn('code', $getEmployeeBeginWorking1)
            ->where(function ($query) use ($request,$current_cycleName) {
                if (isset($request->from_date) && isset($request->to_date)) {
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                    $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
                    $query->whereDate('create_date', '>=', $from_date);
                    $query->whereDate('create_date', '<=', $to_date);
                }
                if (isset($request->dept) && $request->dept != null) {
                    $query->where('sub_dept', $request->dept);
                }
            })
            ->where('status', 1)
            ->where('cycle_name',$data['filter']['cycle_name'])
            ->where('scores', '>=', $arrayExamPd[$type]['scores'][0])
            ->where('examinations', 1)
            ->groupBy('code')->orderBy('id', 'desc')
            ->get()->ToArray();
            // dd(  $getEmployeeWorkingMission1.'-'.$getEmployeeBeginWorking1);
        $data['emp_fail_1_90_95'] = Exam::where('type', $type)->select('id', 'code')
            ->whereNotIn('code', $getEmployeeWorkingMission1)
            ->whereNotIn('code', $getEmployeeBeginWorking1)
            ->where(function ($query) use ($request,$current_cycleName) {
                if (isset($request->from_date) && isset($request->to_date)) {
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                    $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
                    $query->whereDate('create_date', '>=', $from_date);
                    $query->whereDate('create_date', '<=', $to_date);
                }
                if (isset($request->dept) && $request->dept != null) {
                    $query->where('sub_dept', $request->dept);
                }
            })
            ->whereIn('code', $data['emp'])
            ->whereNotIn('code', array_column($data['emp_pass_1'], 'code'))
            ->where('cycle_name',$data['filter']['cycle_name'])
            ->where('examinations', 1)
            ->where('status', 0)
            ->where('scores', '>=', $arrayExamPd[$type]['scores'][1])->where('scores', '<', $arrayExamPd[$type]['scores'][0])
            ->groupBy('code')->orderBy('id', 'desc')
            ->get()->ToArray();
        $data['emp_fail_1_90'] = Exam::where('type', $type)->select('id', 'code')
            ->whereNotIn('code', $getEmployeeWorkingMission1)
            ->whereNotIn('code', $getEmployeeBeginWorking1)
            ->where(function ($query) use ($request,$current_cycleName) {
                if (isset($request->from_date) && isset($request->to_date)) {
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                    $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
                    $query->whereDate('create_date', '>=', $from_date);
                    $query->whereDate('create_date', '<=', $to_date);
                }
                if (isset($request->dept) && $request->dept != null) {
                    $query->where('sub_dept', $request->dept);
                }
            })
            ->whereIn('code', $data['emp'])
            ->whereNotIn('code', array_column($data['emp_pass_1'], 'code'))
            ->whereNotIn('code', array_column($data['emp_fail_1_90_95'], 'code'))
            ->where('cycle_name',$data['filter']['cycle_name'])
            ->where('examinations', 1)
            ->where('status', 0)->where('scores', '<', $arrayExamPd[$type]['scores'][1])
            ->groupBy('code')->orderBy('id', 'desc')
            ->get()->ToArray();

        $data['emp_yet_1'] = Employee::select('code')->where('status', 1)->where('status_exam', 1)
            ->whereHas('employeeDepartment', function ($query) use ($request) {
                if (isset($request->dept) && $request->dept != null) {
                    $query->where('department_id', $request->dept);
                }
            })
            ->whereNotIn('code', $getEmployeeBeginWorking1)
            ->whereNotIn('code', $getEmployeeWorkingMission1)
            ->whereNotIn('code', array_column($data['emp_pass_1'], 'code'))
            ->whereNotIn('code', array_column($data['emp_fail_1_90_95'], 'code'))
            ->whereNotIn('code', array_column($data['emp_fail_1_90'], 'code'))
            ->get()->ToArray();

        $data['emp_pass_2'] = Exam::where('type', $type)->select('id', 'code')->whereIn('code', $data['emp'])
            ->whereNotIn('code', $getEmployeeWorkingMission2)
            ->where(function ($query) use ($request,$current_cycleName) {
                if (isset($request->from_date) && isset($request->to_date)) {
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                    $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
                    $query->whereDate('create_date', '>=', $from_date);
                    $query->whereDate('create_date', '<=', $to_date);
                }
                if (isset($request->dept) && $request->dept != null) {
                    $query->where('sub_dept', $request->dept);
                }
            })
            ->where('cycle_name',$data['filter']['cycle_name'])
            ->where('status', 1)
            ->where('scores', '>=', $arrayExamPd[$type]['scores'][0])
            ->where('examinations', 2)
            ->groupBy('code')->orderBy('id', 'desc')
            ->get()->ToArray();

        $data['emp_fail_2_90_95'] = Exam::where('type', $type)->select('id', 'code')
            ->whereNotIn('code', $getEmployeeWorkingMission2)
            ->whereIn('code', $data['emp'])
            ->whereNotIn('code', array_column($data['emp_pass_2'], 'code'))
            ->where(function ($query) use ($request,$current_cycleName) {
                if (isset($request->from_date) && isset($request->to_date)) {
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                    $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
                    $query->whereDate('create_date', '>=', $from_date);
                    $query->whereDate('create_date', '<=', $to_date);
                }
                if (isset($request->dept) && $request->dept != null) {
                    $query->where('sub_dept', $request->dept);
                }
            })
            ->where('cycle_name',$data['filter']['cycle_name'])
            ->where('examinations', 2)
            ->where('status', 0)
            ->where('scores', '>=', $arrayExamPd[$type]['scores'][1])->where('scores', '<', $arrayExamPd[$type]['scores'][0])
            ->groupBy('code')->orderBy('id', 'desc')
            ->get()->ToArray();
        $data['emp_fail_2_90'] = Exam::where('type', $type)->select('id', 'code')
            ->whereNotIn('code', $getEmployeeWorkingMission2)
            ->whereIn('code', $data['emp'])
            ->whereNotIn('code', array_column($data['emp_pass_2'], 'code'))
            ->whereNotIn('code', array_column($data['emp_fail_2_90_95'], 'code'))
            ->where(function ($query) use ($request,$current_cycleName) {
                if (isset($request->from_date) && isset($request->to_date)) {
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                    $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
                    $query->whereDate('create_date', '>=', $from_date);
                    $query->whereDate('create_date', '<=', $to_date);
                }
                if (isset($request->dept) && $request->dept != null) {
                    $query->where('sub_dept', $request->dept);
                }
            })
            ->where('cycle_name',$data['filter']['cycle_name'])
            ->where('examinations', 2)
            ->where('status', 0)
            ->where('scores', '<', $arrayExamPd[$type]['scores'][1])
            ->groupBy('code')->orderBy('id', 'desc')
            ->get()->ToArray();

        $data['emp_yet_2'] = Employee::select('code')->where('status', 1)->where('status_exam', 1)
            ->whereHas('employeeDepartment',function ($query) use ($request){
                if (isset($request->dept) && $request->dept != null) {
                    $query->where('department_id', $request->dept);
                }
            })
            ->whereNotIn('code', $getEmployeeWorkingMission2)
            ->whereNotIn('code', array_column($data['emp_pass_2'], 'code'))
            ->whereNotIn('code', array_column($data['emp_fail_2_90_95'], 'code'))
            ->whereNotIn('code', array_column($data['emp_fail_2_90'], 'code'))
            ->get()->ToArray();
        $data['lists'] = Exam::where('type', $type)->where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->dept) && $request->dept != null) {
                $query->where('sub_dept', $request->dept);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->cycle_name) && $request->cycle_name != null) {
                $query->where('cycle_name',$request->cycle_name  );
            }
            if (isset($request->confirm) && $request->confirm != null) {
                if($request->confirm == 1){
                    $query->where('confirm', '>',0);
                }else{
                    $query->where('confirm', '=',0);
                }
            }
            if (isset($request->from_date) && isset($request->to_date)) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('create_date', '>=', $from_date);
                $query->whereDate('create_date', '<=', $to_date);
            }
        })
        ->whereNotIn('code', $getEmployeeWorkingMission1)
        ->whereNotIn('code', $getEmployeeBeginWorking1)
        ->whereNotIn('code', $getEmployeeWorkingMission2)
        ->orderBy('code')->orderBy('cycle_name')->orderBy('created_at')->paginate($data['per_page']);
        return view('backend.pages.exams.index', $data);
    }

    public function index1(Request $request)
    {

        if (is_null($this->user) || !$this->user->can('exam.view')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        // Phân trang
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['keyword'] = $request->input('keyword', null);
        $data['advance'] = 0;
        if (count($request->except('keyword')) > 0) {
            // Tìm kiếm nâng cao
            $data['advance'] = 1;
            $data['filter'] = $request->all();
        }
        $current_cycleName = $request->cycle_name ? (int)$request->cycle_name : Carbon::now()->format('mY');
        $data['cycleName'] =$request->cycle_name ? (int)$request->cycle_name : (int)$current_cycleName;
        $data['cycleNames'] = ArrayHelper::cycleName();
        $data['filter']['cycle_name'] =$request->cycle_name ? (int)$request->cycle_name :(int)$current_cycleName;
        if(strlen($current_cycleName) == 5){
            $convert_date = substr($current_cycleName, 1, 4) . '-' . substr($current_cycleName, 0, 1);
        }else{
            $convert_date = substr($current_cycleName, 2, 4) . '-' . explode(substr($current_cycleName, 2, 4),$current_cycleName)[0];
        }
        // lấy ra nhân viên vào trong 1 tháng
        $getEmployeeBeginOneMonth = Employee::select('code')->where('status_exam', 1)->whereHas('employeeDepartment.department', function ($query) {
            $query->where('id', 4);
           })
            ->where('status', 1)
            ->whereDate('begin_date_company', '>=', Carbon::parse($convert_date . '-1')->subMonths(1)->format('Y-m-d'))->pluck('code');
        // dd($getEmployeeBeginOneMonth);

        // lấy ra nhân viên nghỉ trước đợt thi 1
        $getEmployeeWorkingMission1 = Employee::select('code')->where('status_exam', 1)
        ->whereHas('employeeDepartment.department', function ($query) {
            $query->where('id', 4);
           })
        ->whereDate('end_date_company', '>=',  Carbon::parse($convert_date . '-1')->subMonths(1)->format('Y-m-d'))
        ->pluck('code');
        // dd($getEmployeeWorkingMission1);
        $data['emp_pass_1'] = Exam::where('type', 2)->select('id', 'code')
            ->whereNotIn('code', $getEmployeeWorkingMission1)
            ->whereIn('code', $getEmployeeBeginOneMonth)
            ->where('cycle_name',$data['filter']['cycle_name'])
            ->where('sub_dept', 4)
            ->where('status', 1)
            ->where('scores', '>', 79)
            ->groupBy('code')->orderBy('id', 'desc')
            ->get()->ToArray();

        $data['emp_fail_1_60_79'] = Exam::where('type', 2)->select('id', 'code')
            ->whereNotIn('code', $getEmployeeWorkingMission1)
            ->whereIn('code', $getEmployeeBeginOneMonth)
            ->whereNotIn('code', array_column($data['emp_pass_1'], 'code'))
            ->where('status', 0)
            ->where('sub_dept', 4)
            ->where('scores', '>=', 60)->where('scores', '<=', 79)
            ->where('cycle_name',$data['filter']['cycle_name'])
            ->groupBy('code')->orderBy('id', 'desc')
            ->get()->ToArray();

        $data['emp_fail_1_50_59'] = Exam::where('type', 2)->select('id', 'code')
            ->whereNotIn('code', $getEmployeeWorkingMission1)
            ->whereIn('code', $getEmployeeBeginOneMonth)
            ->where('sub_dept', 4)
            ->whereNotIn('code', array_column($data['emp_pass_1'], 'code'))
            ->whereNotIn('code', array_column($data['emp_fail_1_60_79'], 'code'))
            ->where('scores', '>=', 50)->where('scores', '<=', 59)
            ->where('cycle_name',$data['filter']['cycle_name'])
            ->groupBy('code')->orderBy('id', 'desc')
            ->get()->ToArray();
        $data['emp_fail_1_49'] = Exam::where('type', 2)->select('id', 'code')
            ->whereNotIn('code', $getEmployeeWorkingMission1)
            ->whereIn('code', $getEmployeeBeginOneMonth)
            ->whereNotIn('code', array_column($data['emp_pass_1'], 'code'))
            ->whereNotIn('code', array_column($data['emp_fail_1_60_79'], 'code'))
            ->whereNotIn('code', array_column($data['emp_fail_1_50_59'], 'code'))
            ->where('scores', '<', 50)
            ->where('sub_dept', 4)
            ->where('cycle_name',$data['filter']['cycle_name'])
            ->groupBy('code')
            ->orderBy('id', 'desc')
            ->get()->ToArray();
        $data['emp_yet_1'] = Employee::select('code')->where('status_exam', 1)
            ->whereIn('code', $getEmployeeBeginOneMonth)
            ->whereNotIn('code', $getEmployeeWorkingMission1)
            ->whereNotIn('code', array_column($data['emp_pass_1'], 'code'))
            ->whereNotIn('code', array_column($data['emp_fail_1_60_79'], 'code'))
            ->whereNotIn('code', array_column($data['emp_fail_1_50_59'], 'code'))
            ->whereNotIn('code', array_column($data['emp_fail_1_49'], 'code'))
            ->get()->ToArray();

        $data['lists'] = Exam::where('type', 2)->where('sub_dept', 4)->where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->from_date) && isset($request->to_date)) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('create_date', '>=', $from_date);
                $query->whereDate('create_date', '<=', $to_date);
            }
        })
        ->where('cycle_name',$data['filter']['cycle_name'])
        ->orderBy('code')->orderBy('created_at')->paginate($data['per_page']);
        return view('backend.pages.exams.index1', $data);
    }


    public function exportExcel(Request $request)
    {
        $type = $request->exam_type ? $request->exam_type : 1;
        $data = Exam::where('type', $type)->where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->dept) && $request->dept != null) {
                $query->where('sub_dept', $request->dept);
            }
            if (isset($request->cycle_name) && $request->cycle_name != null) {
                $query->where('cycle_name', $request->cycle_name);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->from_date) && isset($request->to_date)) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('create_date', '>=', $from_date);
                $query->whereDate('create_date', '<=', $to_date);
            }
        })->orderBy('code')->orderBy('cycle_name')->orderBy('created_at')->get();
        return (new ExamExport($data))->download('exam.xlsx');
    }

    public function reportFailAnswer(Request $request)
    {
        // Phân trang
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['keyword'] = $request->input('keyword', null);
        $data['advance'] = 0;
        $data['arrayExamPd'] = ArrayHelper::arrayExamPd();
        $type = $request->exam_type ? $request->exam_type : 1;
        $current_cycleName = $request->cycle_name ? (int)$request->cycle_name : Carbon::now()->format('mY');
        $data['filter'] = $request->all();
        $data['filter']['cycle_name'] =$request->cycle_name ? (int)$request->cycle_name : $current_cycleName;
        $data['cycleNames'] = ArrayHelper::cycleName();
        $data['depts'] = Department::where('status',1)->get();
        $data['filter']['exam_type'] =$type;
        $exam = Exam::where('type', $type)->where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->from_date) && isset($request->to_date)) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('create_date', '>=', $from_date);
                $query->whereDate('create_date', '<=', $to_date);
            }
        })->where('cycle_name',$data['filter']['cycle_name'])->orderBy('code')->orderBy('cycle_name')->orderBy('created_at')->get();
        $list_answer=[];
        if($exam){
            foreach ($exam as $key => $value) {
                if($value->fail_aws){
                    $fail_aws = json_decode($value->fail_aws);
                    foreach ($fail_aws as $key1 => $value1) {
                        $list_answer[]= $value1;
                    }

                }
            }
        }
        $data['lists'] = collect($list_answer)->sortBy('id');
        if (isset($request->question) && $request->question != null) {
            $data['lists'] = collect($list_answer)->where('id',$request->question)->sortBy('id');
        }
        $x[] ='x';
        $true[] ='Đúng';
        $false[] ='Sai';
        $arrayExamPd = $data['arrayExamPd'];
        foreach ($arrayExamPd[$type]['data'] as $key => $value) {
            foreach ($value['questions'] as $key1 => $value1) {
                $x[] ='Câu '.$value1['id'];
                $true[$value1['id']] =0;
                $false[$value1['id']] =0;
                $dung=0;
                $sai=0;
                foreach ($list_answer as $key2 => $value2) {
                   if($value1['id'] == $value2->id  && $value2->result == 1){ //đúng
                      $dung++;
                      $true[$value1['id']] =$dung;
                   }
                   if($value1['id'] == $value2->id  && $value2->result == 0){ //sai
                      $sai++;
                      $false[$value1['id']] =$sai;
                   }
                }
            }
        }

        //dd($false);
        $data['report_lists'][] =$x;
        $data['report_lists'][] =$true;
        $data['report_lists'][] =$false;
        // dd($data);
        return view('backend.pages.exams.report-fail', $data);
    }

    public function exportExcelAudit(Request $request)
    {
        $data = Exam::where('type', 2)->where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            // if (isset($request->cycle_name) && $request->cycle_name != null) {
            //     $query->where('cycle_name', $request->cycle_name);
            // }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->from_date) && isset($request->to_date)) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('create_date', '>=', $from_date);
                $query->whereDate('create_date', '<=', $to_date);
            }
        })->orderBy('code')->orderBy('created_at')->get();
        // })->orderBy('code')->orderBy('cycle_name')->orderBy('created_at')->get();
        return (new AuditExport($data))->download('Audit_exam.xlsx');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('exam.delete')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }

        $exam = Exam::find($id);
        if (is_null($exam)) {
            session()->flash('error', "The page is not found !");
            return redirect()->route('admin.exams.index');
        }
        $exam->deleted_at = Carbon::now();
        $exam->deleted_by = Auth::id();
        $exam->status = 0;
        $exam->save();

        session()->flash('success', 'Đã xóa bản ghi thành công !!');
        return redirect()->route('admin.exams.index');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (is_null($this->user) || !$this->user->can('exam.view')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        $exam = Exam::find($id);
        return view('backend.pages.exam.show', compact('exam'));
    }
    /**
     * revertFromTrash
     *
     * @param integer $id
     * @return Remove the item from trash to active -> make deleted_at = null
     */
    public function revertFromTrash($id)
    {
        if (is_null($this->user) || !$this->user->can('exam.delete')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }

        $exam = Exam::find($id);
        if (is_null($exam)) {
            session()->flash('error', "The page is not found !");
            return redirect()->route('admin.exams.index');
        }
        $exam->deleted_at = null;
        $exam->deleted_by = null;
        $exam->save();

        session()->flash('success', 'exam has been revert back successfully !!');
        return redirect()->route('admin.exams.index');
    }

    /**
     * destroyTrash
     *
     * @param integer $id
     * @return void Destroy the data permanently
     */
    public function destroyTrash($id)
    {
        if (is_null($this->user) || !$this->user->can('exam.delete')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        $exam = Exam::find($id);
        if (is_null($exam)) {
            session()->flash('error', "The page is not found !");
            return redirect()->route('admin.exams.index');
        }

        // Delete exam permanently
        $exam->delete();

        session()->flash('success', 'Bản ghi đã được xóa!!');
        return redirect()->route('admin.exams.index');
    }

    /**
     * trashed
     *
     * @return view the trashed data list -> which data status = 0 and deleted_at != null
     */
    public function trashed()
    {
        if (is_null($this->user) || !$this->user->can('exam.view')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        return 1;
    }
    public function action(Request $request)
    {
        $method = $request->input('method', '');
        if ($method == 'per_page') {
            $this->per_page($request);
            return back();
        }else if ($method == 'confirm') {
            if (isset($request->ids)) {
                foreach ($request->ids as $key => $value) {
                    $record = Exam::where('id',$value)->where('confirm','=',0)->first();
                    if($record){
                        $record->confirm = auth()->user()->id;
                        $record->save();
                    }
                }
            }
            return back()->with('success', 'thành công!');
        }else if ($method == 'unconfirm') {
            if (isset($request->ids)) {
                foreach ($request->ids as $key => $value) {
                    $record = Exam::where('id',$value)->where('confirm','>',0)->first();
                    if($record){
                        $record->confirm = 0;
                        $record->save();
                    }
                }
            }
            return back()->with('success', 'thành công!');
        }
        else if ($method == 'delete') {
            if (isset($request->ids)) {
                foreach ($request->ids as $key => $value) {
                    $count_record = Exam::find($value)->delete();
                }
            }
            return back()->with('success', 'đã xóa ' . count($request->ids) . ' bản ghi');
        } else {
            return back()->with('success', 'thành công!');
        }
    }
}
