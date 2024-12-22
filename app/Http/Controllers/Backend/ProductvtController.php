<?php

namespace App\Http\Controllers\Backend;

use App\Models\Productvt;
use App\Imports\ProductvtImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductvtController extends Controller
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
        // dd(123);
        $productvt = Productvt::all();
    //    dd($productvt);
    //    return route('admin.productvt.index', compact('productvt'));
        return view('backend.pages.productvt.index', compact('productvt'));
    }
    public function UserInput(Request $request)
    {
        $productvt = Productvt::all();
        return view('backend.pages.productvt.user-input', compact('productvt'));

    }

    public function ProductvtData(Request $request)
    {
        $request->validate([
            'ngaylamviec' => 'required',
            'muctieu' => 'required',
            'maylamviec' => 'required',
            'macodenv' => 'required',
            'calamviec' => 'required',
            'sltrenmay' => 'required',
            'slnhanvien' => 'required',
            'phantram' => 'required',
            'ghichu' => 'required'
        ]);

        Productvt::create([
            'ngaylamviec' => $request->ngaylamviec,
            'muctieu' => $request->input('slTarget'),
            'maylamviec' => $request->maylamviec,
            'macodenv' => $request->macodenv,
            'calamviec' => $request->calamviec,
            'sltrenmay' => $request->sltrenmay,
            'slnhanvien' => $request->slnhanvien,
            'phantram' => $request->phantram,
            'ghichu' => $request->ghichu
        ]);

        // return redirect()->back()->with('status','Lưu dữ liệu thành công');
        return redirect()->route('admin.productvt.index');
        // return view('backend.pages.productvt.index', compact('productvt'));
    }
    public function ProductvtEdit($ngaylamviec)
    {
        $productvt = Productvt::find($ngaylamviec);
        return view('backend.pages.productvt.edit', compact('productvt'));
    }
    public function ProductvtUpdate(Request $request, $ngaylamviec)
    {
        $productvt = Productvt::find($ngaylamviec);
        return view('backend.pages.productvt.edit', compact('productvt'));

        $productvt = Productvt::find($ngaylamviec);

        $productvt->ngaylamviec = $request->ngaylamviec;
        $productvt->muctieu = $request->input('slTarget');
        $productvt->maylamviec = $request->maylamviec;
        $productvt->macodenv = $request->macodenv;
        $productvt->calamviec = $request->calamviec;
        $productvt->sltrenmay = $request->sltrenmay;
        $productvt->slnhanvien = $request->slnhanvien;
        $productvt->phantram = $request->phantram;
        $productvt->ghichu = $request->ghichu;

        $productvt->update();
        // return redirect()->back()->with('status','Sửa thành công');
        return view('backend.pages.productvt.index', compact('productvt'));
    }
}
