@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.assemble.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.assemble.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.pages.assemble.partials.top-show')
        @include('backend.layouts.partials.messages')
        <form id="form-search" action="{{ route('admin.assembles.index',) }}" method="get">
            <div class="row form-group">
                <div class="col-sm-8">
                    <span class="btn-group">
                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Tác vụ <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a class="btn-action" data-target="#form_lists" data-method="delete" href="javascript:;"><i class="fa fa-trash" style="color: #cb3030;"></i> Xóa</a></li>
                        </ul>
                    </span>
                    <a href="{{ route('admin.assembles.create') }}" class="btn btn-info"><i class="fa fa-edit"></i> Thêm mới</a>
                    {{-- <a href="{{ route('admin.assembles.exportExcel',Request::all()) }}" class="btn btn-success"><i class="fa fa-edit"></i> Xuất Excel</a> --}}
                </div>
            </div>
        </form><!-- END #form-search -->
        <form id="form-search-advance" action="{{ route('admin.assembles.index') }}" method="get" class="hidden">
            <div id="search-advance" class="search-advance">
                <div class="row form-group space-5">
                    <div class="col-sm-2">
                        <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Nhập từ khóa" class="form-control" />
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"> <i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control date_picker" name="search_date" id="search_date"
                            value="{{ @$filter['search_date'] }}" placeholder="Ngày kiểm tra" autocomplete="off">
                        </div>
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
        <form id="form_lists" action="{{ route('admin.assembles.action') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="" />
            <input type="hidden" name="status" value="" />
            <div class="table-responsive product-table">
                <table class="table table-bordered" id="exams_table">
                    <thead>
                        <tr>
                            <th>TT</th>
                            <th>Mã sản phẩm</th>
                            <th>Lot No</th>
                            <th>Mac số</th>
                            <th>Giờ hệ thống</th>
                            <th>Thời gian bắt đầu</th>
                            <th>Dự kiến kết thúc</th>
                            <th>Thời gian HT thực tế</th>
                            <th>Vị trí máy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $index=> $item)
                            @php
                               $content_form = json_decode($item->content_form);
                            @endphp
                            @if (@$content_form)
                            <tr>
                                <td>{{$index+1 }}</td>
                                <td>{{$content_form->code }}</td>
                                <td>{{$content_form->lot_no }}</td>
                                <td>{{$content_form->index }}</td>
                                <td>{{$content_form->working_hour }}</td>
                                <td>{{$content_form->start_time }}</td>
                                <td>{{$content_form->end_time }}</td>
                                <td>{{$content_form->complete_time }}</td>
                                <td>{{$content_form->pc_name }}</td>
                            </tr>
                            @endif
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
         $('input.date_picker').datepicker({
            autoclose: true,
            dateFormat: "dd-mm-yy"
        }).val();
    </script>
@endsection
