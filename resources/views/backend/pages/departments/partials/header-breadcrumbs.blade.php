<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.departments.index'))
                    Danh sách bộ phận
                @elseif(Route::is('admin.departments.create'))
                    Thêm mới
                @elseif(Route::is('admin.departments.edit'))
                    {{-- Sửa <span class="badge badge-info">{{ $departments->name }}</span> --}}
                @elseif(Route::is('admin.departments.show'))
                    {{-- Xem <span class="badge badge-info">{{ $departments->code }}</span> --}}
                    <a class="btn btn-outline-success btn-sm"
                        href="{{ route('admin.departments.edit', $departments->id) }}"> <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.departments.index'))
                            <li class="breadcrumb-item active" aria-current="page">Danh sách</li>
                        @elseif(Route::is('admin.departments.create'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.departments.index') }}">Danh sách</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
                        @elseif(Route::is('admin.departments.edit'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.departments.index') }}">Danh sách</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Sửa</li>
                        @elseif(Route::is('admin.departments.show'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.departments.index') }}">Danh sách</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
