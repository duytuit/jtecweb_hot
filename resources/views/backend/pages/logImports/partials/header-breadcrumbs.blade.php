<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.logImports.index'))
                    logImports List
                @elseif(Route::is('admin.logImports.create'))
                    Create New logImports
                @elseif(Route::is('admin.logImports.edit'))
                    Edit logImports <span class="badge badge-info">{{ $logImports->title }}</span>
                @elseif(Route::is('admin.logImports.show'))
                    View logImports <span class="badge badge-info">{{ $logImports->title }}</span>
                    <a  class="btn btn-outline-success btn-sm" href="{{ route('admin.logImports.edit', $logImports->id) }}"> <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.logImports.index'))
                            <li class="breadcrumb-item active" aria-current="page">logImports List</li>
                        @elseif(Route::is('admin.logImports.create'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.logImports.index') }}">logImports List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create New logImports</li>
                        @elseif(Route::is('admin.logImports.edit'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.logImports.index') }}">logImports List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit logImports</li>
                        @elseif(Route::is('admin.logImports.show'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.logImports.index') }}">logImports List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Show logImports</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
