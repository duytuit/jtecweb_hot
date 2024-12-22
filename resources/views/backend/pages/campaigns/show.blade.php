@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.campaigns.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.campaigns.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.layouts.partials.messages')
        <div class="create-page">
                <div class="form-body">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="title">campaign Title</label>
                                    <br>
                                    {{ $campaign->title }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="slug">Short URL</label>
                                    <br>
                                    {{ $campaign->slug }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="image">campaign Featured Image</label>
                                    <br>
                                    @if ($campaign->image != null)
                                    <img src="{{ asset('public/assets/images/campaigns/'.$campaign->image) }}" alt="Image" class="img width-100" />
                                    @else
                                    <span class="border p-2">
                                        No Image
                                    </span>
                                    @endif

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group has-success">
                                    <label class="control-label" for="status">Status</label>
                                    <br>
                                    {{ $campaign->status === 1 ? 'Active' : 'Inactive' }}
                                </div>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="description">campaign Description</label>
                                    <div>
                                        {!! $campaign->description !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="meta_description">campaign Meta Description</label>
                                    <div>
                                        {!! $campaign->meta_description !!}
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="card-body">
                                        <a  class="btn btn-success" href="{{ route('admin.campaigns.edit', $campaign->id) }}"> <i class="fa fa-edit"></i> Edit Now</a>
                                        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-dark ml-2">Hủy</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
    $(".categories_select").select2({
        placeholder: "Select a Category"
    });
    </script>
@endsection
