<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.checkdevices.index'))
                    Danh sách
                @elseif(Route::is('admin.checkdevices.create'))
                    Kiểm tra vị trí
                @elseif(Route::is('admin.checkdevices.edit'))
                    Edit checkdevices <span class="badge badge-info">{{ $checkdevices->title }}</span>
                @elseif(Route::is('admin.checkdevices.show'))
                    View checkdevices <span class="badge badge-info">{{ $checkdevices->title }}</span>
                    <a class="btn btn-outline-success btn-sm"
                        href="{{ route('admin.checkdevices.edit', $checkdevices->id) }}"> <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.checkdevices.index'))
                            <li class="breadcrumb-item active" aria-current="page">Danh sách</li>
                        @elseif(Route::is('admin.checkdevices.create'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.checkdevices.index') }}">checkdevices
                                    List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Kiểm tra vị trí</li>
                        @elseif(Route::is('admin.checkdevices.edit'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.checkdevices.index') }}">checkdevices
                                    List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit checkdevices</li>
                        @elseif(Route::is('admin.checkdevices.show'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.checkdevices.index') }}">checkdevices
                                    List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Show checkdevices</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
