@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.requireds.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.requireds.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.pages.requireds.partials.top-show')
        @include('backend.layouts.partials.messages')
        <div class="form-group">
            <form class="cat-style" action="{{ route('admin.accessorys.saveInvoice') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-sm-4">
                        Đồng bộ lúc: {{@$update_asyncInvoice ? json_decode(@$update_asyncInvoice)->time:''}}
                    </div>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="file" name="import_file" class="form-control" />
                            <button type="submit" class="btn btn-primary">Nhập Invoice</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <form id="form-search" action="{{ route('admin.accessorys.index',) }}" method="get">
            <div class="row form-group">
                <div class="col-sm-8">
                    <span class="btn-group">
                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Tác vụ <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a class="btn-action" data-target="#form_lists" data-method="delete" href="javascript:;"><i class="fa fa-trash" style="color: #cb3030;"></i> Xóa</a></li>
                            <li><a class="btn-action" data-target="#form_lists" data-method="active" href="javascript:;"><i class="fa fa-check" style="color: green;"></i> Duyệt</a></li>
                            <li><a class="btn-action" data-target="#form_lists" data-method="inactive" href="javascript:;"><i class="fa fa-times"></i> Bỏ duyệt</a></li>
                        </ul>
                    </span>
                    <a href="{{ route('admin.accessorys.create') }}" class="btn btn-info"><i class="fa fa-edit"></i> Thêm mới</a>
                    {{-- @if (Auth::user()->id == 1)
                         <a href="{{ route('admin.accessorys.syncAccessory') }}" target="_blank" class="btn btn-success"><i class="fa fa-edit"></i>Đồng bộ dữ liệu</a>
                    @endif --}}
                </div>
                <div class="col-sm-4 text-right">
                    <div class="input-group">
                        <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Nhập từ khóa" class="form-control" />
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-info"><span class="fa fa-search"></span></button>
                            <button type="button" class="btn btn-warning btn-search-advance" data-toggle="show" data-target=".search-advance"><span class="fa fa-filter"></span></button>
                        </div>
                    </div>
                </div>
            </div>
        </form><!-- END #form-search -->
        <form id="form_lists" action="{{ route('admin.accessorys.action') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="" />
            <input type="hidden" name="status" value="" />
            <div class="table-responsive product-table">
                <table class="table table-bordered" id="exams_table">
                    <thead>
                        <tr>
                            <th>TT</th>
                            <th>Mã linh kiện</th>
                            {{-- <th>vị trí (location_c)</th> --}}
                            <th>Ảnh</th>
                            <th>Định mức</th>
                            <th>Đơn vị</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $index=> $item)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>{{$item->code }}</td>
                                {{-- <td>{{$item->location_c }}</td> --}}
                                <td>
                                    {!! $item->image != null ? '<img src="' . asset('public/assets/images/accessory/' . $item->image) . '"  width=150>':'' !!}
                                </td>
                                <td>{{$item->material_norms }}</td>
                                <td>{{$item->unit }}</td>
                                <td>
                                    @if ( $item->status)
                                        <span class="badge badge-success font-weight-100">Hoạt động</span>
                                    @else
                                        <span class="badge badge-warning">Ngừng hoạt động</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.accessorys.edit', ['id' => $item->id]) }}" class="btn btn-primary"><span class="fa fa-edit"></span></a>
                                    <a href="{{ route('admin.accessorys.trashed.destroy', ['id' => $item->id]) }}" class="btn btn-danger"><span class="fa fa-trash"></span></a>
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
    const ajaxURL = "<?php echo Route::is('admin.requireds.trashed' ? 'requireds/trashed/view' : 'requireds') ?>";
    $('table#requireds_table').DataTable({
        dom: 'Blfrtip',
        language: {processing: "<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Loading Data..."},
        processing: true,
        serverSide: true,
        ajax: {url: ajaxURL},
        aLengthMenu: [[25, 50, 100, 1000, -1], [25, 50, 100, 1000, "All"]],
        buttons: ['excel', 'pdf', 'print'],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'title', name: 'title'},
            {data: 'image', name: 'image'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'}
        ]
    });
    </script>
@endsection
