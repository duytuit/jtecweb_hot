<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAccessory extends Model
{
    use HasFactory;
    protected $guarded = [];
    // protected $fillable = [
    //     '年月度',
    //     '場所C',
    //     'X31',
    //     '品目K',
    //     '品目C',
    //     'FILLER10',
    //     '当月入庫数',số lượng sản phẩm nhận được trong tháng này(nhập)
    //     '当月出庫数',số lượng mặt hàng phát hành trong tháng(xuất)
    //     '当月在庫数',số lượng tồn kho tháng hiện tại(*)(số lượng)
    //     '当月在庫単価', đơn giá tồn kho
    //     '当月在庫金額',thành tiền(*)
    //     'FILLER20',
    //     '当月出庫数_2'số lượng mặt hàng được phát hành trong tháng này
    // ];
}
