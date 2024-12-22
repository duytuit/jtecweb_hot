@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.exams.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.exams.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.pages.exams.partials.top-show1')
        @include('backend.layouts.partials.messages')
        <form id="form-search" action="{{ route('admin.exams.audit') }}" method="get">
            <div class="row form-group">
                <div class="col-sm-8">
                    <span class="btn-group">
                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Tác vụ <span
                                class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a class="btn-action" data-target="#form_lists" data-method="delete" href="javascript:;"><i
                                        class="fa fa-trash" style="color: #cb3030;"></i> Xóa</a></li>
                            <li><a class="btn-action" data-target="#form_lists" data-method="active" href="javascript:;"><i
                                        class="fa fa-check" style="color: green;"></i> Duyệt</a></li>
                            <li><a class="btn-action" data-target="#form_lists" data-method="inactive"
                                    href="javascript:;"><i class="fa fa-times"></i> Bỏ duyệt</a></li>
                        </ul>
                    </span>
                    <a href="#" class="btn btn-info"><i class="fa fa-edit"></i> Thêm mới</a>
                    <a href="{{ route('admin.exams.reportFailAnswer',Request::all()) }}" class="btn btn-secondary"><i class="fa fa-chart-bar"></i> Thống kê câu trả lời</a>
                    <a href="{{ route('admin.exams.exportExcelAudit', Request::all()) }}" class="btn btn-success"><i
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
        <form id="form-search-advance" action="{{ route('admin.exams.audit') }}" method="get" class="hidden">
            <div id="search-advance" class="search-advance">
                <div class="row form-group space-5">
                    <div class="col-sm-2">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"> <i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control date_picker" name="from_date" id="from_date"
                                value="{{ @$filter['from_date'] }}" placeholder="Từ..." autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"> <i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control date_picker" name="to_date" id="to_date"
                                value="{{ @$filter['to_date'] }}" placeholder="Đến..." autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <select name="cycle_name" class="form-control" style="width: 100%;">
                            <option value="">Kỳ thi</option>
                             @foreach ($cycleNames as $item)
                                <option value="{{$item}}" {{ @$filter['cycle_name'] == $item ? 'selected' : '' }}>{{$item}}</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="status" class="form-control" style="width: 100%;">
                            <option value="">Trạng thái</option>
                            <option value="1" {{ @$filter['status'] === '1' ? 'selected' : '' }}>Đạt</option>
                            <option value="0" {{ @$filter['status'] === '0' ? 'selected' : '' }}>Chưa đạt</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-warning btn-block">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </form><!-- END #form-search-advance -->
        <form id="form_lists" action="{{ route('admin.exams.action') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="" />
            <input type="hidden" name="status" value="" />
            <div class="table-responsive product-table">
                <table class="table table-bordered" id="exams_table">
                    <thead>
                        <tr>
                            <th width="3%"><input type="checkbox" class="greyCheck checkAll"
                                    data-target=".checkSingle" /></th>
                            <th>TT</th>
                            <th>Mã NV</th>
                            <th>Tên NV</th>
                            <th>Công đoạn</th>
                            <th>Ngày vào công ty</th>
                            <th>Tổng câu</th>
                            <th>Trả lời đúng</th>
                            <th>Điểm</th>
                            <th>Thời gian làm bài</th>
                            <th>Kết quả</th>
                            <th>Ngày thi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $code = 0;
                            $cycle_name = 0;
                            $check = 0;
                            $examinations = 0;
                        @endphp
                        @foreach ($lists as $index => $item)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                        class="greyCheck checkSingle" /></td>
                                <td>{{ $index + 1 }}</td>
                                @if ($cycle_name != $item->cycle_name)
                                    @php
                                        $check = 1;
                                        $cycle_name = $item->cycle_name;
                                    @endphp
                                @else
                                    @php
                                        $check = 0;
                                    @endphp
                                @endif
                                @if ($code != $item->code)
                                    @php
                                        $check = 1;
                                        $code = $item->code;
                                    @endphp
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->sub_dept == 1 ? 'Cắm' : '' }}</td>
                                @else
                                    <td colspan="3"></td>
                                @endif
                                <td>{{ @$item->employee->begin_date_company ? date('d-m-Y', strtotime(@$item->employee->begin_date_company)) : '' }}
                                </td>
                                <td>{{ $item->total_questions }}</td>
                                <td>{{ $item->results }}</td>
                                <td>{{ $item->scores }}</td>
                                <td>{{ $item->counting_time }}</td>
                                <td>
                                    @if ($item->status)
                                        <span class="badge badge-success font-weight-100">Đạt</span>
                                    @else
                                        <span class="badge badge-warning">Chưa Đạt</span>
                                    @endif
                                </td>
                                <td>{{ date('d-m-Y', strtotime(@$item->created_at)) }}</td>
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

        function deleteItem(params) {
            swal.fire({
                title: "Bạn có chắc chắn?",
                text: "bản ghi này sẽ được chuyển vào thùng rác!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Vâng, Xóa nó!"
            }).then((result) => {
                if (result.value) {
                    $("#deleteForm" + params).submit();
                }
            })
        }
        $('.detailReport1').click(function(e) {
            e.preventDefault();
            var emp = $(this).data('emp');
            var type = $(this).data('type');
            values = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                emp: emp,
                type: type
            };
            $.ajax({
                url: "{{ route('exam.detailReport1') }}",
                method: 'POST',
                data: values,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(response);
                    link.download = `Invoice_details_report1.xlsx`;
                    link.click();
                }
            });
        })
    </script>
@endsection
