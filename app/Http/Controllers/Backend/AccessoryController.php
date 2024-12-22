<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\RedisHelper;
use App\Helpers\UploadHelper;
use App\Http\Controllers\Controller;
use App\Imports\EmpImport;
use App\Models\Accessory;
use App\Models\Exam;
use App\Models\LogImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AccessoryController extends Controller
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

        if (is_null($this->user) || !$this->user->can('accessory.view')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        // Phân trang
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['keyword'] = $request->input('keyword', null);
        $data['advance'] = 0;
        if (count($request->except('keyword')) > 0) {
            // Tìm kiếm nâng cao
            $data['advance'] = 1;
            $data['filter'] = $request->all();
        }
        $data['lists'] = Accessory::where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->filter($request);
            }
            if (isset($request->status) && $request->status != null) {
                $query->where('status', $request->status);
            }
            if (isset($request->from_date) && isset($request->to_date)) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('created_at', '>=', $from_date);
                $query->whereDate('created_at', '<=', $to_date);
            }
        })->orderBy('created_at','desc')->paginate($data['per_page']);
        $data['update_asyncInvoice'] = RedisHelper::getKey('update_asyncInvoice');
        return view('backend.pages.accessorys.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function syncAccessory(Request $request)
    {
            set_time_limit(0);
            // $sql = 'SELECT * FROM TAD_Z60M GROUP BY "品目C" ORDER BY "品目C"';
            // $getList = DB::connection('oracle')->select($sql);
            $getList = DB::connection('oracle')->table('TAD_Z60M')->select('品目c','品目k','場所c','棚番')->get();
            echo "Đang thực hiện đồng bộ... <br>";
            foreach ($getList as $key => $value) {
                $accessory_dept=[];
                $_accessory_dept=[];
                try {
                    $_accessory = Accessory::where('code',trim($value->品目c))->first();
                    if(!$_accessory){
                        $accessory_dept[]=[
                            'location_c' => trim($value->場所c),// mã công đoạn
                            'location_k' => trim($value->品目k),
                            'inventory' =>0
                        ];
                        Accessory::create([
                            'code'=> trim($value->品目c),
                            'location_k'=> trim($value->品目k),
                            'location_c'=> trim($value->場所c),
                            'location'=> trim($value->棚番),
                            'accessory_dept'=> json_encode($accessory_dept),
                            'status'=>1
                        ]);
                    }else{
                        $str1 = trim($value->品目c);
                        $str2 = trim($_accessory->code);
                        if($str1 == $str2){
                            $___accessory_dept = json_decode($_accessory->accessory_dept);
                            if(!$___accessory_dept){
                                $_accessory_dept[]=[
                                    'location_c' => trim($value->場所c),// mã công đoạn
                                    'location_k' => trim($value->品目k),
                                    'inventory' => 0
                                ];
                                $_accessory->accessory_dept = json_encode($_accessory_dept);
                                $_accessory->save();
                            }else{
                                $check = true;
                                foreach ($___accessory_dept as $key_1 => $value_1) {
                                    if($value_1->location_c == trim($value->場所c)){
                                        $check = false;
                                    }
                                }
                                if($check == true){
                                    $_accessory_dept[]=[
                                        'location_c' => trim($value->場所c),// mã công đoạn
                                        'location_k' => trim($value->品目k),
                                        'inventory' => 0
                                    ];
                                    $accessory_dept_new = array_merge($___accessory_dept, $_accessory_dept);
                                    $_accessory->accessory_dept = json_encode($accessory_dept_new);
                                    $_accessory->save();
                                }
                            }

                        }else{
                            $accessory_dept[]=[
                                'location_c' => trim($value->場所c),// mã công đoạn
                                'location_k' => trim($value->品目k),
                                'inventory' =>0
                            ];
                            Accessory::create([
                                'code'=> trim($value->品目c),
                                'location_k'=> trim($value->品目k),
                                'location_c'=> trim($value->場所c),
                                'location'=> trim($value->棚番),
                                'accessory_dept'=> json_encode($accessory_dept),
                                'status'=>1
                            ]);
                        }

                    }
                    } catch (\Exception $e) {
                        LogImport::create([
                            'type' => 1,
                            'status' => 0,
                            'data' => "syncAccessory",
                            'messages' => $e->getLine().'||'.$e->getMessage().'||'.json_encode($accessory_dept)
                        ]);
                    }
                }
            echo "Đã đồng bộ xong. <br>";
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.pages.accessorys.create');
    }
    public function saveInvoice(Request $request)
    {
        try {
            set_time_limit(0);
            $data = Excel::toCollection(new EmpImport, request()->file('import_file'));
            RedisHelper::queueSet('asyncInvoiceData', $data[0]);

            RedisHelper::setKey('asyncInvoice',true);
            return back()->with('success', 'Đang bắt đầu đồng bộ.');
        } catch (\Exception $e) {
            session()->flash('sticky_error', $e->getMessage());
            return back();
        }
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
            'code' => 'required|unique:accessories,code',
        ]);
        try {
            $accessory_dept[]=[
                'location_c' => '1523',// mã công đoạn
                'location_k' => '7',
                'inventory' =>0
            ];
            $accessory_dept[]=[
                'location_c' => '0111',// mã công đoạn
                'location_k' => '7',
                'inventory' =>0
            ];
            $accessory_dept[]=[
                'location_c' => '1510',// mã công đoạn
                'location_k' => '7',
                'inventory' =>0
            ];
            $accessory_dept[]=[
                'location_c' => '9997',// mã công đoạn
                'location_k' => '7',
                'inventory' =>0
            ];
            $accessory_dept[]=[
                'location_c' => '1533',// mã công đoạn
                'location_k' => '7',
                'inventory' =>0
            ];
            $accessory_dept[]=[
                'location_c' => '1521',// mã công đoạn
                'location_k' => '7',
                'inventory' =>0
            ];
            $accessory_dept[]=[
                'location_c' => '9998',// mã công đoạn
                'location_k' => '7',
                'inventory' =>0
            ];
            $accessory_dept[]=[
                'location_c' => '9995',// mã công đoạn
                'location_k' => '7',
                'inventory' =>0
            ];
            $accessory_dept[]=[
                'location_c' => '1610',// mã công đoạn
                'location_k' => '7',
                'inventory' =>0
            ];
            Accessory::create([
                'code'=> $request->code,
                'type'=> $request->type??null,
                'location_k'=>  7,
                'location_c'=>  '0111',
                'location'=>  $request->location??'D',
                'material_norms'=>  $request->material_norms,
                'unit'=>  $request->unit??'',
                'accessory_dept'=> json_encode($accessory_dept),
                'status'=>1,
                'note_type'=>$request->note_type
            ]);
            session()->flash('success', 'Thêm thành công !!');
            return redirect()->route('admin.accessorys.index');
        } catch (\Exception $e) {
            session()->flash('sticky_error', $e->getMessage());
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function show(Accessory $accessory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('accessory.edit')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }

        $accessory = Accessory::find($id);
        return view('backend.pages.accessorys.edit', compact('accessory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('accessory.edit')) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }

        $accessory = Accessory::find($id);
        if (is_null($accessory)) {
            session()->flash('error', "The page is not found !");
            return redirect()->route('admin.blogs.index');
        }
        try {
            $accessory->code=$request->code;
            $accessory->type=$request->type??null;
            $accessory->note_type=$request->note_type;
            $accessory->location=$request->location;
            $accessory->material_norms=$request->material_norms;
            $accessory->unit=$request->unit;
            if (!is_null($request->image)) {
                $accessory->image = UploadHelper::upload('image', $request->image, $accessory->code . '-' . time(), 'public/assets/images/accessory');
            }
            $accessory->save();
            Cache::store('redis')->forget('findAccessoryByCode_'.$accessory->code);
            session()->flash('success', 'Sửa thành công !!');
            return redirect()->route('admin.accessorys.index');
        } catch (\Exception $e) {
            session()->flash('sticky_error', $e->getMessage());
            return back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function destroyTrash(Request $request, $id)
    {
        $accessory = Accessory::find($id);
        if (is_null($accessory)) {
            session()->flash('error', "Nội dung đã được xóa hoặc không tồn tại !");
            return redirect()->route('admin.accessorys.index');
        }
        $accessory->delete();
        session()->flash('success', 'Đã xóa bản ghi thành công !!');
        return redirect()->route('admin.accessorys.index');
    }
}
