<?php

namespace App\Models;

use App\Traits\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DynamicColumn extends Model
{
    use HasFactory,SoftDeletes,ActivityLogger;
    protected $guarded = [];
}
