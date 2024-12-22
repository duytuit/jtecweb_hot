@extends('backend.layouts.master')
@php
    use App\Models\Employee;
    use App\Models\Department;
    use App\Helpers\ArrayHelper;
    use App\Models\Accessory;
@endphp
{{-- @section('title')
    @include('backend.pages.warehouses.partials.title')
@endsection --}}

@section('admin-content')
    @include('backend.pages.warehouses.partials.header-breadcrumbs')
    <div class="container-fluid">
        <input type="hidden" id="joinRoom" value="orderproduct">
        <input type="hidden" id="username" value="{{$uuid}}">
        <input type="hidden" id="device" value="{{$device}}">
        <input type="hidden" id="ip_client" value="{{$ip_client}}">
        <!-- START #form-search-advance -->
        <form id="form-search-advance" action="{{ route('admin.warehouses.index_ong') }}" method="get" class="hidden">
            <div id="search-advance" class="search-advance">
                <div class="row form-group">
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
                        <select class="form-control" name="type" id="order_type"  onchange="this.form.submit()">
                            <option value="112" {{ @$filter['type'] === '112' ? 'selected' : '' }}>Yêu Cầu Băng Dính,Ống,Keo,Thiếc</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" name="required_department_id" id="required_department_id" onchange="this.form.submit()">
                            <option value="">Bộ phận</option>
                            @foreach ($departments as $item)
                            <option value="{{ $item->id }}" {{ @$filter['required_department_id'] == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="status" class="form-control" style="width: 100%;" onchange="this.form.submit()">
                            <option value="0" {{ @$filter['status'] === '0' ? 'selected' : '' }}>Chưa Xuất</option>
                            <option value="1" {{ @$filter['status'] === '1' ? 'selected' : '' }}>Đã xuất</option>
                            <option value="111" {{ @$filter['status'] === '111' ? 'selected' : '' }}>Trạng thái</option>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <select name="locations" class="form-control" style="width: 100%;" onchange="this.form.submit()">
                            <option value="">Vị trí kho</option>
                            <option value="1" {{ @$filter['locations'] === '1' ? 'selected' : '' }}>Z->A</option>
                            <option value="0" {{ @$filter['locations'] === '0' ? 'selected' : '' }}>A->Z</option>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-warning btn-block">Tìm</button>
                    </div>
                    {{-- <div class="col-sm-1">
                        <a href="{{ route('admin.warehouses.exportExcel',Request::all()) }}" class="btn btn-success">Excel</a>
                    </div> --}}
                </div>
            </div>
        </form>
        <!-- END #form-search-advance -->
        <form id="form_lists" action="{{ route('admin.warehouses.action') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="" />
            <div class="table-responsive product-table overflow-x-scroll ">
                <table class="table table-bordered" id="checkCutMachine_table" style="min-width: 1440px; ">
                    <thead>
                        <tr>
                            <th align="center" width="3%"><input type="checkbox" class="greyCheck checkAll"
                                    data-target=".checkSingle" /></th>
                            <th>Trạng thái</th>
                            <th>Mã linh kiện</th>
                            <th width="70">Kho</th>
                            {{-- <th>Vị trí xưởng</th> --}}
                            <th width="20">Máy yc</th>
                            <th width="50">Số cuộn</th>
                            <th width="60">Số lượng</th>
                            <th width="60">Tồn kho</th>
                            {{-- <th>Tồn xưởng</th> --}}
                            <th width="20">Kích thước</th>
                            <th width="120">Ghi chú</th>
                            {{-- <th>Kho xuất</th> --}}
                            <th>Bộ phận yêu cầu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($lists)
                            @foreach ($lists as $index => $item)
                            @php
                                $content_form = json_decode($item->content_form);
                                $confirm_form = json_decode($item->confirm_form);
                                $department =  Department::findById($item->required_department_id);
                                $accessory = Accessory::findByCode($item->code);
                                $accessory_dept = json_decode(@$accessory->accessory_dept);
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
                            <tr class="list_content">
                                <td align="center"><input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                    class="greyCheck checkSingle" /></td>
                                <td style="width: 200px;">
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
                                                <div class="btn btn-sm btn btn-danger" onclick="modal_form(this,{{$item}})">Xuất hàng</div>
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
                                        </div>
                                        <div class="collapse">
                                            @if (@$confirm_form)
                                                @foreach ($confirm_form as $index => $item2 )
                                                    @php
                                                        $user = Employee::findEmployeeById(@$item2->user_id);
                                                    @endphp
                                                    <div><strong>Xuất lần {{($index +1)}}:</strong></div>
                                                    <div>Số lượng: {{@$item2->quantity}} {{ @$content_form->unit_price?'('.@$content_form->unit_price.')':''}}</div>
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
                                            <strong class="tooltip-text-title"><a href="javascript:;" onclick="showInvoice({{$accessory}})">{{ $item->code }}</a></strong>
                                        </span>
                                    </div>
                                </td>
                                <td> {{ @$item->location }}</td>
                                {{-- <td> {{ @$content_form->location_order }}</td> --}}
                                <td style="background-color: bisque;font-weight: bold"> {{ @ArrayHelper::list_machine[$content_form->pc_name] }}</td>
                                <td style="font-weight: bold">
                                    <div>{{@$item->quantity}}</div>
                                </td>
                                <td>
                                    <input type="text" class="quantity" id="quantity_detail_{{$item->id}}" value=" {{@$item->quantity_detail}}" style="width:80px">
                                </td>
                                <td style="color: #cbcbcb"> {{number_format(@$_accessory_dept_warehouses->inventory) }}</td>
                                {{-- <td> {{number_format(@$_accessory_dept->inventory)}}</td> --}}
                                <td> {{@$item->size ? @$item->size :'' }}</td>
                                <td>
                                    {!!@$item->order == 1 ?'<span class="badge badge-danger">Hàng gấp</span>' :'' !!}
                                </td>
                                {{-- <td>
                                    @if (@$confirm_form)
                                    {{@$confirm_form[0]->quantity}} {{ @$content_form->unit_price?'('.@$content_form->unit_price.')':''}}
                                    @endif
                                </td> --}}
                                <td>
                                    {{-- bộ phận yêu cầu --}}
                                    <strong {{$status_form_depts == 0 ?'style=color:red':''}}>{{@$_department_form_depts->name}}-{{ @$item->created_at }}</strong>
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
                            @php $list = [5, 10, 20, 50]; @endphp
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
                    <h5 class="modal-title">Thông tin INVOICE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>linh kiện</th>
                                <th>vị trí</th>
                                <th>số lượng</th>
                                <th>ngày tạo</th>
                                <th>thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="list_item_invoice">

                        </tbody>
                    </table>
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
    .content-selected{
         background-color:aqua;
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

        //  socket = io("http://192.168.207.6:8091", {
        //     cors: {
        //         origin: "http://192.168.207.6:8088",
        //         methods: ["GET", "POST"]
        //     },
        //     transports : ['websocket']
        // });

        // socket.on('connect', function() {
        //    console.log('connected');
        //    socketId = socket.id;
        //    console.log(socketId);
        // });

        // socket.emit('joinRoom', { room, username });

        // socket.on('warning', function(data) {
        //   if(data.status == false){
        //     socket.emit('createRoom', { room });
        //   }
        // });
        // socket.on('chat', function(msg) {
        //     info = JSON.parse(msg.message);
        //     console.log(info);
        //     if(check != info.data.code_required && info.status == 'order'){
        //         let message = "Code: "+info.data.code+"<br>"+"Bộ phận: "+info.data.department.name+"<br>"+"Số lượng: "+info.data.quantity+"<br>";
        //         toastr.error(message, 'Yêu cầu xuất:');
        //         check = info.data.code_required;
        //     }
        // });
        function print_required(id){
            if (confirm('Bạn có muốn in phiếu không?')) {
                $.ajax({
                    url: "{{route('admin.warehouses.createPrintPdf')}}",
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
        function showInvoice(invoice){
           let invoice_data = JSON.parse(invoice.invoice_data);
           html='';
           if(invoice_data.length > 0){
                invoice_data.forEach(function(item, index) {
                    html+=  '<tr>'+
                            '     <td>'+item.item+'</td>'+
                            '     <td>'+item.pl_no+'</td>'+
                            '     <td>'+item.qty+'</td>'+
                            '     <td>'+item.created_at+'</td>'+
                            '     <td><input type="checkbox" data-pl_no="'+item.pl_no+'" data-accessory="'+invoice.id+'" value="123" onclick="checkLocaltion(this)"></td>'+
                            ' </tr>';

                });
           }
            $('.list_item_invoice').html(html);
            $('#form_confirm').modal('show');
        }
        function checkLocaltion(event){
            let pl_no = $(event).attr("data-pl_no");
            let accessory_id = $(event).attr("data-accessory");
            let check = 0;
            if($(event).is(':checked')){
                check = 1;
            }
            $.ajax({
                url: "{{ route('admin.warehouses.checkLocaltion') }}",
                data: {
                    pl_no:pl_no,
                    accessory_id: accessory_id,
                    check:check
                },
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if(response.status == true){
                        toastr.success(response.message, 'Thông báo');
                    }else{
                        toastr.error(response.message, 'Thông báo');
                    }
                },
                error: function() {
                    // toastr.error('đã có lỗi xảy ra', 'Thất bại');
                }
            });
        }
        function modal_form(event,item){
            // event.preventDefault();
            //console.log(item);
            //let content_form = JSON.parse(item.content_form);
            //console.log(content_form);
            //$('.unit_price').text('('+content_form.unit_price+')');
            //$('#quantity').val(item.remaining);
            //$('#required_id').val(item.id);
            //$('.so_cuon').text("Số cuộn: "+item.quantity);
            //$('#form_confirm').modal('show');
            let quantity_detail = $('#quantity_detail_'+item.id).val().replace(/,/g, "");
            quantity_detail = parseFloat(quantity_detail);
            if((0 < quantity_detail) && (quantity_detail <= item.remaining)){

            }else{
                toastr.warning("Số lượng xuất phải nằm trong phạm vi 1 và "+item.remaining, 'Thông báo');
                return false;
            }
            var $temp = $("<input>");
            $("body").append($temp);
            // Get the text from the HTML element and set it as the textarea value
            $temp.val(item.code).select();
            // Copy the text to the clipboard
            document.execCommand("copy");
            // Remove the temporary textarea
            $temp.remove();
            $(event).prop("disabled", true);
            $.ajax({
                url: "{{ route('admin.warehouses.complete') }}",
                data: {
                    id:item.id,
                    quantity:quantity_detail
                },
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if(response.status == true){
                        var _time = new Date();
                        var current_time = _time.toLocaleTimeString()+ ' '+ _time.getDate()+'-'+(_time.getMonth()+1)+'-'+_time.getFullYear();
                        // socket.emit('chat', {room:'orderproduct', sender: username, message: JSON.stringify({
                        //     room:room,
                        //     username:username,
                        //     ip_client:ip_client,
                        //     device:device,
                        //     current_time:current_time,
                        //     data:response.data,
                        //     status:'confirm'
                        // }) });
                        $(event).closest('.list_content').remove();
                        toastr.success(response.message, 'Thông báo');
                    }
                    if(response.status == false){
                        toastr.error(response.message, 'Thông báo');
                    }
                },
                error: function() {
                    toastr.error('đã có lỗi xảy ra', 'Thất bại');
                }
            });
        }
        function change_quantity_detail(event,item){
            console.log(item);
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
                                var _time = new Date();
                                var current_time = _time.toLocaleTimeString()+ ' '+ _time.getDate()+'-'+(_time.getMonth()+1)+'-'+_time.getFullYear();
                                // socket.emit('chat', {room:'orderproduct', sender: username, message: JSON.stringify({
                                //     room:room,
                                //     username:username,
                                //     ip_client:ip_client,
                                //     device:device,
                                //     current_time:current_time,
                                //     data:response.data,
                                //     status:'confirm'
                                // }) });
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
