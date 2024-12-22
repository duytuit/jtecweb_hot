@extends('backend.layouts.master')
@php
    use App\Models\Employee;
    use App\Models\Department;
    use App\Helpers\ArrayHelper;
@endphp
{{-- @section('title')
    @include('backend.pages.requireds.partials.title')
@endsection --}}

@section('admin-content')
    @include('backend.pages.requestVpp.partials.header-breadcrumbs')
    <div class="container-fluid">
        <!-- START #form-search-advance -->
        <form id="form-search-advance" action="{{ route('admin.requireds.index') }}" method="get" class="hidden">
            <div id="search-advance" class="search-advance">
                <div class="row form-group space-5">
                    <div class="col-sm-2">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control date_picker" name="search_date" id="search_date"
                            value="{{ @$filter['search_date'] }}" placeholder="Ngày kiểm tra" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-warning btn-block">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- END #form-search-advance -->
        <form id="form_lists" action="{{ route('admin.requireds.action') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="" />
            <div class="table-responsive product-table overflow-x-scroll ">
                <table class="table table-bordered" id="checkCutMachine_table" style="min-width: 1440px; ">
                    <thead>
                        <tr>
                            <th width="3%"><input type="checkbox" class="greyCheck checkAll"
                                    data-target=".checkSingle" /></th>
                            <th>TT</th>
                            <th>Mã yêu cầu</th>
                            <th>Thông tin yêu cầu</th>
                            <th>Bộ phận yêu cầu</th>
                            <th>Bộ phận tiếp nhận</th>
                            <th>Ghi chú</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($lists)
                            @foreach ($lists as $index => $item)
                                @php
                                    $content_form = json_decode($item->content_form);
                                    $confirm_form = json_decode($item->confirm_form);
                                    $employee = $item->employee;
                                    $department = $item->department;
                                @endphp
                                <tr>
                                    <td><input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                            class="greyCheck checkSingle" /></td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div><strong>{{ $item->code_required }}</strong></div>
                                        <div> {{ $item->created_at }}</div>
                                        <a href="javascript:;" class="btn btn-sm btn-info" onclick="print_required({{$item->id}})">In phiếu</a>
                                    </td>
                                    <td>
                                         Mã linh kiện: <strong>{{ @$item->code }}</strong><br>
                                        <span class="text-success  ">{{ 'Số lượng: ' . @$item->quantity }}</span> <br>
                                        <span class="text-danger ">{{ 'Số lượng tồn: ' . @$content_form->inventory_accessory }}
                                        </span><br>
                                        {{ 'Định lượng: ' . $content_form->size }} <br>
                                        {{ 'Đơn vị: ' . @$content_form->unit_price }} <br>
                                        {{ 'Vị trí: ' . @$content_form->location_c }} <br>
                                        Loại số lượng: {{ @$item->usage_status == 2 ? 'Hàng chẵn' : 'Hàng lẻ' }} <br>
                                        {{ 'Người yêu cầu: ' . @$employee->first_name . ' ' . @$employee->last_name }}
                                    </td>
                                    <td>
                                        {{-- bộ phận yêu cầu --}}
                                        @php
                                            $confirm_form_depts = $item->signatureSubmission()->where('type',1)->get();
                                        @endphp
                                        @foreach ($confirm_form_depts as $index1 => $item1)
                                            @if ($item1->status == 2)
                                                {{-- Auto duyệt --}}
                                                @php
                                                    $_department = Department::findById($item1->department_id);
                                                @endphp
                                                <div>Bộ phận: <strong>{{@$_department->name }}</strong></div>
                                                <span class="badge badge-primary">Auto duyệt</span>
                                                <div>{{ 'Thời gian duyệt: ' . $item1->updated_at }}</div>
                                            @else
                                                @php
                                                    $employees = json_decode($item1->approve_id);
                                                    $employee = Employee::findEmployeeById($employees[0]);
                                                    $_department = Department::findById($item1->department_id);
                                                @endphp
                                                <div>Bộ phận: <strong>{{@$_department->name }}</strong></div>
                                                @if ($item1->status == 0)
                                                    <span class="badge badge-warning">Chưa duyệt</span>
                                                    <span>Cấp duyệt {{($index1+1)}}({!! ArrayHelper::positionTitle()[$item1->positions] !!}): <strong>{{ @$employee->first_name .' '. @$employee->last_name }}</strong></span><br>
                                                    @endif
                                                @if ($item1->status == 1)
                                                    <div class="badge badge-success">Đã duyệt</div>
                                                    <span>Cấp duyệt {{($index1+1)}}({!! ArrayHelper::positionTitle()[$item1->positions] !!}): <strong>{{ @$employee->first_name .' '. @$employee->last_name }}</strong></span><br>
                                                    <span>{{ 'Thời gian duyệt: ' . $item1->updated_at }}</span>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        {{-- bộ phận tiếp nhận --}}
                                        @php
                                            $confirm_form_depts = $item->signatureSubmission()->where('type',2)->get();
                                        @endphp
                                        @foreach ($confirm_form_depts as $index1 => $item1)
                                            @if ($item1->status == 2)
                                                {{-- Auto duyệt --}}
                                                 @php
                                                    $_department = Department::findById($item1->department_id);
                                                 @endphp
                                                <div>Bộ phận: <strong>{{@$_department->name }}</strong></div>
                                                <span class="badge badge-primary">Auto duyệt</span>
                                                <div>{{ 'Thời gian duyệt: ' . $item1->updated_at }}</div>
                                            @else
                                                @php
                                                    $employees = json_decode($item1->approve_id);
                                                    $employee = Employee::findEmployeeById($employees[0]);
                                                    $_department = Department::findById($item1->department_id);
                                                @endphp
                                                <div>Bộ phận: <strong>{{@$_department->name }}</strong></div>
                                                @if ($item1->status == 0)
                                                    <span class="badge badge-warning">Chưa duyệt</span>
                                                    <span>Cấp duyệt {{($index1+1)}}({!! ArrayHelper::positionTitle()[$item1->positions] !!}): <strong>{{ @$employee->first_name .' '. @$employee->last_name }}</strong></span><br>
                                                    @endif
                                                @if ($item1->status == 1)
                                                    <div class="badge badge-success">Đã duyệt</div>
                                                    <span>Cấp duyệt {{($index1+1)}}({!! ArrayHelper::positionTitle()[$item1->positions] !!}): <strong>{{ @$employee->first_name .' '. @$employee->last_name }}</strong></span><br>
                                                    <span>{{ 'Thời gian duyệt: ' . $item1->updated_at }}</span>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $item->content }}</td>
                                    <td style="width: 250px;">
                                        <div class="information-export">
                                            <div style="display: flex;gap: 0.2em;justify-content: center;">
                                                <div>
                                                    @if ($item->status == 0)
                                                        <a href="javascript:;" class="btn text-light btn-danger" onclick="modal_form({{$item}})">Xuất hàng</a>
                                                    @else
                                                        <div class="btn btn-sm btn-primary">Đã xuất đủ hàng</div>
                                                    @endif
                                                </div>
                                                <div >
                                                    <a class="btn btn-primary text-light expand-collapse-icon collapse-toggle" onclick="infoStatus(this)"></a>
                                                </div>
                                            </div>
                                            <div class="collapse">
                                                @if (@$confirm_form)
                                                @foreach ($confirm_form as $index => $item2 )
                                                    @php
                                                        $user = Employee::findEmployeeById(@$item2->user_id);
                                                    @endphp
                                                    <div><strong>Xuất lần {{($index +1)}}:</strong></div>
                                                    <div>Số lượng: {{$item2->quantity}}</div>
                                                    <div>Người xuất: <strong>{{@$user->first_name.' '.@$user->last_name}}</strong></div>
                                                    <div>{{ date('Y-m-d H:i:s', strtotime(@$item2->date))}}</div>
                                                    <div>{{$item2->note}}</div>
                                                @endforeach
                                            @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->status == 0)
                                            <a title="Xóa" class="btn-danger btn-sm text-white" href="{{ route('admin.requireds.trashed.destroy', ['id' => $item->id]) }}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endif
                                    </td>
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
     {{-- modal --}}
     <div id="form_confirm" class="modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xuất hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_post" class="form-horizontal" role="form" method="POST" action="{{ route('admin.requireds.complete') }}">
                        <input type="hidden" name="id" id="required_id">
                        <div class="form-group">
                            <label for="quantity">Số lượng xuất</label>
                            <input type="text" class="form-control quantity" name="quantity" id="quantity" placeholder="Nhập số lượng" required data-parsley-required-message="Trường số lượng là bắt buộc">
                        </div>
                        <div class="form-group">
                            <label for="note">Ghi chú</label>
                            <input type="text" class="form-control" name="note" id="note" placeholder="Ghi chú" autocomplete="false">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary save_form">Xuất hàng</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Thoát</button>
                </div>
            </div>
        </div>
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
                    url: "{{route('admin.requireds.createPrintPdf')}}",
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
        function modal_form(item){
            console.log(item);
            $('#quantity').val(item.remaining);
            $('#required_id').val(item.id);
            $('#form_confirm').modal('show');
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
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000)
                        },
                        error: function() {
                            toastr.error('đã có lỗi xảy ra', 'Thất bại');
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000)
                        }
                    });
                }
                e.preventDefault();
            });
        });
    </script>
@endsection
