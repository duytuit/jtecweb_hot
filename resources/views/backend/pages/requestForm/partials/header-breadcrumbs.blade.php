<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.assets.index'))
                    Danh sách
                @elseif(Route::is('admin.assets.create'))
                    Thêm mới
                @elseif(Route::is('admin.assets.edit'))
                    Sửa yêu cầu <span class="badge badge-info">{{ $asset->title }}</span>
                @elseif(Route::is('admin.assets.show'))
                    Chi tiết yêu cầu <span class="badge badge-info">{{ $asset->title }}</span>
                    <a class="btn btn-outline-success btn-sm" href="{{ route('admin.assets.edit', $asset->id) }}">
                        <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.assets.index'))
                            <li class="breadcrumb-item active" aria-current="page">Danh sách</li>
                        @elseif(Route::is('admin.assets.create'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.assets.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
                        @elseif(Route::is('admin.assets.edit'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.assets.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sửa yêu cầu</li>
                        @elseif(Route::is('admin.assets.show'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.assets.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chi tiết yêu cầu</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
