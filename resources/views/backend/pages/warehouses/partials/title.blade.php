@if (Route::is('admin.warehouses.index'))
Danh sách
@elseif(Route::is('admin.warehouses.create'))
Create New required
@elseif(Route::is('admin.warehouses.edit'))
Edit required {{ $required->title }}
@elseif(Route::is('admin.warehouses.show'))
View required {{ $required->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
