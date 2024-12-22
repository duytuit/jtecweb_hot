<?php

namespace App\Models;

use App\Traits\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignatureSubmission extends Model
{
    use HasFactory, ActivityLogger;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'required_id',
        'department_id',
        'content',
        'positions',
        'approve_id',
        'signature_id',
        'status',
        'type',
        'deleted_at',
        'created_at',
        'updated_at',

    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'signature_id', 'id');
    }
}
