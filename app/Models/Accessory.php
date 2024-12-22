<?php

namespace App\Models;

use App\Traits\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Accessory extends Model
{
    use HasFactory,ActivityLogger;
    protected $guarded = [];

    protected $fillable = [
        'code',
        'location_k',
        'location_c',
        'location',
        'material_norms',
        'image',
        'unit',
        'status',
        'inventory',
        'accessory_dept',
        'invoice_data',
        'type',
        'note_type',
    ];
    protected $seachable = [
       'code',
        'location_k',
        'location_c',
        'location',
        'material_norms',
        'image',
        'unit',
        'status'
    ];
       // $store = DB::connection('oracle')
        //         ->table('TAD_Z60M')
        //         ->where('場所C','like', '%0111%')
        //         ->where('品目K','like', '%7%')
        //         ->where('品目C','like', 'AVS5B%')->get();
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
    public static function searchByAll(array $options = [])
    {
        $default = [
            'select'   => '*',
            'where'    => [],
            'orwhere'    => [],
            'order_by' => 'id DESC',
            'per_page' => 10,
        ];

        $options = array_merge($default, $options);
        extract($options);
        $model = self::select($options['select']);
        if ($options['where']) {
            $model = $model->where(function($query) use($options){
                                   $query->where($options['where']);
            });
        }
        return $model->orderByRaw($options['order_by'])->paginate($options['per_page']);
    }
    public static function findById($id)
    {
        $rs = Cache::store('redis')->get('findAccessoryById_'.$id);
        if($rs)return $rs;
        $rs = Accessory::find($id);
        if(!$rs)return false;
        Cache::store('redis')->put('findAccessoryById_'.$id, $rs,60*60*24);
        return $rs;
    }
    public static function findByCode($code)
    {
        $rs = Cache::store('redis')->get('findAccessoryByCode_'.$code);
        if($rs)return $rs;
        $rs = Accessory::where('code',$code)->first();
        if(!$rs)return false;
        Cache::store('redis')->put('findAccessoryByCode_'.$code, $rs,60*60*24);
        return $rs;
    }
}
