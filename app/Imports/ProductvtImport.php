<?php

namespace App\Imports;
use App\Models\Productvt;
use Illuminate\Support\Collection;


class ProductvtImport
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {

            $productvt = Productvt::where('ngaylamviec',$row['ngaylamviec'])->first();
            if($productvt){
                $productvt->update([
                    'muctieu' => $row['muctieu'],
                    'maylamviec' => $row['maylamviec'],
                    'macodenv' => $row['macodenv'],
                    'calamviec' => $row['calamviec'],
                    'sltrenmay' => $row['sltrenmay'],
                    'slnhanvien' => $row['slnhanvien'],
                    'phantram' => $row['phantram'],
                    'ghichu' => $row['ghichu']
                ]);
            }else{
                Productvt::create([
                    'ngaylamviec' => $row['ngaylamviec'],
                    'muctieu' => $row['muctieu'],
                    'maylamviec' => $row['maylamviec'],
                    'macodenv' => $row['macodenv'],
                    'calamviec' => $row['calamviec'],
                    'sltrenmay' => $row['sltrenmay'],
                    'slnhanvien' => $row['slnhanvien'],
                    'phantram' => $row['phantram'],
                    'ghichu' => $row['ghichu']
                ]);
            }
        }
    }
}
