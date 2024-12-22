@if (Route::is('admin.assets.index'))
bài thi online
@elseif(Route::is('admin.assets.create'))
Thêm mới bài thi online
@elseif(Route::is('admin.assets.edit'))
Sửa bài thi online {{ $asset->title }}
@elseif(Route::is('admin.assets.show'))
Chi tiết bài thi online {{ $asset->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
