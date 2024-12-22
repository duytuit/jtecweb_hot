@if (Route::is('admin.requireds.index'))
Yêu cầu
@elseif(Route::is('admin.requireds.create'))
Tạo yêu cầu
@elseif(Route::is('admin.requireds.edit'))
Sửa yêu cầu {{ $required->title }}
@elseif(Route::is('admin.requireds.show'))
Xem yêu cầu {{ $required->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
