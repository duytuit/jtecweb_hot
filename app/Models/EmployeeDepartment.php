<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ActivityLogger;

class EmployeeDepartment extends Model
{
    use HasFactory, SoftDeletes, ActivityLogger;
    protected $fillable = [
        'id',
        'employee_id',
        'department_id',
        'positions',
        'permissions',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
