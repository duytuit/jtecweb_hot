<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Imports\QuestionImport;
use Maatwebsite\Excel\Facades\Excel;


class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::all();
        return view('question.index', compact('questions'));
    }
    public function importExcelData(Request $request)
    {
        $request->validate([
            'import_file'=>[
                'required',
                'file',
            ],
        ]);

        Excel::import(new QuestionImport, $request->file('import_file'));
        return redirect()->back()->with('status','Import thành công');

    }
}
