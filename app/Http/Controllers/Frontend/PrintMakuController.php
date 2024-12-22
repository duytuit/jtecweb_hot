<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class PrintMakuController extends Controller
{
    public function index()
    {
        return view('frontend.pages.printMaku.index');
    }
}
