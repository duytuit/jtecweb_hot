<?php

namespace App\Http\Controllers\Backend;

use App\Models\CheckTension;
use App\Exports\CheckTensionExport;
// use App\Imports\CheckTensionImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Helpers\ArrayHelper;
use App\Models\Employee;



class CheckTensionController extends Controller
{
    public $user;

    public function __construct()
    {
        // dd(1);
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        // if (is_null($this->user) || !$this->user->can('backend.pages.checkTension.index')) {
        //     $message = 'Bạn cần đăng nhập trước khi làm việc';
        //     return view('errors.403', compact('message'));
        // }
        $checkTension = CheckTension::all();
        return view('backend.pages.checkTension.index', compact('checkTension'));
    }
    public function view(Request $request)
    {
        $checkTension = CheckTension::all();
        return view('backend.pages.checkTension.view', compact('checkTension'));
    }

    // public function view(Request $request)
    // {
    //     $checkTension['per_page'] = $request->input('per_page', Cookie::get('per_page'));
    //     $checkTension['keyword'] = $request->input('keyword', null);
    //     $checkTension['advance'] = 0;
    //     if (count($request->except('keyword')) > 0) {
    //         // Tìm kiếm nâng cao
    //         $checkTension['advance'] = 1;
    //         $checkTension['filter'] = $request->all();
    //     }
    //     $current_cycleName = Carbon::now()->format('mY');
    //     $checkTension['cycleName'] = $current_cycleName;
    //     $checkTension['cycleNames'] = ArrayHelper::cycleName();
    //     $checkTension['emp'] = Employee::select('code')->where('status', 1)->pluck('code');
    //     $checkTension['lists'] = CheckTension::where(function ($query) use ($request) {
    //         if (isset($request->keyword) && $request->keyword != null) {
    //             $query->filter($request);
    //         }
    //         if (isset($request->cycle_name) && $request->cycle_name != null) {
    //             $query->where('cycle_name', $request->cycle_name);
    //         }
    //         if (isset($request->status) && $request->status != null) {
    //             $query->where('status', $request->status);
    //         }
    //         if (isset($request->from_date) && isset($request->to_date)) {
    //             $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
    //             $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
    //             $query->whereDate('created_at', '>=', $from_date);
    //             $query->whereDate('created_at', '<=', $to_date);
    //         }
    //     })->orderBy('code')->orderBy('cycle_name')->orderBy('created_at')->paginate($checkTension['per_page']);
    //     return view('backend.pages.checkTension.view', $checkTension);
    // }

    public function saveData(Request $request)
    {
        $checkTension = new CheckTension();
        $checkTension->target125 = '9';
        $checkTension->target2 = '15';
        $checkTension->target55 = '29';
        $checkTension->weight125 = $request->input('weight125');
        $checkTension->weight2 = $request->input('weight2');
        $checkTension->weight55 = $request->input('weight55');
        $checkTension->selectComputer = $request->input('selectComputer');
        $checkTension->checkresult = $request->input('resultAll');
        $checkTension->save();
        return view('backend.pages.checkTension.complete', compact('checkTension'));
    }
    public function viewData()
    {
        $viewdata = CheckTension::all();
        return view('backend.pages.checkTension.view', compact('viewdata'));
    }

    public function exportExcel(Request $request)
    {
        $data = CheckTension::all();
        return (new CheckTensionExport($data))->download('tension.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
