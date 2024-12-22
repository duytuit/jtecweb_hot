@if (Route::is('admin.questions.index'))
bài thi online
@elseif(Route::is('admin.questions.create'))
Thêm mới bài thi online
@elseif(Route::is('admin.questions.edit'))
Sửa bài thi online {{ $question->title }}
@elseif(Route::is('admin.questions.show'))
Chi tiết bài thi online {{ $question->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
