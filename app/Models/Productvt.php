<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productvt extends Model
{
    use HasFactory;
    protected $table = 'sanluong';

    protected $fillable = [
        'ngaylamviec',
        'muctieu',
        'maylamviec',
        'macodenv',
        'calamviec',
        'sltrenmay',
        'slnhanvien',
        'phantram',
        'ghichu'
    ];
}
