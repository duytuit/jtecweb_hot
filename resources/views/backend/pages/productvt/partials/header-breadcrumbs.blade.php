<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.productvt.index'))
                    Danh sách thi
                {{-- @elseif(Route::is('admin.productvt.create'))
                    Create New Exam --}}
                @elseif(Route::is('admin.productvt.edit'))
                    Edit Exam <span class="badge badge-info">{{ $exam->title }}</span>
                {{-- @elseif(Route::is('admin.productvt.show'))
                    View Exam <span class="badge badge-info">{{ $exam->title }}</span>
                    <a  class="btn btn-outline-success btn-sm" href="{{ route('admin.productvt.edit', $exam->id) }}"> <i class="fa fa-edit"></i></a> --}}
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.productvt.index'))
                            <li class="breadcrumb-item active" aria-current="page">Danh sách thi</li>
                        {{-- @elseif(Route::is('admin.productvt.create'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.productvt.index') }}">Exam List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create New Exam</li> --}}
                        @elseif(Route::is('admin.productvt.edit'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.productvt.index') }}">Exam List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Exam</li>
                        {{-- @elseif(Route::is('admin.productvt.show'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.productvt.index') }}">Exam List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Show Exam</li> --}}
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
