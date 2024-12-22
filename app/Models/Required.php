<?php

namespace App\Models;

use App\Traits\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Required extends Model
{
    use HasFactory, ActivityLogger,SoftDeletes;
    // protected $table = 'requireds';
    protected $guarded = [];

    protected $seachable  = [
        'code',
        'code_required',
        'confirm_form',
        'location',
    ];

    const required_type = [
        0=>"Dây điện",
        1=>"Tanshi",
        2=>"Ống",
        3=>"Băng dính",
        4=>"Keo",
        5=>"Thiếc",
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
    static function groupMachineRequired()
    {
        $rs = Cache::store('redis')->get('groupMachineRequired');
        if($rs)return $rs;
        $rs = Required::select('pc_name')->where('from_type',0)->distinct()->orderBy('pc_name')->get();
        if(!$rs)return false;
        Cache::store('redis')->put('groupMachineRequired', $rs,60*60*12);
        return $rs;
    }
    public function accessory()
    {
        return $this->belongsTo(Accessory::class, 'code', 'code');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'created_by', 'id');
    }
    public function deleteBy()
    {
        return $this->belongsTo(Employee::class, 'deleted_by', 'id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'required_department_id', 'id');
    }
    public function signatureSubmission()
    {
        return $this->hasMany(SignatureSubmission::class, 'required_id');
    }
    public function signature_Submission()
    {
        return $this->hasMany(SignatureSubmission::class, 'required_id');
    }
    static function printPdf($html)
    {
        $post_fields['Html'] = $html;
        $post_fields['PrinterName'] = 'SATO CG412';
        $post_fields['Landscape'] ='false';
        $post_fields['Width'] = '730';
        $post_fields['Height'] =  '930';
        $curl_handle = curl_init('http://192.168.207.6:8092/printpdffromhtml/html-pdf');
        curl_setopt($curl_handle, CURLOPT_HEADER, 0);
        curl_setopt($curl_handle, CURLOPT_VERBOSE, 0);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($curl_handle, CURLOPT_POST, true);
        @curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post_fields);
        $returned_data = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $returned_data;
    }
    static function printEdp($html)
    {
        $post_fields['Html'] = $html;
        $post_fields['PrinterName'] = 'RICOH Pro 8300S PCL 6';
        $post_fields['Landscape'] ='false';
        $post_fields['Width'] = '827';
        $post_fields['Height'] =  '1169';
        $curl_handle = curl_init('http://192.168.207.6:8092/printpdffromhtml/html-pdf');
        curl_setopt($curl_handle, CURLOPT_HEADER, 0);
        curl_setopt($curl_handle, CURLOPT_VERBOSE, 0);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($curl_handle, CURLOPT_POST, true);
        @curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post_fields);
        $returned_data = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $returned_data;
    }
}
