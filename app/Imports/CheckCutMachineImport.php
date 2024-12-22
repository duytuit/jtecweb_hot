<?php

namespace App\Imports;

use App\Models\CheckCutMachine;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class CheckCutMachineImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        // foreach ($rows as $row) {
        //     if (empty($row['code'])) {
        //         continue;
        //     }

        //     $department = CheckCutMachine::where('name', $row['name'])->first();
        //     if ($department) {
        //         $department->update([
        //             'code' => $row['code'],
        //             'status' => $row['status'],
        //         ]);
        //     } else {
        //         CheckCutMachine::create([
        //             'code' => $row['code'],
        //             'name' => $row['name'],
        //             'status' => $row['status'],
        //         ]);
        //     }
        // }
    }
}
