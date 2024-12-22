@extends('backend.layouts.master')
@php
    use App\Models\Employee;
    use App\Models\Department;
    use App\Helpers\ArrayHelper;
    use App\Models\Accessory;
@endphp
{{-- @section('title')
    @include('backend.pages.cutedps.partials.title')
@endsection --}}

@section('admin-content')
    @include('backend.pages.cutEdp.partials.header-breadcrumbs')
    <div class="container-fluid">
        <!-- START #form-search-advance -->
        <form id="form-search-advance" action="{{ route('admin.cutedps.index') }}" method="get" class="hidden">
            <div id="search-advance" class="search-advance">
                <div class="row form-group">
                    <div class="col-sm-1">
                        <span class="btn-group">
                            <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Tùy chọn<span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a class="btn-action" data-target="#form_lists" data-method="print_selected" href="javascript:;"><i class="fa fa-print"></i> In Phiếu</a></li>
                                <li><a class="btn-action" data-target="#form_lists" data-method="delete" href="javascript:;"><i class="fa fa-trash"></i> Xóa</a></li>
                            </ul>
                        </span>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="keyword" value="{{ @$filter['keyword'] }}" placeholder="Nhập từ khóa" class="form-control" />
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control date_picker" name="search_date" id="search_date"
                            value="{{ @$filter['search_date'] }}" placeholder="Ngày kiểm tra" autocomplete="off" onchange="this.form.submit()">
                            <noscript><input type="submit" value="Submit"></noscript>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" name="required_department_id" id="required_department_id">
                            <option value="">Bộ phận</option>
                            @foreach ($departments as $item)
                            <option value="{{ $item->id }}" {{ @$filter['required_department_id'] == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="status" class="form-control" style="width: 100%;">
                            <option value="">Trạng Thái In Phiếu</option>
                            <option value="1" {{ @$filter['status'] === '1' ? 'selected' : '' }}>Đã In</option>
                            <option value="0" {{ @$filter['status'] === '0' ? 'selected' : '' }}>Chưa In</option>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-warning btn-block">Tìm</button>
                    </div>
                    <div class="col-sm-1">
                        <a href="{{ route('admin.cutedps.exportExcel',Request::all()) }}" class="btn btn-success">Excel</a>
                    </div>
                </div>
            </div>
        </form>
        <!-- END #form-search-advance -->
        <form id="form_lists" action="{{ route('admin.cutedps.action') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="" />
            <div class="table-responsive product-table overflow-x-scroll ">
                <table class="table table-bordered" id="checkCutMachine_table" style="min-width: 1440px; ">
                    <thead>
                        <tr>
                            <th align="center" width="3%"><input type="checkbox" class="greyCheck checkAll"
                                    data-target=".checkSingle" /></th>
                            <th>Trạng thái</th>
                            <th>Mã sản phẩm</th>
                            <th>Chủng loại</th>
                            <th>Số dây</th>
                            {{-- <th>Xoắn</th> --}}
                            <th>Mã lot</th>
                            <th>Số lượng</th>
                            <th>Bộ phận yêu cầu</th>
                            <th>Bộ phận tiếp nhận</th>
                            <th>Người yêu cầu</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($lists)
                            @foreach ($lists as $index => $item)
                                @php
                                    $content_form = json_decode($item->content_form);
                                    $confirm_form = json_decode($item->confirm_form);
                                    $employee = Employee::findEmployeeById($item->created_by);
                                    $department =  Department::findById($item->required_department_id);
                                @endphp
                                {{-- bộ phận yêu cầu --}}
                                    @php
                                    $confirm_form_depts = $item->signatureSubmission()->where('type',1)->get();
                                    @endphp
                                    @foreach ($confirm_form_depts as $index_form_depts => $item_form_depts)
                                        {{-- Lấy bộ phận yêu cầu đầu tiên --}}
                                        @if ($index_form_depts == 0)
                                            @php
                                                $_department_form_depts = Department::findById($item_form_depts->department_id);
                                                $employees_form_depts = json_decode($item_form_depts->approve_id);
                                                if($employees_form_depts){
                                                    $employee_form_depts = Employee::findEmployeeById($employees_form_depts[0]);
                                                }
                                                $status_form_depts = $item_form_depts->status;
                                            @endphp
                                        @endif
                                    @endforeach
                                {{-- bộ phận tiếp nhận --}}
                                    @php
                                        $confirm_to_depts = $item->signatureSubmission()->where('type',2)->get();
                                    @endphp
                                    @foreach ($confirm_to_depts as $index_to_depts => $item_to_depts)
                                        {{-- Lấy bộ phận tiếp nhận đầu tiên --}}
                                        @if ($index_to_depts == 0)
                                            @php
                                                $_department_to_depts = Department::findById($item_to_depts->department_id);
                                                $employees_to_depts = json_decode($item_to_depts->approve_id);
                                                if($employees_to_depts){
                                                    $employee_to_depts = Employee::findEmployeeById($employees_to_depts[0]);
                                                }
                                                $status_to_depts = $item_to_depts->status;
                                            @endphp
                                        @endif
                                    @endforeach
                                <tr>
                                    <td align="center"><input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                        class="greyCheck checkSingle" /></td>
                                    <td>
                                        @if($item->status == 1)
                                           <span class="badge badge-info font-weight-100">Đã In</span>
                                        @else
                                            <span class="badge badge-secondary">Chưa In</span>
                                        @endif
                                        <a title="Xem EDP" target="_blank" class="d-inline-block btn-primary btn-sm text-white"
                                        href="{{ route('admin.cutedps.detail',['id' =>$item->id]) }}">
                                        <i class="fa fa-eye"></i></a>
                                    </td>
                                    <td style="width: 200px;">
                                        <div>
                                            <span class="tooltip-text">
                                                <div class="tooltip-text-action">
                                                    <a class="btn copyText">
                                                        <i class="fa fa-copy" style="color: blueviolet;"></i>
                                                    </a>
                                                    <a class="tooltip-text-alert">Sao chép mã</a>
                                                </div>
                                                <strong class="tooltip-text-title">{{ $item->code }}</strong>
                                            </span>
                                        </div>
                                    </td>
                                    <td> {{@$confirm_form->sensyu }}</td>
                                    <td> {{@$confirm_form->senban }}</td>
                                    {{-- <td> {{@$confirm_form->edp->twist1 }}</td> --}}
                                    <td> {{@$confirm_form->lot_no }}</td>
                                    <td> {{@$item->quantity }}</td>
                                    <td>
                                        {{-- bộ phận yêu cầu --}}
                                        <strong>{{$_department_form_depts->name}}</strong>
                                    </td>
                                    <td>
                                        {{-- bộ phận tiếp nhận --}}
                                        <strong>{{@$_department_to_depts->name}}</strong>
                                    </td>
                                    <td>
                                        {{-- Người yêu cầu --}}
                                        <div>{{@$employee->first_name . ' ' . @$employee->last_name }}</div>
                                        <div>{{ @$item->created_at }}</div>
                                   </td>
                                   <td>{{$item->content}}</td>
                                </tr>
                            @endforeach
                        @endif
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
<style>
      .expand-collapse-icon {
        font-size: 200px;
        width: 100%;
        height: 100%;
        position: relative;
        display: inline-block;
    }

    .expand-collapse-icon::before, .expand-collapse-icon::after {
        content: "";
        position: absolute;
        width: 1em;
        height: .16em;
        top: calc( (1em / 2 ) - .08em );
        background-color: white;
        transition: 0.3s ease-in-out all;
        border-radius: 0.03em;
        top: 13px;
        left: 5px;
    }

    .expand-collapse-icon::after {
        transform: rotate(90deg);
    }

    .collapsed.expand-collapse-icon::after {
        transform: rotate(180deg);
    }


    .collapsed.expand-collapse-icon::before {
        transform: rotate(90deg) scale(0);
    }
</style>
@section('scripts')
    <script>

        function print_required(id){
            if (confirm('Bạn có muốn in phiếu không?')) {
                $.ajax({
                    url: "{{route('admin.cutedps.createPrintPdf')}}",
                    method: 'GET',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id:id
                    },
                    success: function(data) {
                        toastr.success(data.message);
                    }
                });
            } else {
                return false;
            }
        }
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });

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

        function infoStatus(event){
            $(event).toggleClass('collapsed');
            $(event).closest(".information-export").find('.collapse').collapse('toggle')
        }
        $(document).ready(function() {
            $("#form_post").parsley();
            $(".save_form").on('click', function(e) {
                var f = $('#form_post');
                f.parsley().validate();
                if (f.parsley().isValid()) {
                    console.log('ok');
                    $.ajax({
                        url: f.attr('action'),
                        data: f.serialize(),
                        type: 'post',
                        dataType: 'json',
                        success: function(response) {
                            if(response.status == true){
                                toastr.success(response.message, 'Thông báo');
                            }
                            if(response.status == false){
                                toastr.error(response.message, 'Thông báo');
                            }
                            window.location.reload();
                        },
                        error: function() {
                            toastr.error('đã có lỗi xảy ra', 'Thất bại');
                        }
                    });
                }
                e.preventDefault();
            });
        });
    </script>
@endsection
