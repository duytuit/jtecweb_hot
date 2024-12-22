@if (Route::is('admin.assembles.index'))
Tài sản
@elseif(Route::is('admin.assembles.create'))
Thêm mới tài sản
@elseif(Route::is('admin.assembles.edit'))
Sửa tài sản {{ $asset->title }}
@elseif(Route::is('admin.assembles.show'))
Chi tiết tài sản {{ $asset->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
