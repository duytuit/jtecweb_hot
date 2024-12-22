<?php

namespace App\Models;

use App\Traits\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Exam extends Model
{
    use HasFactory,SoftDeletes,ActivityLogger;
    protected $guarded = [];

    protected $seachable = [
        'id',
        'name',
        'code',
        'sub_dept',
        'cycle_name',
        'create_date',
        'results',
        'total_questions',
        'status',
        'confirm',
        'counting_time',
        'limit_time',
        'data',
        'created_at',
        'updated_at',
        'deleted_at',
        'updated_by',
        'deleted_by',
        'mission',
        'scores',
        'type',
        'examinations',
        'date_examinations',
        'fail_aws'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'code','code');
    }
    public function user_confirm()
    {
        return $this->belongsTo(Admin::class, 'confirm', 'id');
    }
    // public function businesspartners()
    // {
    //     return $this->belongsTo(BusinessPartners::class, 'bdc_business_partners_id');
    // }

    public function scopeFilter($query, $input)
    {
        foreach ($this->seachable as $value) {
            if (isset($input[$value])) {
                $query->where($value, $input[$value]);
            }
        }
        // return $this->model
        //   ->with('handbook_category', 'pub_profile')
        //   ->where('bdc_building_id', $active_building)
        //   ->filter($keyword)
        //   ->orderBy('updated_at', 'DESC')
        //   ->paginate($per_page);

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
}
