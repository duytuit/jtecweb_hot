@if (Route::is('admin.requestVpps.index'))
Yêu cầu
@elseif(Route::is('admin.requestVpps.create'))
Tạo yêu cầu
@elseif(Route::is('admin.requestVpps.edit'))
Sửa yêu cầu {{ $requestVpp->title }}
@elseif(Route::is('admin.requestVpps.show'))
Xem yêu cầu {{ $requestVpp->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
