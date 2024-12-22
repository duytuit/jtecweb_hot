@extends('backend.layouts.master')
@php
    use App\Models\Employee;
@endphp
@section('admin-content')
    @include('backend.pages.checkCutMachine.partials.header-breadcrumbs')
    <div class="container-fluid ">
        <form id="form-search" action="{{ route('admin.checkCutMachine.index') }}" method="get">
            @csrf
            <div class="row">
                <div class="col-sm-1">
                    <span class="btn-group">
                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Tác vụ <span
                                class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a class="btn-action" data-target="#form_lists" data-method="delete" href="javascript:;"><i
                                        class="fa fa-trash" style="color: #cb3030;"></i> Xóa</a></li>
                            <li><a class="btn-action" data-target="#form_lists" data-method="active_check"
                                    href="javascript:;"><i class="fa fa-check-circle" style="color: #3800df;"></i> Duyệt</a>
                            </li>
                            <li><a class="btn-action" data-target="#form_lists" data-method="inactive_check"
                                    href="javascript:;"><i class="fa fa-check-circle" style="color: #3800df;"></i> Bỏ
                                    duyệt</a></li>
                        </ul>
                    </span>
                </div>
                <div class="col-sm-11">
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Nhập từ khóa" class="form-control" />
                        </div>
                        <div class="col-sm-2">
                            <select name="employee_name" id="employee_name" class="form-control" style="width:100%">
                                <option value="">Người thực hiện</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"> <i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control date_picker" name="search_date" id="search_date"
                                value="{{ @$filter['search_date'] }}" placeholder="Ngày kiểm tra" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control custom-select select2" name="machine_name" style="width:100%">
                                <option value="">Chọn máy</option>
                                @foreach ($machineLists as $machineList)
                                    <option value="{{ $machineList['name'] }}">
                                        {{ $machineList['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-warning btn-block">Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- END #form-search -->
        <form id="form_lists" action="{{ route('admin.checkCutMachine.action') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="" />
            <div class="table-responsive product-table overflow-x-scroll ">
                <table class="table table-bordered" id="checkCutMachine_table" style="min-width: 1280px; ">
                    <thead>
                        <tr>
                            <th width="3%"><input type="checkbox" class="greyCheck checkAll"
                                    data-target=".checkSingle" /></th>
                            <th>TT</th>
                            <th>Mã check list</th>
                            <th>Người thực hiện</th>
                            <th>Bộ phận</th>
                            <th>Máy</th>
                            <th>Tình trạng Duyệt</th>
                            <th>Trạng thái</th>
                            <th>Lý do - Sửa chữa</th>
                            <th>Ngày-giờ thực hiện</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $index => $item)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                        class="greyCheck checkSingle" /></td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->code_required }}</td>
                                <td>{{ @$item->employee->first_name . ' ' . @$item->employee->last_name }}</td>
                                <td>{{ @$item->employeeDepartment->department->name }}</td>
                                @php
                                    $contentForm = json_decode($item->content_form);
                                @endphp
                                <td>{{ $contentForm ? $contentForm->name_machine : null }}</td>
                                <td class="p-1">
                                    @if ($item->signatureSubmission)
                                        @foreach ($item->signatureSubmission as $index2 => $item2)
                                            @if ($item2->positions == 4)
                                                <div>SubLeader:
                                                    @if ($item2->status == 0)
                                                        <span> chưa duyệt </span>
                                                        <span class="btn btn-outline-danger"
                                                            style="padding: 0.15rem 0.5rem;">
                                                            <i class="fa fa-times" style="color: red;"></i>
                                                        </span>
                                                    @else
                                                        <button type="button" class="border-0 bg-transparent "
                                                            data-toggle="tooltip" data-html="true"
                                                            data-placement="bottom"
                                                            title="{{ 'SubLeader: ' . @$item2->employee->first_name . @$item2->employee->last_name }} <br>
                                                    {{ 'Duyệt lúc: ' . $item2->updated_at }} ">
                                                            <span> Đã duyệt </span>
                                                            <span style="padding: 0.15rem 0.5rem;"
                                                                class="btn btn-outline-success"><i class="fa fa-check"
                                                                    style="color: green;"></i></span>
                                                        </button>
                                                    @endif
                                                </div>
                                            @else
                                                <div>
                                                    Leader:
                                                    @if ($item2->status == 0)
                                                        <span>chưa duyệt</span>
                                                        <span class="btn btn-outline-danger"
                                                            style="padding: 0.15rem 0.5rem;">
                                                            <i class="fa fa-times" style="color: red;"></i>
                                                        </span>
                                                    @else
                                                        <button type="button" class="border-0 bg-transparent "
                                                            data-toggle="tooltip" data-html="true"
                                                            data-placement="bottom"
                                                            title="{{ 'Leader: ' . @$item2->employee->first_name . @$item2->employee->last_name }} <br>
                                                            {{ 'Duyệt lúc: ' . $item2->updated_at }} ">
                                                            <span> Đã duyệt </span>
                                                            <span style="padding: 0.15rem 0.5rem;"
                                                                class="btn btn-outline-success"><i class="fa fa-check"
                                                                    style="color: green;"></i></span>
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="dropdown show">
                                        <div class="dropdown-menu z-n1 " aria-labelledby="dropdownMenuLink">
                                            @php
                                                $contentFormChecks = $contentForm->check_list;
                                                $check=1; // ok
                                            @endphp

                                            @foreach ($contentFormChecks as $index => $item1)
                                                <div class="dropdown-item" href="#">
                                                    {{ $item1->id + 1 }}.{{ $item1->position }}
                                                    @if ($item1->answer)
                                                        <span class="badge badge-success font-weight-100">OK</span>
                                                    @else
                                                        @php
                                                            $check=0;
                                                        @endphp
                                                        <span class="badge badge-warning">NG</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            @if ($check==1)
                                                <span class="badge badge-success font-weight-100">OK</span>
                                            @else
                                                <span class="badge badge-warning">NG</span>
                                            @endif
                                        </a>
                                    </div>
                                </td>
                                <td>{{ $item->content }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>
                                    {{-- nếu chưa duyệt có thể xóa --}}
                                @php
                                    $signatureSubmission =  @$item->signatureSubmission->where('required_id', $item->id)->where('status', 0)->all();
                                @endphp
                                @if (count($signatureSubmission) >0)
                                    <a title="Xóa" class=" d-inline-block btn-danger btn-sm text-white"
                                        href="{{ route('admin.checkCutMachine.trashed.destroy', ['id' => $item->id]) }}">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
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
    get_data_select_name({
            object: '#employee_name',
            url: '{{ url('admin/employees/ajaxGetSelectByName') }}',
            data_id: 'id',
            data_code: 'code',
            data_first_name: 'first_name',
            data_last_name: 'last_name',
            title_default: 'Người thực hiện',

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
