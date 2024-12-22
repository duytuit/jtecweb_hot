@if (Route::is('admin.exams.index'))
Exams
@elseif(Route::is('admin.exams.create'))
Create New Exam
@elseif(Route::is('admin.exams.edit'))
Edit Exam {{ $Exam->title }}
@elseif(Route::is('admin.exams.show'))
View Exam {{ $Exam->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
