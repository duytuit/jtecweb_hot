@if (Route::is('admin.requireds.index'))
requireds
@elseif(Route::is('admin.requireds.create'))
Create New required
@elseif(Route::is('admin.requireds.edit'))
Edit required {{ $required->title }}
@elseif(Route::is('admin.requireds.show'))
View required {{ $required->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
