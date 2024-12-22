<?php

namespace App\Imports;

use App\Models\Question;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class QuestionImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {

            if (empty($row['name'])) {
                continue;
            }

            $question = Question::where('name',$row['name'])->first();
            if($question){
                $question->update([
                    'myid'=>$row['myid'],
                    'image' => $row['image'],
                    'answer' => $row['answer'],
                    'answer_list' => $row['answer_list'],
                ]);
            }else{
                Question::create([
                    'myid'=>$row['myid'],
                    'name' => $row['name'],
                    'image' => $row['image'],
                    'answer' => $row['answer'],
                    'answer_list' => $row['answer_list'],
                ]);
            }
        }
    }
}
