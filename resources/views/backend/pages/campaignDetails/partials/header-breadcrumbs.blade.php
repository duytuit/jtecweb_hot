<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('admin.campaignDetails.index'))
                    campaignDetails List
                @elseif(Route::is('admin.campaignDetails.create'))
                    Create New campaignDetails
                @elseif(Route::is('admin.campaignDetails.edit'))
                    Edit campaignDetails <span class="badge badge-info">{{ $campaignDetails->title }}</span>
                @elseif(Route::is('admin.campaignDetails.show'))
                    View campaignDetails <span class="badge badge-info">{{ $campaignDetails->title }}</span>
                    <a  class="btn btn-outline-success btn-sm" href="{{ route('admin.campaignDetails.edit', $campaignDetails->id) }}"> <i class="fa fa-edit"></i></a>
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        @if (Route::is('admin.campaignDetails.index'))
                            <li class="breadcrumb-item active" aria-current="page">campaignDetails List</li>
                        @elseif(Route::is('admin.campaignDetails.create'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.campaignDetails.index') }}">campaignDetails List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create New campaignDetails</li>
                        @elseif(Route::is('admin.campaignDetails.edit'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.campaignDetails.index') }}">campaignDetails List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit campaignDetails</li>
                        @elseif(Route::is('admin.campaignDetails.show'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.campaignDetails.index') }}">campaignDetails List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Show campaignDetails</li>
                        @endif

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
