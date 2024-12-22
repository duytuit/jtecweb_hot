<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;


class QrCodeExport implements FromView
{
    use Exportable;

    public $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }
    public function view(): View
    {
        return view('qrcode.export', [ 'collection' =>$this->collection]);
    }

}
