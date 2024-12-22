<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductionPlanImport implements ToCollection, WithMultipleSheets
{
     /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            1 => $this,
        ];
    }
    public function collection(Collection $rows)
    {
        return $rows;
    }
}
