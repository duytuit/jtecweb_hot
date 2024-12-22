<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $exam = DB::table('exams')->select('id','code','name')->orderBy('id')->paginate(50);
        return $this->success($exam,200);
    }

}
