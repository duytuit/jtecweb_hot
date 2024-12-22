<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| Tệp này là nơi bạn có thể xác định tất cả bảng điều khiển dựa trên đóng cửa của mình
|lệnh.Mỗi lần đóng được liên kết với một thể hiện lệnh cho phép
|Cách tiếp cận đơn giản để tương tác với các phương thức IO của mỗi lệnh.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
