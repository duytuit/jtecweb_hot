@if (Route::is('admin.assets.index'))
dữ liệu
@elseif(Route::is('admin.assets.create'))
Thêm mới dữ liệu
@elseif(Route::is('admin.assets.edit'))
Sửa dữ liệu {{ $asset->title }}
@elseif(Route::is('admin.assets.show'))
Chi tiết dữ liệu {{ $asset->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
