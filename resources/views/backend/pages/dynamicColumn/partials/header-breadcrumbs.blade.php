<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.dynamicColumns.index'))
                    Danh sách
                @elseif(Route::is('admin.dynamicColumns.create'))
                    Thêm mới
                @elseif(Route::is('admin.dynamicColumns.edit'))
                    Sửa bài thi online <span class="badge badge-info">{{ $dynamicColumn->title }}</span>
                @elseif(Route::is('admin.dynamicColumns.show'))
                    Chi tiết bài thi online <span class="badge badge-info">{{ $dynamicColumn->title }}</span>
                    <a class="btn btn-outline-success btn-sm" href="{{ route('admin.dynamicColumns.edit', $dynamicColumn->id) }}">
                        <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.dynamicColumns.index'))
                            <li class="breadcrumb-item active" aria-current="page">Danh sách</li>
                        @elseif(Route::is('admin.dynamicColumns.create'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.dynamicColumns.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
                        @elseif(Route::is('admin.dynamicColumns.edit'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.dynamicColumns.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sửa bài thi online</li>
                        @elseif(Route::is('admin.dynamicColumns.show'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.dynamicColumns.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chi tiết bài thi online</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
