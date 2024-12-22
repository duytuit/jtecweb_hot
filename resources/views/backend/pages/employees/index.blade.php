@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.employees.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.employees.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.pages.employees.partials.top-show')
        @include('backend.layouts.partials.messages')

        <form id="form-search" action="{{ route('admin.employees.index') }}" method="get">
            <div class="row form-group">
                <div class="col-sm-8">
                    <span class="btn-group">
                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Tác vụ <span
                                class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a class="btn-action" data-target="#form_lists" data-method="delete" href="javascript:;"><i
                                        class="fa fa-trash" style="color: #cb3030;"></i> Xóa</a></li>
                            <li><a class="btn-action" data-target="#form_lists" data-method="report" href="javascript:;"><i
                                        class="fa fa-save"></i> Có thống kê</a></li>
                            <li><a class="btn-action" data-target="#form_lists" data-method="unreport"
                                    href="javascript:;"><i class="fa fa-save"></i> Không thống kê</a></li>
                        </ul>
                    </span>
                    <a href="{{ route('admin.employees.create') }}" class="btn btn-info"><i class="fa fa-edit"></i> Thêm
                        mới</a>
                    <span class="btn-group">
                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Thêm từ Excel
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu import-excel">
                            <li>
                                <form action="{{ route('admin.employees.importExcelData') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group">
                                        <input type="file" name="import_file" class="form-control" placeholder=" ">
                                        <button type="submit" class="btn btn-primary" name="upload"><i
                                                class="fa fa-import"></i>Nhập</button>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </span>
                    <a href="{{ route('admin.employees.exportExcel', Request::all()) }}" class="btn btn-success"><i
                            class="fa fa-edit"></i> Xuất Excel</a>
                </div>
                <div class="col-sm-4 text-right">
                    <div class="input-group">
                        <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Nhập từ khóa"
                            class="form-control" />
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-info"><span class="fa fa-search"></span></button>
                            <button type="button" class="btn btn-warning btn-search-advance" data-toggle="show"
                                data-target=".search-advance"><span class="fa fa-filter"></span></button>
                        </div>
                    </div>
                </div>
            </div>
        </form><!-- END #form-search -->
        <!-- START #form-search-advance -->
        <form id="form-search-advance" action="{{ route('admin.employees.index') }}" method="get" class="hidden">
            <div id="search-advance" class="search-advance" style="display: {{ $advance ? 'block' : 'none' }};">
                <div class="row form-group">
                    <div class="col-md-2">
                        <select name="ids[]" multiple id="codecode" class="form-control" style="width:100%">
                            <option value="">Chọn mã nhân viên</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"> <i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control date_picker datepicker" name="from_date"
                                id="from_date" value="{{ @$filter['from_date'] }}" placeholder="Từ..." autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"> <i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control date_picker datepicker" name="to_date"
                                id="to_date" value="{{ @$filter['to_date'] }}" placeholder="Đến..."
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <select name="dept" class="form-control" style="width: 100%;">
                            <option value="">Bộ phận</option>
                             @foreach ($depts as $item)
                                <option value="{{$item->id}}" {{ @$filter['dept'] == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <select class="form-control custom-select" name="worker">
                            <option value="">Tình trạng làm việc</option>
                            @foreach ($workers as $worker)
                                <option value="{{ $worker['id'] }}">
                                    {{ $worker['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <select class="form-control custom-select" name="positions">
                            <option value="">Chức vụ</option>
                            @foreach ($positions as $key => $position)
                                <option value="{{  $key }}">
                                    {{ $position }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-warning btn-block">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- END #form-search-advance -->

        <form id="form_lists" action="{{ route('admin.employees.action') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="" />
            <div class="table-responsive product-table">
                <table class="table table-bordered ajax_view" id="employees_table">
                    <thead>
                        <tr>
                            <th width="3%"><input type="checkbox" class="greyCheck checkAll"
                                    data-target=".checkSingle" /></th>
                            <th>STT</th>
                            <th>Mã Code</th>
                            <th>Tên nhân viên</th>
                            <th>Thống kê làm bài kiểm tra</th>
                            <th>Bộ phận</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $index => $item)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                        class="greyCheck checkSingle" /></td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->first_name . ' ' . $item->last_name }}</td>

                                <td>
                                    @if ($item->status_exam == 1)
                                        <span class="badge badge-success font-weight-100">Có</span>
                                    @else
                                        <span class="badge badge-warning">Không</span>
                                    @endif
                                </td>
                                <td>{{ @$item->employeeDepartment->department->name }}</td>
                                <td>
                                    <a title="Lịch sử thao tác" target="_blank" class="d-inline-block btn-info btn-sm text-white"
                                    href="{{ route('admin.activitys.index', ['modelId' => $item->id,'content_type' =>get_class($item)]) }}"><i
                                        class="fa fa-history"></i> </a>
                                    <a title="Sửa" class=" d-inline-block mx-1 btn-purple btn-sm text-white"
                                        href="{{ route('admin.employees.edit', ['id' => $item->id]) }}"><i
                                            class="fa fa-edit"></i></a>
                                    <a title="Xóa" class=" d-inline-block btn-danger btn-sm text-white"
                                        href="{{ route('admin.employees.trashed.destroy', ['id' => $item->id]) }}"><i
                                            class="fa fa-trash"></i></a>
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
                        <select name="per_page" class="form-control" style="display: inline;width: auto;"
                            data-target="#form_lists">
                            @php $list = [5, 10, 20, 50, 100, 200]; @endphp
                            @foreach ($list as $num)
                                <option value="{{ $num }}" {{ $num == $per_page ? 'selected' : '' }}>
                                    {{ $num }}</option>
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
        get_data_select_code({
            object: '#codecode',
            url: '{{ url('admin/departments/ajaxGetSelectCode') }}',
            data_id: 'id',
            data_code: 'code',
            data_first_name: 'first_name',
            data_last_name: 'last_name',
            title_default: 'Chọn mã nhân viên',

        });

        function get_data_select_code(options) {
            $(options.object).select2({
                ajax: {
                    url: options.url,
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        return query;
                    },
                    processResults: function(json, params) {
                        var results = [{
                            id: '',
                            text: options.title_default
                        }];

                        for (i in json.data) {
                            var item = json.data[i];
                            results.push({
                                id: item[options.data_id],
                                text: item[options.data_code] + ' - ' + item[options.data_first_name] +
                                    ' ' +
                                    item[options.data_last_name]
                            });
                        }
                        return {
                            results: results,
                        };
                    },
                    minimumInputLength: 3,
                }
            });
        }
        // const ajaxURL = "<?php echo Route::is('admin.employees.trashed' ? 'employees/trashed/view' : 'employees'); ?>";
        // $('table#employees_table').DataTable({
        //     dom: 'Blfrtip',
        //     language: {
        //         processing: "<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Loading Data..."
        //     },
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         url: ajaxURL
        //     },
        //     aLengthMenu: [
        //         [25, 50, 100, 1000, -1],
        //         [25, 50, 100, 1000, "All"]
        //     ],
        //     buttons: ['excel', 'pdf', 'print'],
        //     columns: [{
        //             data: 'DT_RowIndex',
        //             name: 'DT_RowIndex'
        //         },
        //         {
        //             data: 'title',
        //             name: 'title'
        //         },
        //         {
        //             data: 'image',
        //             name: 'image'
        //         },
        //         {
        //             data: 'status',
        //             name: 'status'
        //         },
        //         {
        //             data: 'action',
        //             name: 'action'
        //         }
        //     ]
        // });
    </script>
@endsection
