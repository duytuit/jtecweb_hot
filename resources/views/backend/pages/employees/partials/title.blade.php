@if (Route::is('admin.employees.index'))
employees
@elseif(Route::is('admin.employees.create'))
Create New employee
@elseif(Route::is('admin.employees.edit'))
Edit employee {{ $employee->title }}
@elseif(Route::is('admin.employees.show'))
View employee {{ $employee->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
