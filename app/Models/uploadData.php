<?php

namespace App\Models;

use App\Traits\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class uploadData extends Model
{
    use HasFactory,SoftDeletes,ActivityLogger;
    protected $guarded = [];

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by', 'id');
    }
}
