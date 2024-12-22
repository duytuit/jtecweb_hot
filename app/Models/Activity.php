<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable =[
        'id',
        'user_id',
        'content_id',
        'content_type',
        'action',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'sql'
    ];
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
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
}
