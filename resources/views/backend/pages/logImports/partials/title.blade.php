@if (Route::is('admin.logImports.index'))
logImports
@elseif(Route::is('admin.logImports.create'))
Create New logImport
@elseif(Route::is('admin.logImports.edit'))
Edit logImport {{ $logImport->title }}
@elseif(Route::is('admin.logImports.show'))
View logImport {{ $logImport->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
