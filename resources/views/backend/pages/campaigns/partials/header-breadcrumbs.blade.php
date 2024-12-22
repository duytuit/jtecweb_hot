<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.campaigns.index'))
                    campaigns List
                @elseif(Route::is('admin.campaigns.create'))
                    Create New campaigns
                @elseif(Route::is('admin.campaigns.edit'))
                    Edit campaigns <span class="badge badge-info">{{ $campaigns->title }}</span>
                @elseif(Route::is('admin.campaigns.show'))
                    View campaigns <span class="badge badge-info">{{ $campaigns->title }}</span>
                    <a  class="btn btn-outline-success btn-sm" href="{{ route('admin.campaigns.edit', $campaigns->id) }}"> <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.campaigns.index'))
                            <li class="breadcrumb-item active" aria-current="page">campaigns List</li>
                        @elseif(Route::is('admin.campaigns.create'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.index') }}">campaigns List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create New campaigns</li>
                        @elseif(Route::is('admin.campaigns.edit'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.index') }}">campaigns List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit campaigns</li>
                        @elseif(Route::is('admin.campaigns.show'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.index') }}">campaigns List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Show campaigns</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
