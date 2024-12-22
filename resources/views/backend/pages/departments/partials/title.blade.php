@if (Route::is('admin.departments.index'))
    Danh sách
@elseif(Route::is('admin.departments.create'))
    Thêm mới
@elseif(Route::is('admin.departments.edit'))
    Sửa
    {{-- {{ $department->title }} --}}
@elseif(Route::is('admin.departments.show'))
    Xem
    {{-- {{ $department->title }} --}}
@endif
| Admin Panel -
{{ config('app.name') }}
