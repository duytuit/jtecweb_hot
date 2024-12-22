@extends('backend.layouts.master')
@php
    use App\Helpers\ArrayHelper;
@endphp
@section('title')
    @include('backend.pages.checkdevices.partials.title')
@endsection
<style type="text/css">
    .checkdevices-table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
    }

    .checkdevices-table td {
        border-style: solid;
        border-width: 1px;
        font-family: Arial, sans-serif;
        font-size: 12px;
        overflow: hidden;
        padding: 10px 5px;
        word-break: normal;
    }

    .checkdevices-table th {
        border-style: solid;
        border-width: 0px;
        font-family: Arial, sans-serif;
        font-size: 12px;
        font-weight: normal;
        overflow: hidden;
        padding: 10px 5px;
        word-break: normal;
        text-align: center;
    }

    .checkdevices-table .tg-0lax {
        vertical-align: top
    }

    .checkdevices-table .tg-73oq {
        border-color: #000000;
        text-align: left;
        vertical-align: top;
        border-width: 0px;
        width: 20px;
    }

    .checkdevices-table .tg-73oq-text {
        width: fit-content;
        vertical-align: middle;
        text-wrap: nowrap;
    }

    .checkdevices-table .tg-0pky {
        border-color: inherit;
        text-align: center;
        vertical-align: top
    }

    .checkdevices-table .tg-0pky img {
        width: 40px;
        text-align: center;
    }
    .info-person-device div{
        line-height: 1;
        font-size: 12px;
    }
</style>
@section('admin-content')
    @include('backend.pages.checkdevices.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.pages.checkdevices.partials.top-show')
        @include('backend.layouts.partials.messages')
        <form id="form-search" action="{{ route('admin.checkdevices.index_list') }}" method="get">
            <div class="row form-group">
                <div class="col-sm-1">
                    <span class="btn-group">
                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Tác vụ <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a class="btn-action" data-target="#form_lists" data-method="delete" href="javascript:;"><i class="fa fa-trash" style="color: #cb3030;"></i> Xóa</a></li>
                        </ul>
                    </span>
                </div>
                <div class="col-sm-2">
                    <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Nhập từ khóa" class="form-control" />
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
                    <button class="btn btn-warning btn-block">Tìm kiếm</button>
                </div>
            </div>
        </form><!-- END #form-search -->
            <form id="form_lists" action="{{ route('admin.checkdevices.action') }}" method="post">
                @csrf
                <input type="hidden" name="method" value="" />
                <input type="hidden" name="status" value="" />
                <div class="table-responsive product-table">
                    <table class="table table-bordered" id="exams_table">
                        <thead class="thead-primary">
                            <tr>
                                <th width="3%"><input type="checkbox" class="greyCheck checkAll" data-target=".checkSingle" /></th>
                                <th>TT</th>
                                <th>Thiết bị</th>
                                <th>Loại</th>
                                <th>Mã nhân viên</th>
                                <th>Nhân viên</th>
                                <th>Thời gian</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $index=> $item)
                            @php
                                $device = json_decode($item->content_form);
                            @endphp
                            <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="greyCheck checkSingle" /></td>
                            <td>{{ ($index+1) }}</td>
                            <td>{{ @$device->ip_client }}</td>
                            <td>{{ @$device->device }}</td>
                            <td>{{ @$item->employee->code }}</td>
                            <td>{{ @$item->employee->first_name.' '.@$item->employee->last_name }}</td>
                            <td>
                                 <p><span class="badge badge-success font-weight-100">Vào: </span> {{ @$device->time_in}}</p>
                                 <p><span class="badge badge-primary font-weight-100">Ra: </span> {{ @$device->time_out}}</p>
                            </td>
                            <td>
                                <a title="Lịch sử thao tác" target="_blank" class="d-inline-block btn-info btn-sm text-white"
                                    href="{{ route('admin.activitys.index', ['modelId' => $item->id,'content_type' =>get_class($item)]) }}"><i
                                        class="fa fa-history"></i> </a>
                            </td>
                            </tr>
                            @endforeach
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
                                @php $list = [5, 10, 20, 50, 100, 200]; @endphp
                                @foreach ($list as $num)
                                <option value="{{ $num }}" {{ $num==$per_page ? 'selected' : '' }}>{{ $num }}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                </div> --}}
            </form>
    </div>
@endsection

@section('scripts')
    <script>
         $('input.date_picker').datepicker({
            autoclose: true,
            dateFormat: "dd-mm-yy"
           }).val();
    </script>
@endsection
