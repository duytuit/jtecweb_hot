<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToCollection, WithHeadingRow
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

            $employee = Employee::where('first_name', $row['first_name'])->first();
            if ($employee) {
                $employee->update([
                    'code' => $row['code'],
                    'last_name' => $row['last_name'],
                    'status' => $row['status'],
                ]);
            } else {
                Employee::create([
                    'code' => $row['code'],
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'status' => $row['status'],
                ]);
            }
        }
    }
}
