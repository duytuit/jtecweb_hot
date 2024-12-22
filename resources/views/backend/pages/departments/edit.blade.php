@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.departments.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.departments.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.layouts.partials.messages')
        <div class="create-page">
          <form action="{{ route('admin.departments.update', ['id' => $department->id]) }}" method="POST"
                enctype="multipart/form-data" data-parsley-validate data-parsley-focus="first">
                @csrf
                <input type="hidden" name="departmentId" id="departmentId" value="{{ $department->id }}">
                <div class="form-body">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="name">Tên bộ phận <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $department->name }}"
                                        placeholder="" required="" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="code">Mã bộ phận <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="code" name="code" value="{{ $department->code }}"
                                        placeholder="" required="" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="">Nhóm Quyền</label><br>
                                    @php
                                        $permissions = json_decode($department->permissions);
                                    @endphp
                                    <select class="roles_select form-control custom-select" id="roles"
                                        name="roles[]" multiple style="width: 100%;">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}" {{ ($permissions && in_array($role->name,$permissions)) ? 'selected' : null}}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Trạng thái</label>
                                    <input type="checkbox" id="_status" data-id="" data-url="" name="status"
                                        value="{{ $department->status }}" checked class="d-none" />
                                    <label for="_status" class="toggle">
                                        <div class="slider"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-actions ">
                                    <div class="card-body d-flex justify-content-between">
                                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i>
                                            Lưu</button>
                                        <a href="{{ route('admin.departments.index') }}" class="btn btn-dark">Quay lại</a>
                                    </div>
                                </div>
                            </div>
                        </div>
            </form>
            <form action="{{ route('admin.departments.edit', ['id' => $department->id]) }}" method="Get">
                @csrf
                        <div class="mb-3 row">
                            <div class="col-md-9">
                                <select name="ids[]" multiple id="codecode" class="form-control" style="width:100%">
                                    <option value="">Chọn mã nhân viên</option>
                                </select>
                            </div>
                            <div class="col-md-3 flex-fill" style="display: flex">
                                <div class="col-md-6">  <button type="submit" class="btn btn-warning w-100 ">Lọc</button></div>
                                <div class="col-md-6">  <button class="btn btn-primary w-100 add_btn_js">Thêm</button></div>
                            </div>
                        </div>
                        <div class="row w-100 mx-auto ">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>TT</th>
                                        <th>Code</th>
                                        <th>Tên nhân viên</th>
                                        <th>Chức vụ</th>
                                        <th>Quyền hạn</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>


                                @foreach ($employeeDepartments as $index => $employeeDepartment)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{ @$employeeDepartment->employee->code }}</td>
                                    <td>{{ @$employeeDepartment->employee->first_name . ' ' . @$employeeDepartment->employee->last_name }}
                                    </td>
                                    <td>
                                        <select class="form-control custom-select" id="positionTitle"
                                            onchange="changePosition(this,{{ $employeeDepartment->id }})">
                                            @foreach ($positionTitles as $key => $item)
                                            <option value="{{  $key }}" {{ $key===$employeeDepartment->positions ? 'selected' : '' }}>
                                                {{ $item }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        @php
                                            $_permissions = json_decode(@$employeeDepartment->permissions);
                                        @endphp
                                        <select class="permissions_select form-control custom-select"
                                            name="permissions[]" multiple style="width: 100%;"  onchange="changePermissions(this,{{ $employeeDepartment->id }})">
                                            @foreach ($positionTitles as $key => $permission)
                                                <option value="{{$key }}" {{($_permissions && in_array($key,$_permissions)) ? 'selected' : '' }}>{{ $permission }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <a title="Lịch sử thao tác" target="_blank" class="d-inline-block btn-info btn-sm text-white"
                                        href="{{ route('admin.activitys.index', ['modelId' => $employeeDepartment->id,'content_type' =>get_class($employeeDepartment)]) }}"><i
                                            class="fa fa-history"></i> </a>
                                        <a title="Xóa" class=" d-inline-block btn-danger btn-sm text-white delete_js" href=""
                                            onclick="deletefromED(this,{{ $employeeDepartment->id }})"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        {{-- <div class="row">
                            <div class="col-sm-3">
                                <span class="record-total">Tổng: {{ $employeeDepartments->total() }} bản ghi</span>
                            </div>
                            <div class="col-sm-6 text-center">
                                <div class="pagination-panel">
                                    {{ $employeeDepartments->appends(Request::all())->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
                                </div>
                            </div>
                            <div class="col-sm-3 text-right">
                                <span>
                                    Hiển thị
                                    <select name="per_page" class="form-control" style="display: inline;width: auto;" data-target="#form_lists">
                                        @php $list = [5, 10, 20, 50, 100, 200]; @endphp
                                        @foreach ($list as $num)
                                        <option value="{{ $num }}" {{ $num==$per_page ? 'selected' : '' }}>
                                            {{ $num }}</option>
                                        @endforeach
                                    </select>
                                </span>
                            </div>
                        </div> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
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
        $('.add_btn_js').click(function(e) {
            e.preventDefault();
            values = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                ids: JSON.stringify($('#codecode').val()),
                departmentId: $('#departmentId').val()
            }
            $.ajax({
                url: "{{ route('admin.departments.addEmployeeIntoDepartment') }}",
                method: 'POST',
                data: values,
                success: function(data) {
                    toastr.success("Thành công", 'Success');
                    window.location.reload();
                },
                errors: function(data) {}
            })
        })

        function deletefromED(event, employeeDepartmentId) {
            // e.preventDefault();
            values = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: employeeDepartmentId,
            }
            $.ajax({
                url: "{{ route('admin.departments.destroyEmployeeDepartments') }}",
                method: 'POST',
                data: values,
                success: function(data) {
                    toastr.success("Thành công", 'Success', {
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut",
                        timeOut: 2000
                    });
                    window.location.reload();
                },
                errors: function(data) {

                }
            });
        };

        function changePosition(event, employeeDepartmentId) {
            // event.preventDefault();
            values = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                positionTitle: $(event).val(),
                employeeDepartmentId: employeeDepartmentId,
            }
            $.ajax({
                url: "{{ route('admin.departments.changePositionTitle') }}",
                method: 'POST',
                data: values,
                success: function(data) {
                    toastr.success("Thành công", 'Success', {
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut",
                        timeOut: 2000
                    });
                    window.location.reload();
                },
                error: function(data) {
                    console.error('Lỗi khi lưu giá trị:', data);
                }
            });
        };
        function changePermissions(event, employeeDepartmentId) {
            // event.preventDefault();
            values = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                permissions: $(event).val(),
                employeeDepartmentId: employeeDepartmentId,
            }
            $.ajax({
                url: "{{ route('admin.departments.changePermissions') }}",
                method: 'POST',
                data: values,
                success: function(data) {
                    toastr.success("Thành công", 'Success', {
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut",
                        timeOut: 2000
                    });
                    window.location.reload();
                },
                error: function(data) {
                    console.error('Lỗi khi lưu giá trị:', data);
                }
            });
        };
        $(".roles_select").select2({
            placeholder: "Nhóm quyền"
        });
        $(".permissions_select").select2({
            placeholder: "Quyền hạn"
        });
    </script>
@endsection
