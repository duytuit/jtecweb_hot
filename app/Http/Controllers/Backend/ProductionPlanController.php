<?php
namespace App\Http\Controllers\Backend;

use App\Helpers\ArrayHelper;
use App\Helpers\RedisHelper;
use App\Helpers\UploadHelper;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ProductionPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class ProductionPlanController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       // Phân trang
       $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
       $data['filter'] = $request->all();
       $data['filter']['bp'] = $request->get('bp',1);
       $data['lists'] = ProductionPlan::where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->where('code', 'like','%'.$request->keyword.'%');
                    //   ->orWhere('kttm', 'like','%'.$request->keyword.'%')
                    //   ->orWhere('ktnq', 'like','%'.$request->keyword.'%')
                    //   ->orWhere('dap1', 'like','%'.$request->keyword.'%')
                    //   ->orWhere('dap2', 'like','%'.$request->keyword.'%')
                    //   ->orWhere('cam', 'like','%'.$request->keyword.'%')
                    //   ->orWhere('cat', 'like','%'.$request->keyword.'%');
            }
           if (isset($request->status) && $request->status != null) {
               $query->where('status', $request->status);
           }
       })->whereNotnull('description')->paginate($data['per_page']);
       $data['asyncProductionPlan'] = RedisHelper::getKey('update_EmployeeProductionPlan');
       $data['productionPlanHeaderWeekday']= Cache::get('productionPlanHeaderWeekday');
       $data['productionPlanKTNQ']= Cache::get('productionPlanKTNQ');
       $data['productionPlanKTTM']= Cache::get('productionPlanKTTM');
       return view('backend.pages.productionPlan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('asset.create')) {
            return abort(403, 'Bạn không có quyền truy cập trang này !');
        }
        return view('backend.pages.productionPlan.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function asyncProductionPlan()
    {
        RedisHelper::setKey('checkAsyncProductionPlan',true);
        return redirect()->back()->with('success','Đang bắt đầu xử lý đồng bộ.');
    }

    public function asyncKTNQ(){
        RedisHelper::setKey('checkAsyncKTNQ',true);
        return redirect()->back()->with('success','Đang bắt đầu xử lý đồng bộ.');
    }
    public function asyncKTTM(){
        RedisHelper::setKey('checkAsyncKTTM',true);
        return redirect()->back()->with('success','Đang bắt đầu xử lý đồng bộ.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required|unique:productionPlan,name'
        ]);
        try {
            $image = null;
            if (!is_null($request->image)) {
                $image = UploadHelper::upload('image', $request->image, $request->name . '-' . time(), 'public/productionPlan/images/asset');
            }
            Asset::create([
                'code'=>$request->code,
                'name'=> $request->name,
                'image'=>$image,
                'note'=> $request->note,
                'model'=> $request->model,
                'color'=> $request->color,
                'manager_by' => $request->manager_by,
                'status'=> @$request->status ? 1 : 0,
                'created_by'=>Auth::user()->id,
                'updated_by'=>Auth::user()->id,
            ]);
            session()->flash('success', 'Thêm mới thành công');
            return back();
        } catch (\Exception $e) {
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        // if (is_null($this->user) || !$this->user->can('asset.edit')) {
        //     $message = 'Bạn không có quyền truy cập trang này !';
        //     return view('errors.403', compact('message'));
        // }
        $data['type'] = $request->type;
        $data['productionPlan'] = ProductionPlan::find($id);
        if(! $data['productionPlan']){
            return back()->with('warning','không tìm thấy dữ liệu');
        }
        if($request->type == 1){ // kiểm tra ngoại quan
            $data['data_productionPlan']= Cache::get('productionPlanKTNQ');
        }
        if($request->type == 2){ // kiểm tra thông mạch
            $data['data_productionPlan']= Cache::get('productionPlanKTTM');
        }
        return view('backend.pages.productionPlan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function updateKTNQ(Request $request, $id)
    {
        $productionPlan = ProductionPlan::find($id);
        if(!$productionPlan){
            return back()->with('warning','không tìm thấy dữ liệu');
        }
        try {
            $_productionPlan = $request->productionPlan;
            // dd($_productionPlan);
            if ($_productionPlan && count($_productionPlan['column_name']) > 0) {
                $column_names = $_productionPlan['column_name'];
                $_column_data = [];
                $ktnq = [];
                foreach ($column_names as $key => $value) {
                    $column_slug  =  Str::slug($value, "_");
                    $data_type  = @$_productionPlan['data_type'][$key];
                    $data_row  = @$_productionPlan['data_row'][$key];
                    $data_row_temp  = @$_productionPlan['data_row_temp'][$key];
                    $order  = @$_productionPlan['order'][$key];

                    if ($data_row && !is_string($data_row)) {
                        $__file = $data_row->getClientOriginalName();
                        $filename = pathinfo($__file, PATHINFO_FILENAME);
                        $extension = pathinfo($__file, PATHINFO_EXTENSION);
                        $filename_array = explode(".", $filename);
                        $file_path = UploadHelper::uploadv2($data_row, $filename_array[0], 'public/productionPlan/files');
                        $ktnq[$order] = $file_path;
                    } else {
                        $ktnq[$order] = $data_row ?? $data_row_temp;
                    }

                    $_column_data[] = [
                        'order'  => $order,
                        'column_name'  => $value,
                        'column_slug'  => $column_slug,
                        'data_type'  => $data_type
                    ];
                }
                $productionPlan->ktnq = json_encode($ktnq);
                $productionPlan->save();
                Cache::put('productionPlanKTNQ', $_column_data);
            }
            session()->flash('success', 'Cập nhật thành công');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage().$e->getLine());
        }
    }
    public function updateKTTM(Request $request, $id)
    {
        $productionPlan = ProductionPlan::find($id);
        if(!$productionPlan){
            return back()->with('warning','không tìm thấy dữ liệu');
        }
        try {
            $_productionPlan = $request->productionPlan;
            // dd($_productionPlan);
            if ($_productionPlan && count($_productionPlan['column_name']) > 0) {
                $column_names = $_productionPlan['column_name'];
                $_column_data = [];
                $kttm = [];
                foreach ($column_names as $key => $value) {
                    $column_slug  =  Str::slug($value, "_");
                    $data_type  = @$_productionPlan['data_type'][$key];
                    $data_row  = @$_productionPlan['data_row'][$key];
                    $data_row_temp  = @$_productionPlan['data_row_temp'][$key];
                    $order  = @$_productionPlan['order'][$key];

                    if ($data_row && !is_string($data_row)) {
                        $__file = $data_row->getClientOriginalName();
                        $filename = pathinfo($__file, PATHINFO_FILENAME);
                        $extension = pathinfo($__file, PATHINFO_EXTENSION);
                        $filename_array = explode(".", $filename);
                        $file_path = UploadHelper::uploadv2($data_row, $filename_array[0], 'public/productionPlan/files');
                        $kttm[$order] = $file_path;
                    } else {
                        $kttm[$order] = $data_row ?? $data_row_temp;
                    }

                    $_column_data[] = [
                        'order'  => $order,
                        'column_name'  => $value,
                        'column_slug'  => $column_slug,
                        'data_type'  => $data_type
                    ];
                }
                $productionPlan->kttm = json_encode($kttm);
                $productionPlan->save();
                Cache::put('productionPlanKTTM', $_column_data);
            }
            session()->flash('success', 'Cập nhật thành công');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage().$e->getLine());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asset $asset)
    {
        //
    }
    public function action(Request $request)
    {
        $method = $request->input('method', '');
        if ($method == 'per_page') {
            $this->per_page($request);
            return back();
        } else {
            return back();
        }
    }
}
