<?php

namespace App\Http\Controllers\Backend;

use App\Models\Department;
use App\Http\Controllers\Controller;

use App\Models\EmployeeDepartment;
use Illuminate\Http\Request;

class EmployeeDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *s
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $emp = EmployeeDepartment::where('code', $request->manhanvien)->first();

        $mission = Department::where(['code' => $request->manhanvien])->count();
        try {
            $department = Department::create([
                'name' => $request->name, //tên nhân viên
                'code' => $request->manhanvien, // mã nhân viên
            ]);
            return $this->success(compact('department'));
        } catch (\Exception $e) {
            return $this->error(['error', $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeDepartment  $employeeDepartment
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeDepartment $employeeDepartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeDepartment  $employeeDepartment
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeDepartment $employeeDepartment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeDepartment  $employeeDepartment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeDepartment $employeeDepartment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeDepartment  $employeeDepartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeDepartment $employeeDepartment)
    {
        //
    }
}
