@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.requireds.partials.title')
@endsection
@php
    use App\Models\EmployeeDepartment;
    use App\Models\Employee;
    use App\Models\Department;
    use App\Helpers\ArrayHelper;
@endphp

@section('admin-content')
    @include('backend.pages.requireds.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.layouts.partials.messages')
        <div class="create-page">
            <input type="hidden" id="joinRoom" value="orderproduct">
            <input type="hidden" id="username" value="{{$uuid}}">
            <input type="hidden" id="device" value="{{$device}}">
            <input type="hidden" id="ip_client" value="{{$ip_client}}">
            <form id="post_form" action="{{ route('admin.requireds.store') }}" method="POST" enctype="multipart/form-data"
                data-parsley-validate data-parsley-focus="first">
                @csrf
                <div class="form-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-header">Bộ phận yêu cầu</h5>
                                <div class="row form-group">
                                    <div class="col-sm-4">
                                        <label class="control-label" for="department_id">Bộ phận</label>
                                        <select class="form-control" name="department_id" id="department_id" style="width:100%">
                                            @foreach ($departments as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" for="created_by">Người yêu cầu</label>
                                        <input type="text" class="form-control" id="created_by" name="created_by"
                                            value="{{ $employee->first_name . ' ' . $employee->last_name }}" readonly />
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">Máy yêu cầu</label>
                                        <select name="selecMachine" id="selecMachine" class="form-control select2" style="width: 100%;">
                                            <option value="">Máy tính yêu cầu</option>
                                            @foreach ($machineLists as $key => $item)
                                                <option value="{{ $key }}" @if (@$filter['selecMachine'] == $key ) selected @endif>{{ $key }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label class="control-label" for="searchcode">SCAN CODE<span
                                                class="required">*</span></label>
                                        <div class="suggestions-container">
                                           <input type="text" class="form-control" id="searchcode" name="searchcode" value="{{ old('searchcode') }}" placeholder="scan code" />
                                           <div class="suggestions"></div>
                                        </div>
                                        <input type="hidden" id="code" name="code">
                                        <label class="control-label" for="searchcode">Mã linh kiện:</label>
                                        <div><strong class="malinhkien"></strong></div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label" for="order_type">Loại Yêu Cầu</label>
                                        <select class="form-control" name="type" id="order_type" style="font-weight: bold">
                                            <option value="0">Yêu Cầu Dây Điện</option>
                                            <option value="1" {{$employeeDepartment->department_id == 7?'selected':''}}>Yêu Cầu Tanshi</option>
                                            <option value="2">Yêu Cầu Ống</option>
                                            <option value="3">Yêu Cầu Băng Dính</option>
                                            <option value="4">Yêu Cầu Keo</option>
                                            <option value="5">Yêu Cầu Thiếc</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="row col-sm-8">
                                        <div class="col-sm-6" style="padding: 0">
                                            <div class="form-group">
                                                <label class="control-label" for="quantity">Số lượng<span
                                                        class="required">*</span></label>
                                                <input type="number" step="1" min="0" max="100000" value="1"
                                                    class="form-control" id="quantity" name="quantity" placeholder="Số lượng" required
                                                    data-parsley-required-message="Trường số lượng là bắt buộc">
                                            </div>
                                            <div class="input-numeric-container">
                                                <table class="table-numeric">
                                                    <tbody>
                                                        <tr class="control-key">
                                                            <td><button type="button" class="key btn-primary" data-key="0">0</button></td>
                                                            <td><button type="button" class="key btn-primary" data-key="1">1</button></td>
                                                            <td><button type="button" class="key btn-primary" data-key="2">2</button></td>
                                                            <td><button type="button" class="key btn-primary" data-key="3">3</button></td>
                                                            <td><button type="button" class="key btn-primary" data-key="4">4</button></td>
                                                            <td><button type="button" class="key btn-primary" data-key="5">5</button></td>
                                                            <td><button type="button" class="key btn-primary" data-key="6">6</button></td>
                                                            <td><button type="button" class="key btn-primary" data-key="7">7</button></td>
                                                            <td><button type="button" class="key btn-primary" data-key="8">8</button></td>
                                                            <td><button type="button" class="key btn-primary" data-key="9">9</button></td>
                                                            <td><button type="button" class="key-del" disabled>Xóa</button></td>
                                                            <td><button type="button" class="key-clear" disabled>Xóa all</button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" style="padding: 0;height: 90px;">
                                            <div class="form-group" style="margin-left: 15px">
                                                <label class="control-label">Loại số lượng</label>
                                                <div>
                                                    <input name="usage_status" id="quantityUnused"
                                                    style="width:20px;height:20px; vertical-align: middle;" type="radio"
                                                    value="1" required checked
                                                    data-parsley-error-message="Vui lòng chọn một loại số lượng.">
                                                    <label for="quantityUnused">Hàng chẵn</label>

                                                    <input name="usage_status" id="quantityUsed"
                                                    style="width:20px;height:20px; vertical-align: middle;" type="radio"
                                                    value="0" required >
                                                    <label for="quantityUsed">Hàng lẻ</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label" for="size">Kích thước</label>
                                        <input type="number" class="form-control" placeholder="(nếu có)" id="size" name="size"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label" for="order">Hàng gấp</label>
                                        <div>
                                            <input name="order" id="order" style="transform: scale(2.5);margin-left: 18px;margin-top: 10px;" type="checkbox" value="1">
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label class="control-label" for="material_norms">Định mức</label>
                                        <input type="text" class="form-control" id="material_norms"
                                            name="material_norms" value="{{ old('material_norms') }}" readonly />
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label" for="unit">Đơn vị</label>
                                        <input type="text" class="form-control" id="unit" name="unit"
                                            value="{{ old('unit') }}" readonly />
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label class="control-label" for="ton_kho">Tồn kho</label>
                                        <input type="text" class="form-control" name="ton_kho" id="ton_kho"  readonly />
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label" for="ton_xuong">Tồn xưởng</label>
                                        <input type="text" class="form-control" name="ton_xuong"  id="ton_xuong"  readonly />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="content">Ghi chú</label>
                                    <input type="text" class="form-control" id="content" name="content"
                                        value="{{ old('content') }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5 class="card-header">Bộ phận Tiếp nhận</h5>
                                <div class="row form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label">Bộ phận</label>
                                        @php
                                            $to_dept = $formTypeJobs['to_dept']; // lấy ra các bộ phận tiếp nhận
                                            $confirm_to_dept = $formTypeJobs['confirm_to_dept']; // các chức vụ phê duyệt trong bộ phận tiếp nhận
                                            $confirm_by_to_dept = $formTypeJobs['confirm_by_to_dept']; // các chức vụ phê duyệt trong bộ phận tiếp nhận
                                        @endphp
                                        @foreach ($to_dept as $value)
                                            @php
                                                $department = Department::find($value);
                                            @endphp
                                            <input type="text" class="form-control" id="department" name="department" value="{{ $department->name }}" readonly>
                                            {{-- Duyệt tay --}}
                                            @if ($confirm_to_dept == 0)
                                            @foreach ($confirm_by_to_dept as $key1 => $value1)
                                                @php
                                                    $emp_depts = EmployeeDepartment::where('department_id',$value)->where('positions', $value1)->pluck('employee_id')->toArray();
                                                @endphp
                                                    <div class="control-label">Người duyệt {{$key1+1}}:{!! ArrayHelper::positionTitle()[$value1] !!}</div>
                                                    @foreach ($emp_depts as $key2 => $value2)
                                                        @php
                                                            $employee = Employee::find($value2);
                                                        @endphp
                                                        <div><strong>{{ $employee->first_name." ".$employee->last_name}}</strong></div>
                                                    @endforeach
                                            @endforeach
                                            @else
                                            {{-- Duyệt tự động --}}
                                            <div class="form-group">
                                                <div class="control-label">Người duyệt:</div>
                                                <div><strong>Auto Duyệt</strong></div>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row fixed-bottom">
                        <div class="col-md-6 form-actions mx-auto">
                            {{-- <button class="btn btn-success text-light form_save_print"> <i class="fa fa-check"></i>Tạo và In yêu cầu</button> --}}
                            @if ($employeeDepartment->department_id == 7)
                                <button class="btn btn-success text-light form_save_print"> <i class="fa fa-check"></i>Tạo và In yêu cầu</button>
                                <button class="btn btn-success text-light form_save"> <i class="fa fa-check"></i>Tạo yêu cầu</button>
                            @else
                                <button class="btn btn-success text-light form_save"> <i class="fa fa-check"></i>Tạo yêu cầu</button>
                                <button class="btn btn-primary text-light form_save_print"> <i class="fa fa-check"></i>Tạo và In yêu cầu</button>
                            @endif
                            <a href="{{ route('admin.requireds.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- modal --}}
    <div id="form_check_required" class="modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thông tin xác nhận yêu cầu</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                   <div>Mã linh kiện : <strong class="confirm_malinhkien"></strong></div>
                   <div>Đã được yêu cầu bởi:</div>
                   <div><strong class="confirm_user_by"></strong></div>
                   <div>Bộ phận: <strong class="confirm_deparment"></strong></div>
                   <div>Lúc: <strong class="confirm_updated_at"></strong></div>
                   <div>Số lượng:<strong class="confirm_quantity"></strong></div>
                   <div>Tình trạng: <strong>Chưa xuất</strong></div>
                   <div><strong>BẠN CÓ MUỐN TIẾP TỤC YÊU CẦU KHÔNG?</strong></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary form_confirm">Tiếp tục</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Thoát</button>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
     .key-del {
        background: #FF9800;
        /* border: 1px solid #ca7800; */
        color: #fff;
    }

    .key-clear {
        background: #E91E63;
        /* border: 1px solid #c70a4b; */
        color: #fff;
    }

    button[disabled] {
        opacity: 0.5;
        cursor: no-drop;
    }

    [data-numeric="hidden"] .table-numeric {
        display: none;
    }
</style>
@section('scripts')
    <script>
        var form_create='';
        var append_input='';
        var cursor='input[name="quantity"]';
        var parents = $(".input-numeric-container");
        var nonKeys = parents.find(".key-del, .key-clear");
        var socket;
        var socketId='';
        var room = document.getElementById('joinRoom').value;
        var username = document.getElementById('username').value;
        var device = document.getElementById('device').value;
        // socket = io("http://192.168.207.6:8091", {
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
        //     let check = 0;
        //     $('.info-person-device').each(function(i, obj) {
        //        let ip_client = $(this).find('.ip_client').text();
        //        if(info.ip_client == ip_client){
        //           check = 1;
        //        }
        //     });

        //     if(check == 0){
        //         // tạo notifytion

        //     }
        //     if(info.status == 'out'){
        //         $('div[data-ip_client="'+info.ip_client+'"]').remove();
        //     }

        // });
        function check_required(){
            $.ajax({
                url: "{{route('admin.requireds.checkRequired')}}",
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    code: $('input[name="code"]').val(),
                    searchcode: $('input[name="searchcode"]').val(),
                    department_id: $('#department_id').val(),
                },
                success: function(data) {
                    if(data.status == true){
                        console.log(data.message);
                        $('.confirm_malinhkien').text(data.message.code);
                        $('.confirm_user_by').text(data.message.employee.first_name +' '+data.message.employee.last_name);
                        $('.confirm_deparment').text(data.message.department.name);
                        $('.confirm_updated_at').text(data.message.created_at);
                        $('.confirm_quantity').text(data.message.quantity);
                        $('#form_check_required').modal('show')
                        $('.form_confirm').focus();
                    }else{
                        if(append_input){
                            $(form_create).append(append_input);
                        }
                        submit_order()
                    }
                }
            });
        }

        $('#order_type').change(function(e){
            e.preventDefault();
            if($(this).val() == 1){
                $(this).css({backgroundColor: 'cornflowerblue'});
            }else if($(this).val() == 2){
                $(this).css({backgroundColor: 'darkorange'});
            }else if($(this).val() == 3){
                $(this).css({backgroundColor: 'bisque'});
            }else if($(this).val() == 4){
                $(this).css({backgroundColor: 'cyan'});
            }else{
                $(this).css({backgroundColor: 'lightgreen'});
            }
        })
        $('.form_confirm').click(function(e){
            e.preventDefault();
             if(append_input){
                $(form_create).append(append_input);
             }
             submit_order()
        })
        function submit_order() {
            console.log('đã đóng');
            $('.form_confirm').prop("disabled", true);
            $('.form_save').prop("disabled", true);
            $('.form_save_print').prop("disabled", true);
            setTimeout(() => {
                $('.form_confirm').prop('disabled', false);
                $('.form_save').prop('disabled', false);
                $('.form_save_print').prop('disabled', false);
                console.log('đã mở');
            }, 2000)

            // Get all the forms elements and their values in one step
            var values = $(form_create).serialize()

            $.ajax({
                url: "{{ route('admin.requireds.store') }}",
                method: 'POST',
                data: values,
                success: function(res) {
                    $('#searchcode').val('');
                    $('#form_check_required').modal('hide');
                    $('input[name="code"]').val('')
                    $('.malinhkien').text('');
                    $('#ton_kho').val('');
                    $('#ton_xuong').val('');
                    $('#material_norms').val('');
                    $('#unit').val('');
                    $(form_create).append(append_input);
                    if(res.status == true){
                        $('#searchcode').focus();
                        console.log(res.data);
                            var _time = new Date();
                            var current_time = _time.toLocaleTimeString()+ ' '+ _time.getDate()+'-'+(_time.getMonth()+1)+'-'+_time.getFullYear();
                            // socket.emit('chat', {room:'orderproduct', sender: username, message: JSON.stringify({
                            //     room:room,
                            //     username:username,
                            //     ip_client:ip_client,
                            //     device:device,
                            //     current_time:current_time,
                            //     data:res.data,
                            //     status:'order'
                            // }) });
                        toastr.success(res.message)
                        // window.location.reload();
                    }else{
                        toastr.error(res.message)
                    }
                }
            })
        }
        $('.form_save').click(function(e){
            e.preventDefault();
            form_create='#post_form';
            append_input='<input type="hidden" name="print" value="false" />';
            check_required();
        })
        $('.form_save_print').click(function(e){
            e.preventDefault();
            form_create='#post_form';
            append_input='<input type="hidden" name="print" value="true" />';
            check_required();
        })
         $('#searchcode').codeScanner({
            maxEntryTime: 100, // milliseconds
            minEntryChars: 1, // characters
            loading:false,
            nextElement:'input[name="quantity"]',
            onScan: function ($element, code) {
            }
        });
        $(document).ready(function(){
            $('#order_type').css({backgroundColor: 'lightgreen'});
            $('#searchcode').keyup(delay(function (e) {
                filterAccessorys();
            }, 300));
            $('#searchcode').keypress(function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('[role="listbox"]').remove();
                    getAccessorys()
                }
            })
            $('input[name="quantity"]').keypress(function(e){
                if (e.keyCode == 13){
                    $('input[name="size"]').focus();
                    return false;
                }
            })
            $('#searchcode').focus();
            if( $('input[name="quantity"]').val() == ""){
                nonKeys.prop( "disabled", true );
            } else {
                nonKeys.prop( "disabled", false );
            }
            $(document).on( "change", cursor, function(){
                var parents = $( ".input-numeric-container" );
                var input = $(cursor);
                var nonKeys = parents.find( ".key-del, .key-clear");
                var inputValue = input.val();
                if( $('input[name="quantity"]').val() == ""){
                    nonKeys.prop( "disabled", true );
                } else {
                    nonKeys.prop( "disabled", false );
                }
            });
            $(document).on( "click focus",cursor, function(){
                var parents = $( this ).parents( ".input-numeric-container" );
                var data = parents.attr( "data-numeric" );
                if( data ){
                    if( data == "hidden" ){
                    parents.find( ".table-numeric" ).show();
                    }
                }
            });
            //key numeric
            $(document).on( "click", ".key", function(){
                var number = $( this ).attr( "data-key" );
                var input = $(cursor);
                var inputValue = input.val();
                input.val( inputValue + number ).change();
            });
            //delete
            $('.control-key').on('click','.key-del',function(){
                var input = $(cursor);
                var inputValue = input.val();
                console.log(inputValue);
                input.val( inputValue.slice(0, -1) ).change();
            })
            //clear
            $('.control-key').on( "click", ".key-clear", function(){
                var input = $(cursor);
                console.log(input.val());
                input.val( "" ).change();
            });
        });
      function optionChange(event){
            $('#searchcode').val($(event).text());
            $('[role="listbox"]').remove();
            getAccessorys();
      }
      function filterAccessorys(){
        let selectedValue = $('#searchcode').val();
        var scope = $('#searchcode').parents('.suggestions-container');
        $('.suggestions',scope).html('<div role="listbox"></div>');
        $('[role="listbox"]', scope).html('');
        if(selectedValue){
            $.ajax({
                url: "{{ route('admin.requireds.ajaxSuggestions') }}",
                type: "POST",
                dataType: "json",
                data: {
                    selectedValue: selectedValue
                },
                success: function(res) {
                    if (res.data) {
                        $('[role="listbox"]', scope).html('');
                        $.each(res.data, function(k, v) {
                            $('[role="listbox"]', scope).append(
                            '<div role="option" onClick="optionChange(this)" >'+v.code+'</div>'
                            );
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $('[role="listbox"]', scope).html('');
                }
            });
        }
      }
      function getAccessorys(){
        var selectedValue = $('#searchcode').val();
        $.ajax({
            url: "{{ route('admin.requireds.showDataAccessorys') }}",
            type: "POST",
            dataType: "json",
            data: {
                selectedValue: selectedValue
            },
            success: function(data) {
                $('#searchcode').val('');
                $('#code').val(selectedValue);
                $('.malinhkien').text(selectedValue);
                $('#material_norms').val(formatCurrencyV2(data.material_norms.toString()));
                $('#unit').val(data.unit);
                $('#quantity').focus();
                if(data.accessory_dept){
                    let ton_kho = data.accessory_dept.find(x => x.location_c == '0111');
                    let ton_xuong = data.accessory_dept.find(x => x.location_c == "{{@$employeeDepartment->department->code}}");
                    $('#ton_kho').val(formatCurrencyV2(parseInt(ton_kho?.inventory).toString()));
                    $('#ton_xuong').val(formatCurrencyV2(parseInt(ton_xuong?.inventory).toString()));
                }
            },
            error: function(xhr, status, error) {
                $('#code').val('');
                $('.malinhkien').text('Không tìm thấy mã linh kiện.');
                $('#material_norms').val('');
                $('#ton_kho').val('');
                $('#ton_xuong').val('');
                toastr.success("Có lỗi xảy ra", 'Error', {
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut",
                    timeOut: 2000
                });
            }
        });
      }
      document.body.addEventListener("click", function(){
        let count = $(".suggestions > div").children().length;
        if(count > 0){
            $('[role="listbox"]').remove();
        }
      });
    </script>
@endsection
