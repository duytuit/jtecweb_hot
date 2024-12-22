<?php
namespace App\Http\Controllers\Backend;

use App\Helpers\ArrayHelper;
use App\Helpers\ImageUploadHelper;
use App\Helpers\UploadHelper;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AssetController extends Controller
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
       $data['keyword'] = $request->input('keyword', null);
       $data['advance'] = 0;
       if (count($request->except('keyword')) > 0) {
           // Tìm kiếm nâng cao
           $data['advance'] = 1;
           $data['filter'] = $request->all();
       }
       $data['models'] = ArrayHelper::getModels();
       $data['lists'] = Asset::where(function ($query) use ($request) {
           if (isset($request->keyword) && $request->keyword != null) {
               $query->filter($request);
           }
           if (isset($request->status) && $request->status != null) {
               $query->where('status', $request->status);
           }
       })->orderBy('id','desc')->paginate($data['per_page']);
       return view('backend.pages.assets.index', $data);
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
        return view('backend.pages.assets.create');
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
            'name' => 'required|unique:assets,name'
        ]);
        try {
            $image = null;
            if (!is_null($request->image)) {
                $image = UploadHelper::upload('image', $request->image, $request->name . '-' . time(), 'public/assets/images/asset');
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
            return redirect()->route('admin.assets.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
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
        if (is_null($this->user) || !$this->user->can('asset.edit')) {
            $message = 'Bạn không có quyền truy cập trang này !';
            return view('errors.403', compact('message'));
        }
        $asset = Asset::find($id);
        if($asset){
            $manager = Employee::find($asset->manager_by);
        }

        return view('backend.pages.assets.edit', compact('asset','manager'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:assets,name' . ($id ? ",$id" : '')
        ]);

        $asset = Asset::find($id);

        if (empty($asset)) {
            session()->flash('error', "Không tìm thấy dữ liệu.");
            return redirect()->route('admin.assets.index');
        }
        // Update assets
        try {
            $image = null;
            if (!is_null($request->image)) {
                $image = UploadHelper::upload('image', $request->image, $request->name . '-' . time(), 'public/assets/images/asset');
            }
            $asset->name = $request->name;
            $asset->code = $request->code;
            $asset->model = $request->model;
            $asset->manager_by = $request->manager_by;
            $asset->color = $request->color;
            $image ? $asset->image = $image : '';
            $asset->note = $request->note;
            asset($request->status) ? $asset->status = @$request->status : '';
            $asset->created_by = Auth::user()->id;
            $asset->updated_by = Auth::user()->id;
            $asset->save();
            session()->flash('success', "Thay đổi dữ liệu thành công.");
            return redirect()->route('admin.assets.index');
        } catch (\Exception $e) {
            session()->flash('error', "Failed to update department: " . $e->getMessage());
            return redirect()->back()->withInput();
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
}
