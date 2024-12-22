<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.warehouses.index'))
                {{ $lists->count() }} Yêu cầu trong ngày
                @elseif(Route::is('admin.warehouses.create'))
                    Create New warehouses
                @elseif(Route::is('admin.warehouses.edit'))
                    Edit warehouses <span class="badge badge-info">{{ $warehouses->title }}</span>
                @elseif(Route::is('admin.warehouses.show'))
                    View warehouses <span class="badge badge-info">{{ $warehouses->title }}</span>
                    <a  class="btn btn-outline-success btn-sm" href="{{ route('admin.warehouses.edit', $warehouses->id) }}"> <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.warehouses.index'))
                            <li class="breadcrumb-item active" aria-current="page">Danh sách</li>
                        @elseif(Route::is('admin.warehouses.create'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.warehouses.index') }}">Danh sách</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create New warehouses</li>
                        @elseif(Route::is('admin.warehouses.edit'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.warehouses.index') }}">Danh sách</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit warehouses</li>
                        @elseif(Route::is('admin.warehouses.show'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.warehouses.index') }}">Danh sách</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Show warehouses</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
