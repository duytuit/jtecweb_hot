@extends('backend.layouts.master')
@php
    use App\Helpers\ArrayHelper;
    use App\Models\Admin;
@endphp
@section('title')
    @include('backend.pages.activitys.partials.title')
@endsection
@section('admin-content')
    @include('backend.pages.activitys.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.pages.activitys.partials.top-show')
        @include('backend.layouts.partials.messages')
        <form id="form-search" action="{{ route('admin.activitys.index') }}" method="get">
            <div class="row form-group">
                <div class="col-sm-8">
                    <span class="btn-group">
                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Tác vụ <span
                                class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a class="btn-action" data-target="#form_lists" data-method="delete" href="javascript:;"><i
                                        class="fa fa-trash" style="color: #cb3030;"></i> Xóa</a></li>
                        </ul>
                    </span>
                    <a href="{{ route('admin.activitys.create') }}" class="btn btn-info"><i class="fa fa-edit"></i> Thêm
                        mới</a>
                    <a href="{{ route('admin.activitys.exportExcel', Request::all()) }}" class="btn btn-success"><i
                            class="fa fa-edit"></i> Xuất Excel</a>
                </div>
            </div>
        </form><!-- END #form-search -->
        <!-- START #form-search-advance -->
        <form id="form-search-advance" action="{{ route('admin.activitys.index') }}" method="get" class="hidden">
            <div id="search-advance" class="search-advance" >
                <div class="row form-group">
                    <div class="col-md-2">
                        <input type="text" name="modelId" value="{{ @$filter['modelId'] }}" placeholder="Nhập ID model"
                            class="form-control" />
                    </div>
                    <div class="col-sm-2">
                        <select name="content_type" class="form-control" style="width: 100%;">
                            <option value="">Model</option>
                             @foreach ($models as $item)
                                <option value="{{$item}}" {{ @$filter['content_type'] == $item ? 'selected' : '' }}>{{$item}}</option>
                             @endforeach
                        </select>
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
                        <button class="btn btn-warning btn-block">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- END #form-search-advance -->

        <form id="form_lists" action="{{ route('admin.activitys.action') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="" />
            <div class="table-responsive product-table">
                <table class="table table-bordered ajax_view" id="activitys_table">
                    <thead>
                        <tr>
                            <th width="3%"><input type="checkbox" class="greyCheck checkAll"
                                    data-target=".checkSingle" /></th>
                            <th>ModelId</th>
                            <th>Model</th>
                            <th>Hành động</th>
                            <th>IP</th>
                            <th>Người thao tác</th>
                            <th>Data Old</th>
                            <th>Data New</th>
                            <th>Sql</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $_index => $_item)
                           @php
                                $admin = Admin::findById($_item->user_id);
                                $data_old = json_decode(@$_item->old_data);
                                $data_new = json_decode(@$_item->new_data);
                           @endphp
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $_item->id }}"
                                        class="greyCheck checkSingle" /></td>
                                <td>{{ $_item->content_id }}</td>
                                <td>{{ $_item->content_type }}</td>
                                <td>{{ $_item->action }}</td>
                                <td>{{ $_item->ip_address }}</td>
                                <td>{{@$admin->username.'-'. @$admin->first_name.' '.@$admin->last_name}}</td>
                                <td>
                                    @php
                                        if (@$data_old){
                                            foreach ($data_old as $index => $item) {
                                            if(@$index == 'remember_token'){
                                                continue;
                                            }
                                                @$_index = isset(trans('model')[@$index]) ? trans('model')[@$index] : @$index;
                                                echo '<p  style="width:300px;word-wrap: break-word;">'. @$_index .' : '. ArrayHelper::decode_string(json_encode(@$data_old->$index)).'</p>';
                                        }
                                        }
                                    @endphp
                                </td>
                                <td>
                                    @php
                                      if($data_new){
                                            foreach ($data_new as $index => $item) {
                                            if(@$index == 'remember_token'){
                                                continue;
                                            }
                                            if(@$data_old->$index != @$data_new->$index){

                                                if(@$data_old->$index){
                                                        @$_index = isset(trans('model')[@$index]) ? trans('model')[@$index] : @$index;
                                                        echo '<p  style="width:300px;word-wrap: break-word;">'. @$_index .' : '. ArrayHelper::decode_string(json_encode(@$data_old->$index)).' => '. ArrayHelper::decode_string(json_encode(@$data_new->$index)) .'</p>';
                                                }else{
                                                        @$_index = isset(trans('model')[@$index]) ? trans('model')[@$index] : @$index;
                                                        echo '<p  style="width:300px;word-wrap: break-word;">'. @$_index .' : '.ArrayHelper::decode_string(json_encode(@$data_new->$index)).'</p>';
                                                }
                                            }
                                          }
                                        }
                                    @endphp
                                </td>
                                <td>{{ $_item->sql }}</td>
                                <td>
                                    <a title="Xóa" class=" d-inline-block btn-danger btn-sm text-white"
                                        href="{{ route('admin.activitys.trashed.destroy', ['id' =>$_item->id]) }}"><i
                                            class="fa fa-trash"></i></a>
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
                                <option value="{{ $num }}" {{ $num == $per_page ? 'selected' : '' }}>
                                    {{ $num }}</option>
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
           $('input.date_picker').datepicker({
                autoclose: true,
                dateFormat: "dd-mm-yy"
            }).val();
    </script>
@endsection
