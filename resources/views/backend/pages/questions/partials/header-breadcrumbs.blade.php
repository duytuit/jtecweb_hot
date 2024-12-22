<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.questions.index'))
                    Danh sách
                @elseif(Route::is('admin.questions.create'))
                    Thêm mới
                @elseif(Route::is('admin.questions.edit'))
                    Sửa bài thi online <span class="badge badge-info">{{ $question->title }}</span>
                @elseif(Route::is('admin.questions.show'))
                    Chi tiết bài thi online <span class="badge badge-info">{{ $question->title }}</span>
                    <a class="btn btn-outline-success btn-sm" href="{{ route('admin.questions.edit', $question->id) }}">
                        <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.questions.index'))
                            <li class="breadcrumb-item active" aria-current="page">Danh sách</li>
                        @elseif(Route::is('admin.questions.create'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
                        @elseif(Route::is('admin.questions.edit'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sửa bài thi online</li>
                        @elseif(Route::is('admin.questions.show'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chi tiết bài thi online</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
