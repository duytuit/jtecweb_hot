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
        <form id="form-search" action="{{ route('admin.checkdevices.index') }}" method="get">
            <div class="row form-group">
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
        <!-- START #show list device -->
        <div class="row col-sm-12">
            <div class="col-sm-12" style="padding: 5px;">
                <div style="position: absolute;
                            top: -10px;
                            left: 20px;
                            background-color: white;
                            font-size: 20px;
                ">Danh sách máy Ipad chưa được kiểm kê</div>
                <div style="padding: 15px 5px 5px;border: 1px solid;">
                   @foreach ($getManger as $index => $value)
                       @if ($value->manager_by)
                        @php
                            $employee = $value->manager;
                        @endphp
                        <div style="margin-bottom: 5px">
                            <span style="display: inline-block;width: 250px;"><strong> Nhóm {{@$employee->code.'-'.$employee->first_name.' '.$employee->last_name}}: </strong></span>
                            @php
                                $uncheck = $assetDevices->where('manager_by',$value->manager_by)->all();
                            @endphp
                            @if($uncheck)
                                @foreach ($uncheck as $item)
                                    <span class="badge badge-warning" style="font-size: 16px">{{$item->name}}</span>
                                @endforeach
                            @endif
                        </div>
                       @endif
                   @endforeach
                </div>
            </div>
        </div>
        <div id="location-device" class="row col-sm-12">
                @if ($lists)
                    @foreach ($positionByDevices as $key => $item)
                        <div class="col-sm-6" style="padding: 5px;">
                            <div style="padding: 5px;border: 1px solid;min-height: 135px;display: flex">
                                    <div style="display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    padding: 5px;
                                    background-color: blueviolet;color: white;">
                                        {{'Bàn '.$item}}
                                   </div>
                                   <div style="display: flex;flex-wrap:wrap">
                                        @foreach ($lists as $key1 => $item1)
                                            @php
                                                $content_form= json_decode($item1->content_form);
                                                $employee = @$item1->employee;
                                            @endphp
                                            @if ($item == $content_form->position)
                                                <div class="info-person-device" style="display:inline-block;padding-left: 5px;padding-right: 5px;">
                                                    <div>
                                                        <img style="object-fit: contain;object-position: top center;" src="{{ '../../public/assets/images/pages/tablet.png' }}" width="50">
                                                    </div>
                                                    <div>
                                                        {{$content_form->name}}
                                                    </div>
                                                    <div>
                                                        {{@$employee->code}}
                                                    </div>
                                                    <div>
                                                        {{@$employee->first_name.' '.@$employee->last_name }}
                                                    </div>
                                                    <div>
                                                        {{date('H:i:s',strtotime(@$item1->updated_at))}}
                                                    </div>
                                                    <div>
                                                        {{@$item->content}}
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                   </div>
                            </div>
                        </div>
                    @endforeach
                @endif
        </div>
        <div class="row form-group">
            <div class="col-sm-1">
                <span class="btn-group">
                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Tác vụ <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a class="btn-action" data-target="#form_lists" data-method="delete" href="javascript:;"><i class="fa fa-trash" style="color: #cb3030;"></i> Xóa</a></li>
                    </ul>
                </span>
            </div>
        </div>
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
                            @endphp
                            <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="greyCheck checkSingle" /></td>
                            <td>{{ ($index+1) }}</td>
                            <td>{{ @$device->name }}</td>
                            <td>{{ @$device->model }}</td>
                            <td>{{ @$device->color }}</td>
                            <td>{{ "Bàn " . @$device->position }}</td>
                            <td>{{ @$item->employee->code }}</td>
                            <td>{{ @$item->employee->first_name.' '.@$item->employee->last_name }}</td>
                            <td>{{ @$item->created_at }}</td>
                            <td>{{ @$item->content }}</td>
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
