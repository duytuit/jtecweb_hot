<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.requireds.index'))
                 {{ $lists->count() }} Yêu cầu trong ngày
                @elseif(Route::is('admin.requireds.create'))
                    Thêm mới yêu cầu linh kiện
                @elseif(Route::is('admin.requireds.edit'))
                    Sửa yêu cầu <span class="badge badge-info">{{ $requireds->title }}</span>
                @elseif(Route::is('admin.requireds.show'))
                    Xem yêu cầu<span class="badge badge-info">{{ $requireds->title }}</span>
                    <a class="btn btn-outline-success btn-sm" href="{{ route('admin.requireds.edit', $requireds->id) }}">
                        <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.requireds.index'))
                            <li class="breadcrumb-item active" aria-current="page">Danh sách</li>
                        @elseif(Route::is('admin.requireds.create'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.requireds.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thêm yêu cầu linh kiện</li>
                        @elseif(Route::is('admin.requireds.edit'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.requireds.index') }}">Danh sách</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Sửa yêu cầu</li>
                        @elseif(Route::is('admin.requireds.show'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.requireds.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chi tiết yêu cầu</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
