<?php

namespace App\Exports;

use App\Models\Required;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RequiredExport implements FromView,WithEvents,WithColumnFormatting
{
    use Exportable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {
        return view('backend.pages.requireds.exports.requiredExport', ['lists' => $this->data]);
    }
     /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            // AfterSheet::class    => function(AfterSheet $event) {
            //      $event->sheet->getDelegate()->getStyle('B')->getNumberFormat()->setFormatCode('@');
            // },
        ];
    //     BeforeSheet::class    => function(BeforeSheet $event) {
    //         $event->sheet->getDelegate()->getStyle('B')->getNumberFormat()->setFormatCode('@');
    //    },
        // $event->sheet->getDelegate()->getStyle('B')->getNumberFormat()->setFormatCode(
        //     \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT
        // );
    }
    public function columnFormats(): array
    {
        return [
          //  'B' => PhpOffice\PhpSpreadsheet\Style::NumberFormat
        ];
    }
}
