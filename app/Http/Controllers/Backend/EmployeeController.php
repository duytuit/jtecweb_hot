<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\ArrayHelper;
use App\Helpers\UploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\Imports\EmployeeImport;
use App\Exports\EmployeeExport;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Helpers\StringHelper;
use App\Models\EmployeeDepartment;

class EmployeeController extends Controller
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
        if (is_null($this->user) || !$this->user->can('employee.view')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        // Phân trang
        $employees['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $employees['keyword'] = $request->input('keyword', null);
        $employees['advance'] = 0;
        if (count($request->except('keyword')) > 0) {
            // Tìm kiếm nâng cao
            $employees['advance'] = 1;
            $employees['filter'] = $request->all();
        }
        $depts = Department::where('status',1)->get();
        $employees['lists'] = Employee::where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->worker) && $request->worker != null) {
                $query->where('worker', $request->worker);
            }
            if (isset($request->dept) && $request->dept != null) {
                $query->whereHas('employeeDepartment',function ($query) use ($request) {
                    $query->where('department_id', $request->dept);
                });
            }
            if (isset($request->ids) && $request->ids != null && count($request->ids) > 0) {
                $query->whereIn('id', $request->ids);
            }
            if (isset($request->positions) && $request->positions != null) {
                $query->where('positions', $request->positions);
            }
            if (isset($request->from_date) && isset($request->to_date)) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('begin_date_company', '>=', $from_date);
                $query->whereDate('begin_date_company', '<=', $to_date);
            }
        })->orderBy('id','desc')->paginate($employees['per_page']);
        $workers = ArrayHelper::worker();
        $positions = ArrayHelper::positionTitle();

        return view('backend.pages.employees.index', compact('workers', 'positions','depts'), $employees);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('employee.create')) {
            return abort(403, 'You are not allowed to access this page !');
        }
        $data['departments'] = Department::all();
        $roles = DB::table('roles')->get();
        $positions = ArrayHelper::positionTitle();
        $maritals = ArrayHelper::marital();
        $workers = ArrayHelper::worker();
        $banksLists = ArrayHelper::banksList();
        return view('backend.pages.employees.create', compact('roles', 'positions', 'maritals', 'workers', 'banksLists'), $data);
    }
    public function exportExcel(Request $request)
    {
        $data = Employee::where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->worker) && $request->worker != null) {
                $query->where('worker', $request->worker);
            }
            if (isset($request->positions) && $request->positions != null) {
                $query->where('positions', $request->positions);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
        })->orderBy('code')->get();
        return (new EmployeeExport($data))->download('Employee-export.xlsx');
    }
    public function action(Request $request)
    {
        $method = $request->input('method', '');
        if ($method == 'per_page') {
            $this->per_page($request);
            return back();
        } else if ($method == 'report') {
            if (isset($request->ids)) {
                foreach ($request->ids as $key => $value) {
                    $employee = Employee::find($value);
                    $employee->status_exam =1;
                    $employee->save();
                }
            }
            return back()->with('success', 'Thay đổi trạng thái thành công');
        }
        else if ($method == 'unreport') {
            if (isset($request->ids)) {
                foreach ($request->ids as $key => $value) {
                    $employee = Employee::find($value);
                    $employee->status_exam =0;
                    $employee->save();
                }
            }
            return back()->with('success', 'Thay đổi trạng thái thành công');
        } else {
            return back()->with('success', 'thành công!');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:employees,code',
            'last_name' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $full_name = $request->last_name;
            $_full_name = explode(' ',$full_name);
            $last_name= $_full_name[count($_full_name)-1];
            $first_name = explode($last_name,$full_name);
            // Tạo nhân viên
            $employee = new Employee(); // Tạo một đối tượng Employee mới

            $employee->code = $request->code;
            $employee->first_name = $first_name[0];
            $employee->last_name = $_full_name[count($_full_name)-1];
            $employee->begin_date_company =$request->begin_date_company ? Carbon::parse($request->begin_date_company)->format('Y-m-d'):null;
            $employee->status = 1;
            $employee->created_by = Auth::user()->id;
            // $employee->identity_card = $request->identity_card;
            $employee->birthday =$request->birthday ? Carbon::parse($request->birthday)->format('Y-m-d') : null;
            // $employee->addresss = $request->addresss;
            $employee->dept_id = $request->department_id;
            $employee->marital = 0;
            $employee->worker = 3;
            $employee->positions = 11;
            // $employee->phone = $request->phone;
            $employee->email = $request->code.'@jtec-hn.com.vn';
            // $employee->bank_number = $request->bank_number;
            // $employee->bank_name = $request->bank_name;
            $employee->end_date_company =$request->end_date_company ? Carbon::parse($request->end_date_company)->format('Y-m-d') : null;
            if (!is_null($request->avatar)) {
                $employee->avatar = UploadHelper::upload('avatar', $request->avatar, $request->last_name . '-' . time(), 'public/assets/images/avatar');
            }

            $employee->save();
            $employee_dept = EmployeeDepartment::where(['employee_id'=>$employee->id])->first();
            if(!$employee_dept){
                EmployeeDepartment::create([
                    'employee_id' => $employee->id,
                    'department_id' => $request->department_id,
                    'created_by' => Auth::user()->id,
                ]);
            }else{
                $employee_dept->department_id= $request->department_id;
                $employee_dept->save();
            }
            //Tạo tài khoản
            $admin = new Admin();
            $admin->first_name = $first_name[0];
            $admin->last_name = $_full_name[count($_full_name)-1];
            if ($request->username) {
                $admin->username = $request->username;
            } else {
                $admin->username = $request->code;
            }

            $admin->email = $request->code.'@jtec-hn.com.vn';
            $admin->password = Hash::make($request->code);

            $admin->status = 1;
            $admin->created_by = Auth::id();
            $admin->employee_id = $employee->id;
            $admin->save();

            // Assign Roles
            $admin->assignRole("Worker");
            DB::commit();
            session()->flash('success', 'Thêm mới thành công');
            return redirect()->route('admin.employees.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (is_null($this->user) || !$this->user->can('employees.view')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        $employee = Employee::find($id);
        return view('backend.pages.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('employee.edit')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        $employee = Employee::find($id);
        $data['departments'] = Department::all();
        $data['admin'] = Admin::Where('username', $employee->code)->first();
        $roles = DB::table('roles')->get();
        $positions = ArrayHelper::positionTitle();
        $maritals = ArrayHelper::marital();
        $workers = ArrayHelper::worker();
        $banksLists = ArrayHelper::banksList();

        return view('backend.pages.employees.edit', compact('employee', 'roles', 'positions', 'maritals', 'workers', 'banksLists'), $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $isProfileUpdate = false)
    {
        $request->validate([
            'code' => 'required|unique:employees,code' . ($id ? ",$id" : ''),
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        $employee = Employee::find($id);
        $data['departments'] = Department::all();
        if (empty($employee)) {
            session()->flash('error', "The page is not found.");
            return redirect()->route('admin.employees.index');
        }
        $admin = Admin::find($request->adminId);
        if (is_null($admin)) {
            session()->flash('error', "The page is not found !");
            return redirect()->route('admin.admins.index');
        }
        try {
            $employee->code = $request->input('code');
            $employee->first_name = $request->input('first_name');
            $employee->last_name = $request->input('last_name');
            $employee->begin_date_company = $request->begin_date_company ? Carbon::parse($request->input('begin_date_company'))->format('Y-m-d'):null;
            $employee->status = $request->input('status');
            // $employee->created_by = $request->input('created_by');
            // $employee->identity_card = $request->input('identity_card');
            $employee->birthday = $request->birthday ? Carbon::parse($request->input('birthday'))->format('Y-m-d'):null;
            // $employee->addresss = $request->input('addresss');
            $employee->dept_id = $request->input('department_id');
            $employee->worker = $request->input('worker');
            $employee->positions = $request->input('positions');
            $employee->end_date_company = $request->end_date_company ? Carbon::parse($request->input('end_date_company'))->format('Y-m-d') : null;
            // $employee->avatar = $request->input('avatar');
            // $employee->phone = $request->input('phone');
            // $employee->email = $request->input('email');
            // $employee->bank_number = $request->input('bank_number');
            // $employee->bank_name = $request->input('bank_name');

            if (!is_null($request->avatar)) {
                $employee->avatar = UploadHelper::upload('avatar', $request->avatar, $request->last_name . '-' . time(), 'public/assets/images/avatar');
            }
            $employee->save();
            $employee_dept = EmployeeDepartment::where(['employee_id'=>$employee->id])->first();
            if(!$employee_dept){
                EmployeeDepartment::create([
                    'employee_id' => $employee->id,
                    'department_id' => $request->department_id,
                    'created_by' => Auth::user()->id,
                ]);
            }else{
                $employee_dept->department_id= $request->department_id;
                $employee_dept->save();
            }
            if (!$isProfileUpdate) {
                if (!is_null($request->input('password'))) {
                    $admin->password = Hash::make($request->input('password'));
                }
                $admin->username = $request->input('code');
                $admin->employee_id = $employee->id;
                $admin->save();
                // Detach roles and Assign Roles
                $admin->roles()->detach();

                if (!is_null($request->roles)) {
                    foreach ($request->roles as $role) {
                        $admin->assignRole($role);
                    }
                }
            }
            session()->flash('success', "Employee updated successfully.");
            if ($isProfileUpdate)    return back();
            return redirect()->route('admin.employees.index');
        } catch (\Exception $e) {
            session()->flash('error', "Failed to update Employee: " . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
    public function destroyTrash($id)
    {
        $employee = Employee::find($id);
        if (is_null($employee)) {
            session()->flash('error', "Nội dung đã được xóa hoặc không tồn tại !");
            return redirect()->route('admin.employee.index');
        }
        $employee->deleted_at = Carbon::now();
        $employee->deleted_by = Auth::id();
        $employee->status = 0;
        $employee->save();
        $employee->delete();
        session()->flash('success', 'Đã xóa bản ghi thành công !!');
        return redirect()->route('admin.employees.index');
    }
    public function ajaxGetSelectByName(Request $request)
    {
        if ($request->search) {
            $where[] = ['last_name', 'like', '%' . $request->search . '%'];
            $orwhere[] = ['code', 'like', '%' . $request->search . '%'];
            return response()->json(Employee::searchByAll(['where' => $where,'orwhere'=>$orwhere]));
        }
        return response()->json(Employee::searchByAll(['select' => ['id', 'code', 'first_name', 'last_name']]));
    }

}
