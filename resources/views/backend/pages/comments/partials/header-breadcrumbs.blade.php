<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.comments.index'))
                    Comments List
                @elseif(Route::is('admin.comments.create'))
                    Create New Comments
                @elseif(Route::is('admin.comments.edit'))
                    Edit Comments <span class="badge badge-info">{{ $comments->title }}</span>
                @elseif(Route::is('admin.comments.show'))
                    View Comments <span class="badge badge-info">{{ $comments->title }}</span>
                    <a  class="btn btn-outline-success btn-sm" href="{{ route('admin.comments.edit', $comments->id) }}"> <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.comments.index'))
                            <li class="breadcrumb-item active" aria-current="page">Comments List</li>
                        @elseif(Route::is('admin.comments.create'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.comments.index') }}">Comments List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create New Comments</li>
                        @elseif(Route::is('admin.comments.edit'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.comments.index') }}">Comments List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Comments</li>
                        @elseif(Route::is('admin.comments.show'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.comments.index') }}">Comments List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Show Comments</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
