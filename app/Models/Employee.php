<?php

namespace App\Models;

use App\Traits\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    use HasFactory, SoftDeletes, ActivityLogger;
    // protected $table = 'employees';
    protected $guarded = [];

    protected $seachable = [
        'id',
        'code',
        'first_name',
        'last_name',
        'identity_card',
        'native_land',
        'addresss',
        'birthday',
        'unit_id',
        'dept_id',
        'team_id',
        'process_id',
        'status',
        'marital',
        'worker',
        'positions',
        'begin_date_company',
        'end_date_company',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    // public function department()
    // {
    //     return $this->belongsTo(Department::class, 'name', '');
    // }
    public function scopeFilter($query, $input)
    {
        foreach ($this->seachable as $value) {
            if (isset($input[$value])) {
                $query->where($value, $input[$value]);
            }
        }
        if (isset($input['keyword'])) {
            $search = $input['keyword'];
            $query->where(function ($q) use ($search) {
                foreach ($this->seachable as $value) {
                    $q->orWhere($value, 'LIKE', '%' . $search . '%');
                }
            });
        }
        return $query;
    }

    public static function searchByAll(array $options = [])
    {
        $default = [
            'select'   => '*',
            'where'    => [],
            'orwhere'    => [],
            'order_by' => 'id DESC',
            'per_page' => 20,
        ];

        $options = array_merge($default, $options);
        extract($options);

        $model = self::select($options['select']);
        if ($options['where']) {
            $model = $model->where(function($query) use($options){
                                   $query->where($options['where'])
                                         ->orWhere($options['orwhere']);
            });
        }

        return $model->orderByRaw($options['order_by'])->paginate($options['per_page']);
    }
    public function required()
    {
        return $this->belongsTo(Required::class, 'code_required', 'code');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'code', 'code');
    }
    public function employeeDepartment()
    {
        return $this->belongsTo(EmployeeDepartment::class, 'id', 'employee_id');
    }
    public function employeeDepartments()
    {
        return $this->hasMany(EmployeeDepartment::class, 'employee_id', 'id');
    }
    public static function findEmployeeById($id)
    {
        $rs = Cache::store('redis')->get('findEmployeeById_'.$id);
        if($rs)return $rs;
        $rs = Employee::find($id);
        if(!$rs)return false;
        Cache::store('redis')->put('findEmployeeById_'.$id, $rs,60*60*24);
        return $rs;
    }
    public static function get_departments_by_id($employee_id){
        $rs = Cache::store('redis')->get('get_departments_by_id_'.$employee_id);
        if($rs)return $rs;
        $employee_departments = DB::table('employee_departments')->select('department_id')->whereNull('deleted_at')->where('employee_id',$employee_id)->pluck('department_id');
        if($employee_departments->count() == 0)return false;
        $departments = DB::table('departments')->whereNull('deleted_at')->whereIn('id',$employee_departments->toArray())->get();
        if($departments->count() == 0)return false;
        Cache::store('redis')->put('get_departments_by_id_' . $employee_id, $departments,60*60*24);
        return $departments;
    }
}
