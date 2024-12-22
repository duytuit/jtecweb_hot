<?php

namespace App\Models;

use App\Traits\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeProductionPlan extends Model
{
    use HasFactory,ActivityLogger;
    protected $table = 'production_plans';
    protected $guarded = [];
}
