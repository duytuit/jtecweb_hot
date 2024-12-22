@if (Route::is('admin.checkdevices.index'))
checkdevice
@elseif(Route::is('admin.checkdevices.create'))
Thêm mới checkdevice
@elseif(Route::is('admin.checkdevices.edit'))
Edit checkdevice {{ $checkdevice->title }}
@elseif(Route::is('admin.checkdevices.show'))
View checkdevice {{ $checkdevice->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
