<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.employees.index'))
                    Danh sách nhân viên
                @elseif(Route::is('admin.employees.create'))
                    Thêm nhân viên mới
                @elseif(Route::is('admin.employees.edit'))
                    Edit employees
                    {{-- <span class="badge badge-info">{{ $employees->title }}</span> --}}
                @elseif(Route::is('admin.employees.show'))
                    View employees
                    {{-- <span class="badge badge-info">{{ $employees->title }}</span> --}}
                    <a class="btn btn-outline-success btn-sm" href="{{ route('admin.employees.edit', $employees->id) }}">
                        <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.employees.index'))
                            <li class="breadcrumb-item active" aria-current="page">Danh sách nhân viên</li>
                        @elseif(Route::is('admin.employees.create'))
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.employees.index') }}">Danh sách nhân viên</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Thêm nhân viên mới</li>
                        @elseif(Route::is('admin.employees.edit'))
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.employees.index') }}">Danh sách nhân viên</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Sửa nhân viên</li>
                        @elseif(Route::is('admin.employees.show'))
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.employees.index') }}">Danh sách nhân viên</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Show employees</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
