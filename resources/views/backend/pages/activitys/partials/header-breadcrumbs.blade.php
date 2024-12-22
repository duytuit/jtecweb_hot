<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.activitys.index'))
                    Activitys List
                @elseif(Route::is('admin.activitys.create'))
                    Create New Activitys
                @elseif(Route::is('admin.activitys.edit'))
                    Edit Activitys <span class="badge badge-info">{{ $activitys->title }}</span>
                @elseif(Route::is('admin.activitys.show'))
                    View Activitys <span class="badge badge-info">{{ $activitys->title }}</span>
                    <a  class="btn btn-outline-success btn-sm" href="{{ route('admin.activitys.edit', $activitys->id) }}"> <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.activitys.index'))
                            <li class="breadcrumb-item active" aria-current="page">Activitys List</li>
                        @elseif(Route::is('admin.activitys.create'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.activitys.index') }}">Activitys List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create New Activitys</li>
                        @elseif(Route::is('admin.activitys.edit'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.activitys.index') }}">Activitys List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Activitys</li>
                        @elseif(Route::is('admin.activitys.show'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.activitys.index') }}">Activitys List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Show Activitys</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
