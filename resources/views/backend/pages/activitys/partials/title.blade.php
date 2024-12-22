@if (Route::is('admin.activitys.index'))
Activity
@elseif(Route::is('admin.activitys.create'))
Create New Activity
@elseif(Route::is('admin.activitys.edit'))
Edit Activity {{ $activity->title }}
@elseif(Route::is('admin.activitys.show'))
View Activity {{ $activity->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
