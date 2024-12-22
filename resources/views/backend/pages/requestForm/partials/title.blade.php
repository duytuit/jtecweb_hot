@if (Route::is('admin.assets.index'))
yêu cầu
@elseif(Route::is('admin.assets.create'))
Thêm mới yêu cầu
@elseif(Route::is('admin.assets.edit'))
Sửa yêu cầu {{ $asset->title }}
@elseif(Route::is('admin.assets.show'))
Chi tiết yêu cầu {{ $asset->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
