<?php
namespace App\Http\Controllers\Backend;

use App\Helpers\ArrayHelper;
use App\Helpers\UploadHelper;
use App\Models\Required;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AssembleController extends Controller
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
        $data['keyword'] = $request->input('keyword', null);
        $data['per_page'] = $request->input('per_page', Cookie::get('per_page'));
        $data['advance'] = 0;
        $data['positionByDevices'] = ArrayHelper::PositionByDevices();
        $curentDate = $request->search_date ? $request->search_date : Carbon::now()->format('d-m-Y');
        $data['filter'] = $request->all();
        $data['filter']['search_date'] = $curentDate;
        $data['lists'] = Required::where('type',0)->where('from_type',ArrayHelper::from_type_rquired_assemble)->whereDate('created_at',Carbon::parse($curentDate)->format('Y-m-d'))->orderBy('created_at', 'desc')->where(function ($query) use ($request) {
            if (isset($request->keyword) && $request->keyword != null) {
                $query->whereHas('employee',function($query) use($request){
                    $query->where('code',$request->keyword);
                });
            }
        })->paginate($data['per_page']);
       return view('backend.pages.assemble.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('assemble.create')) {
            return abort(403, 'Bạn không có quyền truy cập trang này !');
        }
        return view('backend.pages.assemble.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.assembles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Required  $Required
     * @return \Illuminate\Http\Response
     */
    public function show(Required $Required)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Required  $Required
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('Required.edit')) {
            $message = 'Bạn không có quyền truy cập trang này !';
            return view('errors.403', compact('message'));
        }
        $Required = Required::find($id);
        if($Required){
            $manager = Employee::find($Required->manager_by);
        }

        return view('backend.pages.assemble.edit', compact('Required','manager'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Required  $Required
     * @return \Illuminate\Http\Response
     */
    public function destroy(Required $Required)
    {
        //
    }
}
