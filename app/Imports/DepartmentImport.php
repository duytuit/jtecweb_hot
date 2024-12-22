<?php

namespace App\Imports;

use App\Models\Department;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class DepartmentImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty($row['code'])) {
                continue;
            }

            $department = Department::where('name', $row['name'])->first();
            if ($department) {
                $department->update([
                    'code' => $row['code'],
                    'status' => $row['status'],
                ]);
            } else {
                Department::create([
                    'code' => $row['code'],
                    'name' => $row['name'],
                    'status' => $row['status'],
                ]);
            }
        }
    }
}
