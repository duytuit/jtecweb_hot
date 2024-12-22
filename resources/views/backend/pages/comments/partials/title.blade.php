@if (Route::is('admin.comments.index'))
Comment
@elseif(Route::is('admin.comments.create'))
Create New Comment
@elseif(Route::is('admin.comments.edit'))
Edit Comment {{ $comment->title }}
@elseif(Route::is('admin.comments.show'))
View Comment {{ $comment->title }}
@endif
| Admin Panel -
{{ config('app.name') }}
