@if (Route::is('admin.assets.index'))
công cụ
@elseif(Route::is('admin.assets.create'))
Thêm mới công cụ
@elseif(Route::is('admin.assets.edit'))
Sửa công cụ {{ $asset->title }}
@elseif(Route::is('admin.assets.show'))
Chi tiết công cụ {{ $asset->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
