<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DepartmentImport;
use App\Exports\DepartmentExport;
use App\Helpers\ArrayHelper;
use App\Models\Admin;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;



class DepartmentController extends Controller
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
        if (is_null($this->user) || !$this->user->can('department.view')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        // Phân trang
        $department['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $department['keyword'] = $request->input('keyword', null);
        $department['advance'] = 0;
        if (count($request->except('keyword')) > 0) {
            // Tìm kiếm nâng cao
            $department['advance'] = 1;
            $department['filter'] = $request->all();
        }

        $department['lists'] = Department::where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
        })->paginate($department['per_page']);
        return view('backend.pages.departments.index', $department);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('department.create')) {
            return abort(403, 'You are not allowed to access this page !');
        }
        return view('backend.pages.departments.create');
    }

    public function importExcelData(Request $request)
    {
        $request->validate([
            'import_file' => [
                'required',
                'file',
            ],
        ]);
        Excel::import(new DepartmentImport, $request->file('import_file'));
        session()->flash('success', 'Thêm mới thành công');
        return response()->json('uploaded successfully');
    }
    public function exportExcel(Request $request)
    {
        $data = Department::where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
        })->orderBy('code')->get();
        return (new DepartmentExport($data))->download('Department-export.xlsx');
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
            'code' => 'required',
            'name' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $department = Department::create([
                'code' => $request->code,
                'name' => $request->name,
                'parent_id' => 0,
                'status' => @$request->status ? 1 : 0,
                'created_by' => Auth::user()->id,
            ]);
            DB::commit();
            session()->flash('success', 'Thêm mới thành công');
            return redirect()->route('admin.departments.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (is_null($this->user) || !$this->user->can('departments.view')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        $department = Department::find($id);
        return view('backend.pages.departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('department.edit')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        $department = Department::find($id);
        $employeeDepartments = EmployeeDepartment::Where('department_id', $id)->where(function ($query) use ($request) {
            if (isset($request->ids) && $request->ids != null && count($request->ids) > 0) {
                $query->whereIn('employee_id', $request->ids);
            }
        })->get();
        $positionTitles = ArrayHelper::positionTitle();
        $employee['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $employee['lists'] = Employee::where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
        })->paginate($employee['per_page']);

        $data['roles'] = DB::table('roles')->get();
        return view('backend.pages.departments.edit', compact('department', 'employeeDepartments', 'employee', 'positionTitles'),$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $department = Department::find($id);

        if (empty($department)) {
            session()->flash('error', "The page is not found.");
            return redirect()->route('admin.departments.index');
        }

        // Update department
        try {
            $department->name = $request->input('name');
            $department->code = $request->input('code');
            $department->status = $request->input('status');
            $department->permissions = json_encode($request->roles);
            $employeeDepartment = EmployeeDepartment::where('department_id',$department->id)->get();
            foreach ($employeeDepartment as $key => $value) {
                $emp = Employee::findEmployeeById($value->employee_id);
                if($emp){
                    $admin = Admin::where('username',$emp->code)->first();
                    if($admin){
                        if ($request->roles != null) {
                            foreach ($request->roles as $role) {
                                $admin->assignRole($role);
                            }
                        }
                    }
                }
            }
            $department->save();
            session()->flash('success', "Department updated successfully.");
            return redirect()->route('admin.departments.index');
        } catch (\Exception $e) {
            session()->flash('error', "Failed to update department: " . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroyTrash($id)
    {
        $department = Department::find($id);
        if (is_null($department)) {
            session()->flash('error', "Nội dung đã được xóa hoặc không tồn tại !");
            return redirect()->route('admin.department.index');
        }
        $department->deleted_at = Carbon::now();
        $department->deleted_by = Auth::id();
        $department->status = 0;
        $department->save();
        $department->delete();
        session()->flash('success', 'Đã xóa bản ghi thành công !!');
        return redirect()->route('admin.departments.index');
    }
    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('department.delete')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }

        $department = Department::find($id);
        if (is_null($department)) {
            session()->flash('error', "Nội dung đã được xóa hoặc không tồn tại !");
            return redirect()->route('admin.department.index');
        }
        $department->deleted_at = Carbon::now();
        $department->deleted_by = Auth::id();
        $department->status = 0;
        $department->save();

        session()->flash('success', 'Đã xóa bản ghi thành công !!');
        return redirect()->route('admin.department.index');
    }
    public function action(Request $request)
    {
        $method = $request->input('method', '');
        if ($method == 'per_page') {
            $this->per_page($request);
            return back();
        } else if ($method == 'restore_apartment') {
            return back()->with('success', 'thành công!');
        } else if ($method == 'delete') {
            if (isset($request->ids)) {
                foreach ($request->ids as $key => $value) {
                    $count_record = Department::find($value)->delete();
                }
            }
            return back()->with('success', 'đã xóa ' . count($request->ids) . ' bản ghi');
        } else {
            return back()->with('success', 'thành công!');
        }
    }
    public function ajaxGetSelectCode(Request $request)
    {
        if ($request->search) {
            $where[] = ['code', 'like', '%' . $request->search . '%'];
            return response()->json(Employee::searchByAll(['where' => $where]));
        }
        return response()->json(Employee::searchByAll(['select' => ['id', 'code', 'first_name', 'last_name']]));
    }
    public function addEmployeeIntoDepartment(Request $request)
    {
        $ids = json_decode($request->ids);
        foreach ($ids as $key => $id) {
            $_employeeDepartment = EmployeeDepartment::where('employee_id', $id)->where('department_id',$request->departmentId)->first();
            Cache::store('redis')->forget('get_departments_by_id_'.$id);
            if (!$_employeeDepartment) {
                EmployeeDepartment::create([
                    'employee_id' => $id,
                    'department_id' => $request->departmentId,
                    'created_by' => Auth::user()->id,
                ]);
            }
            // else{
            //     $_employeeDepartment->department_id= $request->departmentId;
            //     $_employeeDepartment->save();
            // }
        }
    }
    public function changePositionTitle(Request $request)
    {
        $employeeDepartmentId = $request->input('employeeDepartmentId');
        $positions = $request->input('positionTitle');
        $employeeDepartments = EmployeeDepartment::where('id', '<>', $employeeDepartmentId)->get();
        EmployeeDepartment::where('id', $employeeDepartmentId)->update([
            'positions' => $positions
        ]);
        foreach ($employeeDepartments as $employeeDepartment) {
            if ($employeeDepartment->positions == $positions && !in_array($employeeDepartment->positions, [3, 4, 5])) { // trừ sub assiston leader và leader
                $employeeDepartment->positions = 0;
                $employeeDepartment->save();
            }
        }
        return response()->json(['message' => 'Đã lưu giá trị thành công!']);
    }
    public function changePermissions(Request $request)
    {
        $employeeDepartmentId = $request->input('employeeDepartmentId');
        $permissions = $request->input('permissions');
        $employeeDepartments = EmployeeDepartment::where('id', '<>', $employeeDepartmentId)->get();
        EmployeeDepartment::where('id', $employeeDepartmentId)->update([
            'permissions' =>  json_encode($permissions)
        ]);
        foreach ($employeeDepartments as $employeeDepartment) {
           $_permissions = json_decode($employeeDepartment->permissions);
           if($_permissions){
                $__permissions = $_permissions;
                foreach ($_permissions as $key => $value) {
                    if (in_array($value, $permissions) && !in_array($value, [3, 4, 5])) { // trừ sub assiston leader và leader
                       // dd($__permissions);
                       $__permissions = array_diff($__permissions, [$value] );
                    }
                }
                $employeeDepartment->permissions = json_encode($__permissions);
                $employeeDepartment->save();
           }
        }
        return response()->json(['message' => 'Đã lưu giá trị thành công!']);
    }

    public function destroyEmployeeDepartments(Request $request)
    {
        $id = $request->input('id');
        if (is_null($this->user) || !$this->user->can('department.delete')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        $employeeDepartments = EmployeeDepartment::find($id);
        if (is_null($employeeDepartments)) {
            session()->flash('error', "Nội dung đã được xóa hoặc không tồn tại !");
            return redirect()->route('admin.employeeDepartments.index');
        }
        Cache::store('redis')->forget('get_departments_by_id_'.$employeeDepartments->employee_id);
        $employeeDepartments->deleted_at = Carbon::now();
        $employeeDepartments->deleted_by = Auth::id();
        $employeeDepartments->save();

        session()->flash('success', 'Đã xóa bản ghi thành công !!');
        return redirect()->route('admin.departments.edit');
    }
}
