<?php

namespace App\Models;

use App\Traits\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes, ActivityLogger;
    protected $guarded = [];
    protected $fillable =[
        'code',
        'name',
        'image',
        'note',
        'status',
        'model',
        'color',
        'manager_by',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function scopeFilter($query, $input)
    {
        // foreach ($this->fillable as $value) {
        //     if (isset($input[$value])) {
        //         $query->where($value, $input[$value]);
        //     }
        // }
        if (isset($input['keyword'])) {
            $search = $input['keyword'];
            $query->where(function ($q) use ($search) {
                foreach ($this->fillable as $value) {
                    $q->orWhere($value, 'LIKE', '%' . $search . '%');
                }
            });
        }
        return $query;
    }
    public function user()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id');
    }
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_by', 'id');
    }
}
