@if (Route::is('admin.cutedps.index'))
Danh sách
@elseif(Route::is('admin.cutedps.create'))
Tạo yêu cầu cắt EDP
@elseif(Route::is('admin.cutedps.edit'))
Sửa yêu cầu {{ $required->title }}
@elseif(Route::is('admin.cutedps.show'))
View required {{ $required->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
