@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.assets.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.assets.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.pages.assets.partials.top-show')
        @include('backend.layouts.partials.messages')
        <form id="form-search" action="{{ route('admin.assets.index',) }}" method="get">
            <div class="row form-group">
                <div class="col-sm-8">
                    <span class="btn-group">
                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Tác vụ <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a class="btn-action" data-target="#form_lists" data-method="delete" href="javascript:;"><i class="fa fa-trash" style="color: #cb3030;"></i> Xóa</a></li>
                        </ul>
                    </span>
                    <a href="{{ route('admin.assets.create') }}" class="btn btn-info"><i class="fa fa-edit"></i> Thêm mới</a>
                    {{-- <a href="{{ route('admin.assets.exportExcel',Request::all()) }}" class="btn btn-success"><i class="fa fa-edit"></i> Xuất Excel</a> --}}
                </div>
            </div>
        </form><!-- END #form-search -->
        <form id="form-search-advance" action="{{ route('admin.assets.index') }}" method="get" class="hidden">
            <div id="search-advance" class="search-advance">
                <div class="row form-group space-5">
                    <div class="col-sm-2">
                        <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Nhập từ khóa" class="form-control" />
                    </div>
                    <div class="col-sm-2">
                        <select name="status" class="form-control" style="width: 100%;">
                            <option value="">Trạng thái</option>
                            <option value="1" {{ @$filter['status'] === '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ @$filter['status'] === '0' ? 'selected' : '' }}>Chưa hoạt động</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-warning btn-block">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </form><!-- END #form-search-advance -->
        <form id="form_lists" action="{{ route('admin.assets.action') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="" />
            <input type="hidden" name="status" value="" />
            <div class="table-responsive product-table">
                <table class="table table-bordered" id="exams_table">
                    <thead>
                        <tr>
                            <th>TT</th>
                            <th>Mã yêu cầu</th>
                            <th>Tên yêu cầu</th>
                            <th>Hình ảnh</th>
                            <th>Ghi chú</th>
                            <th>Người quản lý</th>
                            <th>Trang thái</th>
                            <th>Chỉnh sửa lần cuối</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $index=> $item)
                            @php
                               $user= $item->user;
                               $manager= $item->manager;
                            @endphp
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>{{$item->code }}</td>
                                <td>{{$item->name }}</td>
                                <td>
                                    {!! $item->image != null ? '<img src="' . asset('public/assets/images/asset/' . $item->image) . '"  width=150>':'' !!}
                                </td>
                                <td>{{$item->note }}</td>
                                <td>
                                    {{ @$manager->code.'-'. @$manager->first_name.' '.@$manager->last_name }}
                                </td>
                                <td>
                                    @if ( $item->status)
                                        <span class="badge badge-success font-weight-100">Hoạt động</span>
                                    @else
                                        <span class="badge badge-warning">Ngừng hoạt động</span>
                                    @endif
                                </td>
                                <td>
                                    <div> {{ @$user->last_name.' '.@$user->last_name }}</div>
                                    <div> {{ $item->updated_at }}</div>
                                </td>
                                <td>
                                    <a title="Lịch sử thao tác" target="_blank" class="d-inline-block btn-info btn-sm text-white"
                                    href="{{ route('admin.activitys.index', ['modelId' => $item->id,'content_type' =>get_class($item)]) }}"><i
                                        class="fa fa-history"></i> </a>
                                    <a href="{{ route('admin.assets.edit', ['id' => $item->id]) }}" class="btn btn-sm btn-primary"><span class="fa fa-edit"></span></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <span class="record-total">Tổng: {{ $lists->total() }} bản ghi</span>
                </div>
                <div class="col-sm-6 text-center">
                    <div class="pagination-panel">
                        {{ $lists->appends(Request::all())->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
                <div class="col-sm-3 text-right">
                    <span>
                        Hiển thị
                        <select name="per_page" class="form-control" style="display: inline;width: auto;" data-target="#form_lists">
                            @php $list = [5, 10, 20, 50, 100, 200]; @endphp
                            @foreach ($list as $num)
                                <option value="{{ $num }}" {{ $num == $per_page ? 'selected' : '' }}>{{ $num }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
    </script>
@endsection
