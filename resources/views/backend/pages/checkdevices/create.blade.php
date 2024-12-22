@extends('backend.layouts.master')

@section('title')
@include('backend.pages.checkdevices.partials.title')
@endsection
<style type="text/css">
</style>
@section('admin-content')
@include('backend.pages.checkdevices.partials.header-breadcrumbs')
<div class="container-fluid">
    @include('backend.layouts.partials.messages')
    <div class="col-sm-12">
        <form id="input_data" action="{{ route('admin.checkdevices.store') }}" method="POST"
            enctype="multipart/form-data" data-parsley-validate data-parsley-focus="first">
            @csrf
            <input type="hidden" id="requiredItem" name="requiredItem">
            <div class="row">
                <div class="col-6 align-items-center justify-content-center mx-auto">
                    <div class="row">
                        <label class="col-sm-4 control-label" for="name">Người thao tác</label>
                        <div class="col-sm-8 " style="line-height: 34px;">
                            <strong>{{@$user->first_name.' '.@$user->last_name}}</strong>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-4 control-label" for="name">Bộ phận</label>
                        <div class="col-sm-8 " style="line-height: 34px;">
                            <strong>{{@$employeeDepartment->department->name}}</strong>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label" for="name">Máy thao tác</label>
                        <div class="col-sm-8 select_device">
                            <select name="device[]" multiple class="form-control select2" style="width: 100%;" required>
                                <option value="">Chọn máy thao tác</option>
                                @foreach ($devicesList as $item)
                                    <option value="{{$item->name}}" {{ @$filter['device']==$item->name ? 'selected' : ''
                                        }}>{{$item->name.'-'.$item->model.'-'.$item->color}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label" for="name">Vị trí máy</label>
                        <div class="col-sm-8">
                            <select name="position" class="form-control" style="width: 100%;" required
                                data-parsley-required-message="Chưa chọn vị trí">
                                <option value="">Vị trí máy</option>
                                @foreach ($PositionByDevices as $item)
                                <option value="{{$item}}" {{ @$filter['position']==$item ? 'selected' : '' }}>{{'Bàn
                                    '.$item}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- <div class="row form-group">
                        <label class="col-sm-4 control-label" for="manager_by">Người quản lý</label>
                        <div class="col-sm-8 select_manager">
                            <select name="manager_by" id="manager_by" class="form-control" style="width:100%">
                                <option value="">Người quản lý</option>
                            </select>
                        </div>
                    </div> --}}
                    <div class="row form-group">
                        <label class="col-sm-4 control-label" for="description">Đánh giá ngoại quan</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description"
                                value="{{ old('description') }}" placeholder="ghi chú">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-actions mx-auto">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i>Cập nhật</button>
                            <a href="#" class="btn btn-dark" onclick="clear_item()">Hủy</a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
    <form id="form_lists" action="{{ route('admin.checkdevices.action') }}" method="post">
        @csrf
        <input type="hidden" name="method" value="" />
        <input type="hidden" name="status" value="" />
        <div class="table-responsive product-table">
            <table class="table table-bordered" id="exams_table">
                <thead class="thead-primary">
                    <tr>
                        <th>TT</th>
                        <th>Thiết bị</th>
                        <th>Loại</th>
                        <th>Màu</th>
                        <th>Vị trí</th>
                        <th>Mã nhân viên</th>
                        <th>Nhân viên</th>
                        <th>Thời gian</th>
                        <th>Ghi chú</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lists as $index=> $item)
                    @php
                        $device = json_decode($item->content_form);
                        $employee =@$item->employee;
                    @endphp
                    <td>{{ ($index+1) }}</td>
                    <td>{{ @$device->name }}</td>
                    <td>{{ @$device->model }}</td>
                    <td>{{ @$device->color }}</td>
                    <td>{{ "Bàn " . @$device->position }}</td>
                    <td>{{ @$employee->code }}</td>
                    <td>{{ @$employee->first_name.' '.@$employee->last_name }}</td>
                    <td>{{ @$item->created_at }}</td>
                    <td>{{ @$item->content }}</td>
                    <td>
                        <a title="Lịch sử thao tác" target="_blank" class="d-inline-block btn-info btn-sm text-white"
                            href="{{ route('admin.activitys.index', ['modelId' => $item->id,'content_type' =>get_class($item)]) }}"><i
                                class="fa fa-history"></i> </a>
                        <a title="Sửa" class="d-inline-block mx-1 btn-purple btn-sm text-white"
                            onclick="item_edit({{$item}},{{@$employee}})" href="javascript:;"><i class="fa fa-edit"></i> </a>
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
                        <option value="{{ $num }}" {{ $num==$per_page ? 'selected' : '' }}>{{ $num }}</option>
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
        function item_edit(item,employee){
            console.log(employee);
            let content_form= JSON.parse(item.content_form);
            $('#requiredItem').val(JSON.stringify(item));
            $('select[name="device[]"]').val(content_form.name).change();
            $('select[name="position"]').val(content_form.position).change();
            $('select[name="device[]"]').removeAttr('multiple');
            // $('select[name="manager_by"]').val(content_form.manager_by).change();
           // $('.select_manager .select2-selection__rendered').text(employee.code+'-'+employee.first_name+' '+employee.last_name);
            $('#description').val(item.content);

        }
        function clear_item(){
            $('#input_data').trigger("reset");
            $('#requiredItem').val('');
            $('.select_device .select2-selection__rendered').text('Chọn máy thao tác');
            $('select[name="device[]"]').attr('multiple','multiple');
           // $('.select_manager .select2-selection__rendered').text('Người quản lý');
        }
        $(document).ready( function () {
            // Kiểm tra sự hỗ trợ của API WebRTC
            if (navigator.connection) {
                // Lấy thông tin về wifi
                navigator.connection.addEventListener('change', function() {
                    var wifiInfo = navigator.connection;
                    console.log('Wifi info:', wifiInfo);
                });
            } else {
                console.log('WebRTC API không được hỗ trợ trên thiết bị này.');
            }
        })
        get_data_select_name({
            object: '#manager_by',
            url: '{{ url('admin/employees/ajaxGetSelectByName') }}',
            data_id: 'id',
            data_code: 'code',
            data_first_name: 'first_name',
            data_last_name: 'last_name',
            title_default: 'Người quản lý',

        });

        function get_data_select_name(options) {
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
                                text: item[options.data_code] + '-' + item[options.data_first_name] + ' ' + item[options.data_last_name]
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
</script>
@endsection
