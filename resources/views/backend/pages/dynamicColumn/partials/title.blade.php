@if (Route::is('admin.dynamicColumns.index'))
bài thi online
@elseif(Route::is('admin.dynamicColumns.create'))
Thêm mới bài thi online
@elseif(Route::is('admin.dynamicColumns.edit'))
Sửa bài thi online {{ $dynamicColumn->title }}
@elseif(Route::is('admin.dynamicColumns.show'))
Chi tiết bài thi online {{ $dynamicColumn->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
