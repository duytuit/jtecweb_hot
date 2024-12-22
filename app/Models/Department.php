<?php

namespace App\Models;

use App\Traits\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Department extends Model
{
    use HasFactory, ActivityLogger,SoftDeletes;
    protected $table = 'departments';
    protected $fillable = [
        'id',
        'code',
        'name',
        'parent_id',
        'status',
        'created_by',
        'updated_by',
        'permissions',
        'dateted_by',
        'deleted_at',
        'create_at',
        'updated_at',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'code', 'code');
    }
    public function employeeDepartmentByCount()
    {
        return $this->hasMany(EmployeeDepartment::class, 'department_id')->select('id')->count();
    }
    public static function findById($id)
    {
        $rs = Cache::store('redis')->get('findDepartmentById_'.$id);
        if($rs)return $rs;
        $rs = Department::find($id);
        if(!$rs)return false;
        Cache::store('redis')->put('findDepartmentById_'.$id, $rs,60*60*24);
        return $rs;
    }
}
