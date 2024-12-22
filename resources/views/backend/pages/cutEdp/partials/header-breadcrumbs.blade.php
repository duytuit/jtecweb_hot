<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.cutedps.index'))
                    Danh sách
                @elseif(Route::is('admin.cutedps.create'))
                    Tạo yêu cầu cắt EDP
                @elseif(Route::is('admin.cutedps.edit'))
                    Sửa yêu cầu cắt EDP <span class="badge badge-info">{{ $cutedps->title }}</span>
                @elseif(Route::is('admin.cutedps.show'))
                    View cutedps <span class="badge badge-info">{{ $cutedps->title }}</span>
                    <a  class="btn btn-outline-success btn-sm" href="{{ route('admin.cutedps.edit', $cutedps->id) }}"> <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.cutedps.index'))
                            <li class="breadcrumb-item active" aria-current="page">Danh sách</li>
                        @elseif(Route::is('admin.cutedps.create'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.cutedps.index') }}">Danh sách</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tạo yêu cầu cắt EDP</li>
                        @elseif(Route::is('admin.cutedps.edit'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.cutedps.index') }}">Danh sách</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sửa yêu cầu cắt EDP</li>
                        @elseif(Route::is('admin.cutedps.show'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.cutedps.index') }}">Danh sách</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Show cutedps</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
