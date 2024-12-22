@if (Route::is('admin.productvt.index'))
productvt
{{-- @elseif(Route::is('admin.productvt.create'))
Create New Exam --}}
@elseif(Route::is('admin.productvt.edit'))
Edit Exam {{ $Exam->title }}
{{-- @elseif(Route::is('admin.productvt.show'))
View Exam {{ $Exam->title }} --}}
@endif
| Admin Panel -
{{ config('app.name') }}
