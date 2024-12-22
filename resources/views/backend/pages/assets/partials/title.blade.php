@if (Route::is('admin.assets.index'))
Tài sản
@elseif(Route::is('admin.assets.create'))
Thêm mới tài sản
@elseif(Route::is('admin.assets.edit'))
Sửa tài sản {{ $asset->title }}
@elseif(Route::is('admin.assets.show'))
Chi tiết tài sản {{ $asset->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
