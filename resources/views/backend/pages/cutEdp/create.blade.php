@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.cutEdp.partials.title')
@endsection
@php
    use App\Models\EmployeeDepartment;
    use App\Models\Employee;
    use App\Models\Department;
    use App\Helpers\ArrayHelper;
@endphp

@section('admin-content')
    @include('backend.pages.cutEdp.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.layouts.partials.messages')
        <div class="create-page">
            <form id="post_form" action="{{ route('admin.cutedps.store') }}" method="POST" enctype="multipart/form-data"
            data-parsley-validate data-parsley-focus="first">
            @csrf
            <div class="form-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-header">Bộ phận yêu cầu</h5>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label class="control-label" for="department_id">Bộ phận</label>
                                    <select class="form-control" name="department_id" id="department_id" style="width:100%" required>
                                        @foreach ($departments as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label" for="created_by">Người yêu cầu</label>
                                    <input type="text" class="form-control" id="created_by" name="created_by"
                                        value="{{ $employee->first_name . ' ' . $employee->last_name }}" readonly />
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-4">
                                    <label class="control-label" for="hincd">Mã sản phẩm</label>
                                    <input type="text" class="form-control" name="hincd" id="hincd" placeholder="Mã sản phẩm"/>
                                </div>
                                <div class="col-sm-3">
                                    <label class="control-label" for="senban">Số dây</label>
                                    <input type="text" class="form-control" name="senban" id="senban" placeholder="Số dây"/>
                                </div>
                                <div class="col-sm-3">
                                    <label class="control-label" for="lot_no">Mã Lot</label>
                                    <div class="suggestions-container">
                                        <input type="text" class="form-control" name="lot_no" id="lot_no" placeholder="Mã Lot"/>
                                        <div class="suggestions"></div>
                                     </div>

                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Thao tác</label>
                                    <button class="btn btn-warning btn-block view_edp">Xem</button>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label class="control-label" for="quantity">Số lượng</label>
                                    <input type="number" class="form-control" name="quantity" id="quantity" min="1" value="1" placeholder="số lượng"/>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label" for="content">Ghi chú</label>
                                    <input type="text" class="form-control" name="content" id="content" placeholder="ghi chú"/>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <table class="table-bordered" width="100%">
                                            <tr>
                                                <th colspan="4">Thông tin</th>
                                            </tr>
                                            <tr>
                                                <td colspan="2" >
                                                    <div class="left">
                                                        <div>
                                                            <div>Chủng loại:</div>
                                                            <div>Kích thước:</div>
                                                            <div>Số dây:</div>
                                                        </div>
                                                        <div class="content_text">
                                                            <div class="chung_loai"></div>
                                                            <div class="kich_thuoc"></div>
                                                            <div class="so_day"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td colspan="2" >
                                                    <div class="right">
                                                        <div>
                                                            <div>Tên mối nối:</div>
                                                            <div>Kích thước sau xoắn:</div>
                                                            <div>Maku dây:</div>
                                                        </div>
                                                        <div class="content_text">
                                                            <div class="ten_moi_noi"></div>
                                                            <div class="kich_thuoc_sau_xoan"></div>
                                                            <div class="maku_day"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="center">Đầu A</td>
                                                <td colspan="2" align="center">Đầu B</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" width="35%">
                                                    <div style="display: flex">
                                                        <div>
                                                            <div>Tanshi:</div>
                                                            <div>Sỏ gôm:</div>
                                                            <div>Chuốt:</div>
                                                            <div>Ghi chú:</div>
                                                        </div>
                                                        <div class="content_text">
                                                            <div class="tanshi_a"></div>
                                                            <div class="sogom_a"></div>
                                                            <div class="chuot_a"></div>
                                                            <div class="ghi_chu_a"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td colspan="2" width="35%">
                                                    <div style="display: flex">
                                                        <div>
                                                            <div>Tanshi:</div>
                                                            <div>Sỏ gôm:</div>
                                                            <div>Chuốt:</div>
                                                            <div>Ghi chú:</div>
                                                        </div>
                                                        <div class="content_text">
                                                            <div class="tanshi_b"></div>
                                                            <div class="sogom_b"></div>
                                                            <div class="chuot_b"></div>
                                                            <div class="ghi_chu_b"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                    </table>
                                </div>
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
                        <button class="btn btn-success text-light form_save_print"> <i class="fa fa-check"></i>Tạo yêu cầu</button>
                        <button class="btn btn-success text-light form_save_print_hot"> <i class="fa fa-check"></i>Tạo và In yêu cầu</button>
                        <a href="{{ route('admin.cutedps.index') }}" class="btn btn-dark">Quay lại</a>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
@endsection
<style>
    .left,.right {
        width: 50%;
        display: flex;
    }
    .table-bordered td,.table-bordered th {
        padding-left: 5px;
    }
    [role="listbox"] {
        left: 0 !important;
    }
</style>
@section('scripts')
    <script>
        $('.form_save_print').click(function(e){
            e.preventDefault();
            var values = $('#post_form').serialize()
            $.ajax({
                url: "{{ route('admin.cutedps.store') }}",
                method: 'POST',
                data: values,
                success: function(res) {
                    if(res.status == true){
                        toastr.success(res.message)
                        window.location.href = "{{ route('admin.cutedps.index') }}";
                    }else{
                        toastr.error(res.message)
                    }
                }
            })
        })
        $('.form_save_print_hot').click(function(e){
            e.preventDefault();
            $('#post_form').append('<input type="hidden" name="print" value="true" />');
            var values = $('#post_form').serialize()
            $.ajax({
                url: "{{ route('admin.cutedps.store') }}",
                method: 'POST',
                data: values,
                success: function(res) {
                    if(res.status == true){
                        toastr.success(res.message)
                        window.location.href = "{{ route('admin.cutedps.index') }}";
                    }else{
                        toastr.error(res.message)
                    }
                }
            })
        })
        $(document).ready(function () {
            $('#hincd,#senban').on('keypress',function(e){
                clearText()
            }).on('paste', function(e){
                clearText()
            });
            $('#lot_no').keyup(delay(function (e) {
                filterMSP();
            }, 300));
        });
        function filterMSP(){
            let selectedValue = $('#lot_no').val();
            var scope = $('#lot_no').parents('.suggestions-container');
            $('.suggestions',scope).html('<div role="listbox"></div>');
            $('[role="listbox"]', scope).html('');
            if(selectedValue){
                $.ajax({
                    url: "{{ route('admin.cutedps.ajaxGetSelectByLotNo') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        search: selectedValue
                    },
                    success: function(res) {
                        if (res.data) {
                            $('[role="listbox"]', scope).html('');
                            $.each(res.data, function(k, v) {
                                $('[role="listbox"]', scope).append(
                                '<div role="option" onClick="optionChange(this)" >'+v.発注seq+' | '+v.品目c+'</div>'
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
        function optionChange(event){
            let text = $(event).text();
            let new_text = text.split("|");
            console.log(new_text.length);
            if(new_text.length > 0){
                let msp = new_text[1];
                let lot_no = new_text[0];
                $('#lot_no').val(lot_no);
                $('#hincd').val(msp);
            }else{
                $('#lot_no').val(text);
            }
            $('[role="listbox"]').remove();
        }
        function clearText(){
            $('.chung_loai').text('');
            $('.kich_thuoc').text('');
            $('.so_day').text('');
            $('.ten_moi_noi').text('');
            $('.kich_thuoc_sau_xoan').text('');
            $('.tanshi_a').text('');
            $('.sogom_a').text('');
            $('.chuot_a').text('');
            $('.ghi_chu_a').text('');
            $('.tanshi_b').text('');
            $('.sogom_b').text('');
            $('.chuot_b').text('');
            $('.ghi_chu_b').text('');
            $('.maku_day').text('');
        }
       $('.view_edp').click(function(e){
            e.preventDefault();
            getEdp();
       })
        function getEdp(){
            $.ajax({
                url: "{{ route('admin.cutedps.ajaxGetSelectByHINCD') }}",
                method: 'GET',
                data: {
                    _token:$('meta[name="csrf-token"]').attr('content'),
                    hincd:$('#hincd').val(),
                    senban:$('#senban').val()
                },
                success: function(res) {
                    if(res.hincd){
                        $('.chung_loai').text(res.sensyu.substring(1));
                        $('.kich_thuoc').text(res.sentyo);
                        $('.so_day').text(res.senban);
                        $('.ten_moi_noi').text(res.jointgb != " " ? res.jointgb:'---');
                        $('.kich_thuoc_sau_xoan').text(res.twist2 != " " ? res.twist2 : '---');
                        $('.tanshi_a').text(res.tascda.substring(1));
                        $('.sogom_a').text(res.gumcda.substring(1));
                        $('.chuot_a').text(res.kawaa);
                        $('.ghi_chu_a').text((res.infomeia1 != " " ? res.infomeia1 : '-') + (res.infomeia2 != " " ? res.infomeia2 : '-'));
                        $('.tanshi_b').text(res.tascdb.substring(1));
                        $('.sogom_b').text(res.gumcdb.substring(1));
                        $('.chuot_b').text(res.kawab);
                        $('.ghi_chu_b').text((res.infomeib1 != " " ? res.infomeib1 : '-') + (res.infomeib2 != " " ? res.infomeib2 : '-'));
                        $('.maku_day').text((res.markno != " " ? res.markno : ''));
                        toastr.success("Lấy dữ liệu thành công.")
                    }else{
                        toastr.error(res.message)
                    }
                }
            })
        }
    </script>
@endsection
