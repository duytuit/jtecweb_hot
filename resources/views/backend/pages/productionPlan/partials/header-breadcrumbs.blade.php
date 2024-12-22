<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.productionPlans.index'))
                    Danh sách
                @elseif(Route::is('admin.productionPlans.create'))
                    Thêm mới
                @elseif(Route::is('admin.productionPlans.edit'))
                    Sửa cột <span class="badge badge-info">{{ $productionPlan->title }}</span>
                @elseif(Route::is('admin.productionPlans.show'))
                   Kế hoạch sản xuất <span class="badge badge-info">{{ $productionPlan->title }}</span>
                    <a class="btn btn-outline-success btn-sm" href="{{ route('admin.productionPlans.edit', $productionPlan->id) }}">
                        <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.productionPlans.index'))
                            <li class="breadcrumb-item active" aria-current="page">Danh sách</li>
                        @elseif(Route::is('admin.productionPlans.create'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.productionPlans.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
                        @elseif(Route::is('admin.productionPlans.edit'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.productionPlans.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sửa cột</li>
                        @elseif(Route::is('admin.productionPlans.show'))
                            <li class="breadcrumb-item"><a href="{{ route('admin.productionPlans.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
