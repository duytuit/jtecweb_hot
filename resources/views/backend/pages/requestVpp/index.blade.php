@extends('backend.layouts.master')
@php
    use App\Models\Employee;
    use App\Models\Department;
    use App\Models\Required;
    use App\Models\Accessory;
    use App\Helpers\ArrayHelper;
@endphp
{{-- @section('title')
    @include('backend.pages.requireds.partials.title')
@endsection --}}
@section('admin-content')

    @include('backend.pages.requestVpp.partials.header-breadcrumbs')
    <div class="container-fluid">
        <input type="hidden" id="joinRoom" value="orderproduct">
        <input type="hidden" id="username" value="{{$uuid}}">
        <input type="hidden" id="device" value="{{$device}}">
        <input type="hidden" id="ip_client" value="{{$ip_client}}">
        <!-- START #form-search-advance -->
        <form id="form-search-advance" action="{{ route('admin.requireds.index') }}" method="get" class="hidden">
            <div id="search-advance" class="search-advance">
                <div class="row form-group space-5">
                    <div class="col-sm-1">
                        <span class="btn-group">
                            <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Tác vụ <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a class="btn-action" data-target="#form_lists" data-method="delete" href="javascript:;"><i class="fa fa-trash" style="color: red;"></i> Xóa</a></li>
                                <li><a class="btn-action" data-target="#form_lists" data-method="confirm" href="javascript:;"><i class="fa fa-check" style="color: green;"></i> Duyệt</a></li>
                                <li><a class="btn-action" data-target="#form_lists" data-method="unconfirm" href="javascript:;"><i class="fa fa-times"></i> Bỏ duyệt</a></li>
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
                    {{-- <div class="col-sm-2 w-100">
                        <select style="width: 100%" class="form-control custom-select select-department" multiple name="required_department_id[]" id="required_department_id">
                            <option value="" selected>Bộ phận</option>
                            @foreach ($departments as $item)
                                <option value="{{ $item->id }}" {{ (@$filter['required_department_id'] &&  in_array($item->id,@$filter['required_department_id'])) ? 'selected' :'' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="col-sm-2 w-100">
                        <select style="width: 100%" class="form-control select2" name="machine" id="machine" onchange="this.form.submit()">
                            <option value="" selected>Máy yêu cầu</option>
                            @foreach ($groupMachineRequired as $item)
                               @if (@$item->pc_name)
                                  <option value="{{ @$item->pc_name }}" {{ @$filter['machine'] == @$item->pc_name  ? 'selected' :'' }}>{{ @$item->pc_name }}</option>
                               @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2 w-100">
                        <select class="form-control" name="type" id="order_type"  onchange="this.form.submit()">
                            <option value="">Yêu cầu</option>
                            <option value="111" {{ @$filter['type'] === '111' ? 'selected' : '' }}>Yêu Cầu Dây Điện và Tanshi</option>
                            <option value="112" {{ @$filter['type'] === '112' ? 'selected' : '' }}>Yêu Cầu Băng Dính, Ống, Keo</option>
                            <option value="4" {{ @$filter['type'] === '4' ? 'selected' : '' }}>Yêu Cầu Thiếc</option>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <select name="status" class="form-control" style="width: 100%;" onchange="this.form.submit()">
                            <option value="">Trạng thái</option>
                            <option value="1" {{ @$filter['status'] === '1' ? 'selected' : '' }}>Đã xuất</option>
                            <option value="0" {{ @$filter['status'] === '0' ? 'selected' : '' }}>Chưa Xuất</option>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-warning btn-block">Tìm</button>
                    </div>
                    <div class="col-sm-1">
                        <a href="{{ route('admin.requireds.exportExcel',Request::all()) }}" class="btn btn-success">Excel</a>
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
                            <th align="center" width="3%"><input type="checkbox" class="greyCheck checkAll"
                                    data-target=".checkSingle" /></th>
                            <th>Thao tác</th>
                            <th>Mã linh kiện</th>
                            <th>Loại yc</th>
                            <th>Vị trí kho</th>
                            <th>Vị trí xưởng</th>
                            <th>Vị trí máy yc</th>
                            <th>Số cuộn</th>
                            <th>Số lượng</th>
                            <th>Tồn kho</th>
                            <th>Tồn xưởng</th>
                            <th>Kích thước</th>
                            <th>Ghi chú</th>
                            <th>Kho xuất</th>
                            <th>Bộ phận yêu cầu</th>
                            <th>Người yêu cầu</th>
                            <th>Nhóm duyệt yc</th>
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
                                    $accessory = Accessory::findByCode($item->code);
                                    $accessory_dept = json_decode($accessory->accessory_dept);
                                    if($accessory_dept){
                                        $_accessory_dept = array_filter($accessory_dept, fn($element) => $element->location_c == sprintf("%04s", $department->code));
                                        if($_accessory_dept){
                                            $_accessory_dept = current($_accessory_dept);
                                        }
                                        $_accessory_dept_warehouses = array_filter($accessory_dept, fn($element) => $element->location_c == sprintf("%04s", '0111'));
                                        if($_accessory_dept_warehouses){
                                            $_accessory_dept_warehouses = current($_accessory_dept_warehouses);
                                        }
                                    }
                                @endphp
                                {{-- bộ phận yêu cầu --}}
                                @php
                                  $confirm_form_depts = $item->signatureSubmission()->where('type',1)->get();
                                  $list_emp_confirm = [];
                                @endphp
                                @foreach ($confirm_form_depts as $index_form_depts => $item_form_depts)
                                    {{-- Lấy bộ phận yêu cầu đầu tiên --}}
                                    @if ($index_form_depts == 0)
                                        @php
                                            $confirm_updated_at = $item_form_depts->updated_at;
                                            $employees_form_depts = json_decode($item_form_depts->approve_id);
                                            if(count( $employees_form_depts) > 0){
                                                foreach ($employees_form_depts as $__key => $__value) {
                                                    $__emp = Employee::findEmployeeById($__value);
                                                    if($__emp){
                                                        $list_emp_confirm[] =$__emp->code."-".$__emp->first_name.' '.$__emp->last_name;
                                                    }
                                                }
                                            }
                                            $employee_signature = Employee::findEmployeeById($item_form_depts->signature_id);
                                            $status_form_depts = $item_form_depts->status;
                                        @endphp
                                    @endif
                                @endforeach
                                <tr>
                                    <td align="center"><input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                        class="greyCheck checkSingle" /></td>
                                    <td style="width: 220px;">
                                        <div class="information-export">
                                            <div style="display: flex;gap: 0.2em;justify-content: center;">
                                                @if (@$content_form->confirm_by)
                                                   @php
                                                        $employee_confirm = Employee::findEmployeeById($content_form->confirm_by);
                                                   @endphp
                                                    <div>
                                                        <button type="button" class="btn btn-outline-success"
                                                            data-toggle="tooltip" data-html="true"
                                                            data-placement="bottom"
                                                            title="{{ $employee_confirm->first_name.' '.$employee_confirm->last_name}} <br>
                                                            {{ 'Duyệt lúc: '.$content_form->confirm_date }} ">
                                                            <i class="fa fa-check" style="color: green;"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                                <div style="width: 100%;display: grid;">
                                                    @if ($item->status == 0)
                                                        <div class="btn btn-sm btn-danger">Chưa xuất</div>
                                                    @else
                                                        @if ($confirm_form[0]->quantity < $item->quantity_detail )
                                                                <div class="btn btn-sm btn-success">Đã xuất hàng lẻ</div>
                                                        @else
                                                            <div class="btn btn-sm btn-primary">Đã xuất đủ hàng</div>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div >
                                                    <a class="btn btn-primary text-light expand-collapse-icon collapse-toggle" onclick="infoStatus(this)"></a>
                                                </div>
                                                <div>
                                                    <a style="height: 100%;" class="btn btn-sm btn-info text-light" title="In phiếu" onclick="print_required({{$item->id}})"> <i class="fa fa-print" style="margin: 3px auto"></i></a>
                                                </div>
                                                @if ($item->status == 0)
                                                <div>
                                                    <a style="height: 100%;" title="Xóa" class="btn btn-sm btn-danger text-white" onclick="confirmDelete({{$item->id}})">
                                                        <i class="fa fa-trash" style="margin: 3px auto"></i>
                                                    </a>
                                                </div>
                                            @endif
                                            </div>
                                            <div class="collapse">
                                                @if (@$confirm_form)
                                                    @foreach ($confirm_form as $index => $item2 )
                                                        @php
                                                            $user = Employee::findEmployeeById(@$item2->user_id);
                                                        @endphp
                                                        <div><strong>Xuất lần {{($index +1)}}:</strong></div>
                                                        <div>Số lượng: {{$item2->quantity}} {{ @$content_form->unit_price?'('.@$content_form->unit_price.')':''}}</div>
                                                        <div>Người xuất: <strong>{{@$user->first_name.' '.@$user->last_name}}</strong></div>
                                                        <div>{{ date('Y-m-d H:i:s', strtotime(@$item2->date))}}</div>
                                                        <div>{{$item2->note}}</div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
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
                                    <td> {{Required::required_type[$item->type]}}</td>
                                    <td> {{ @$item->location }}</td>
                                    <td> {{ @$content_form->location_order }}</td>
                                    <td style="background-color: bisque;font-weight: bold"> {{ @ArrayHelper::list_machine[$content_form->pc_name]??@$content_form->pc_name }}</td>
                                    <td style="font-weight: bold">
                                        <div>{{$item->quantity}}</div>
                                    </td>
                                    <td> {{number_format(@$item->quantity_detail )}}</td>
                                    <td> {{number_format(@$_accessory_dept_warehouses->inventory) }}</td>
                                    <td> {{number_format(@$_accessory_dept->inventory)}}</td>
                                    <td> {{@$item->size ? @$item->size :'' }}</td>
                                    <td>
                                        @if ($item->usage_status == 1)
                                            <span class="badge badge-success">Chẵn</span>
                                        @else
                                            <span class="badge badge-primary">Lẻ</span>
                                        @endif
                                         {!!@$item->order == 1 ?'<span class="badge badge-danger">Hàng gấp</span>' :'' !!}
                                    </td>
                                    <td>
                                        @if (@$confirm_form)
                                           {{$confirm_form[0]->quantity}} {{ @$content_form->unit_price?'('.@$content_form->unit_price.')':''}}
                                        @endif
                                    </td>
                                    <td>
                                        {{-- bộ phận yêu cầu --}}
                                        <strong>{{@$_department_form_depts->name}}</strong>
                                    </td>
                                    <td>
                                        {{-- Người yêu cầu --}}
                                        <div>{{@$employee->first_name . ' ' . @$employee->last_name }}</div>
                                        <div>{{ @$item->created_at }}</div>
                                   </td>
                                   <td>
                                        @if ($status_form_depts == 0)
                                            <div>
                                                <button type="button" class="btn btn-outline-danger"
                                                    data-toggle="tooltip" data-html="true"
                                                    data-placement="bottom"  title="Chưa duyệt <br>">
                                                    <i class="fa fa-check" style="color: red;"></i>
                                                </button>
                                                @foreach ($list_emp_confirm as $__index => $__item )
                                                  <span>{{$__item}}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <div>
                                                <button type="button" class="btn btn-outline-success"
                                                    data-toggle="tooltip" data-html="true"
                                                    data-placement="bottom"
                                                    title="Đã duyệt">
                                                    <i class="fa fa-check" style="color: green;"></i>
                                                </button>
                                                {{ @$employee_signature->first_name.' '.@$employee_signature->last_name}}-{{ 'Duyệt lúc: '.$confirm_updated_at }}
                                            </div>
                                        @endif
                                   </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            {{-- <div class="row">
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
                            @php $list = [5, 10, 20, 50]; @endphp
                            @foreach ($list as $num)
                                <option value="{{ $num }}" {{ $num == $per_page ? 'selected' : '' }}>
                                    {{ $num }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
            </div> --}}
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
         var socket;
         var socketId='';
         var check = '';
         var room = document.getElementById('joinRoom').value;
         var username = document.getElementById('username').value;
         var device = document.getElementById('device').value;

         socket = io("http://192.168.207.6:8091", {
            cors: {
                origin: "http://192.168.207.6:8088",
                methods: ["GET", "POST"]
            },
            transports : ['websocket']
        });

        socket.on('connect', function() {
           console.log('connected');
           socketId = socket.id;
           console.log(socketId);
        });

        socket.emit('joinRoom', { room, username });

        socket.on('warning', function(data) {
          if(data.status == false){
            socket.emit('createRoom', { room });
          }
        });
        socket.on('chat', function(msg) {
            info = JSON.parse(msg.message);
            console.log(info);
            if(check != info.data.code_required && info.status == 'confirm'){
                let confirm_form = JSON.parse(info.data.confirm_form)
                console.log(confirm_form);
                let message = "Code: "+info.data.code+"<br>"+"Bộ phận: "+info.data.department.name+"<br>"+"Số lượng: "+confirm_form[confirm_form.length-1].quantity+"<br>"+confirm_form[confirm_form.length-1].note+"<br>";
                toastr.success(message, 'Đã xuất hàng:');
                check = info.data.code_required;
            }
        });
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
        function confirmDelete(id){
            swal.fire({
                title: "Bạn có chắc chắn muốn xóa!",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Vâng, Xóa nó!",
                cancelButtonText:"Không",
                showCancelButton: true,
            }).then((result) => {
                if (result.value) {
                        $.ajax({
                        type: "GET",
                        url: "{{ route('admin.requireds.trashed.destroy') }}",
                        data: {id:id},
                        success: function (data) {
                            if(data.status == true){
                                toastr.success(data.message, 'Thông báo');
                                window.location.reload();
                            }else{
                                toastr.error(data.message, 'Thông báo');
                            }

                        }
                    });
                }
            })
        }
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
        $(".select-department").select2({
            placeholder: "Trạng thái"
        });
    </script>
@endsection
