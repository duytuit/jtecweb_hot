@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.uploadData.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.uploadData.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.pages.uploadData.partials.top-show')
        @include('backend.layouts.partials.messages')
        <form id="form-search" action="{{ route('admin.uploadDatas.index') }}" method="get">
            <div class="row form-group">
                <div class="col-sm-8">
                    {{-- <span class="btn-group">
                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Tác vụ <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a class="btn-action" data-target="#form_lists" data-method="delete" href="javascript:;"><i class="fa fa-trash" style="color: #cb3030;"></i> Xóa</a></li>
                        </ul>
                    </span> --}}
                    {{-- <a href="{{ route('admin.uploadDatas.restartWebPdf') }}" class="btn btn-secondary"><i class="fa fa-edit"></i> Reset Web</a> --}}
                    <a href="{{ route('admin.uploadDatas.create') }}" class="btn btn-info"><i class="fa fa-edit"></i> Cập nhật</a>
                </div>
            </div>
        </form><!-- END #form-search -->
        <form id="form-search-advance" action="{{ route('admin.uploadDatas.index') }}" method="get" class="hidden">
            <div id="search-advance" class="search-advance">
                <div class="row form-group space-5">
                    <div class="col-sm-2">
                        <input type="text" name="code" value="{{  @$filter['code']  }}" placeholder="Nhập từ khóa" class="form-control" />
                    </div>
                    <div class="col-sm-2">
                        <select name="type" class="form-control" style="width: 100%;">
                            <option value="">Màn hình</option>
                            <option value="1" {{ @$filter['type'] === '1' ? 'selected' : '' }}>1 màn hình</option>
                            <option value="2" {{ @$filter['type'] === '2' ? 'selected' : '' }}>2 màn hình</option>
                            <option value="3" {{ @$filter['type'] === '3' ? 'selected' : '' }}>3 màn hình</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="status" class="form-control" style="width: 100%;">
                            <option value="">Trạng thái</option>
                            <option value="1" {{ @$filter['status'] === '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ @$filter['status'] === '0' ? 'selected' : '' }}>Chưa hoạt động</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="updated_by" id="updated_by" class="form-control" style="width:100%">
                            <option value="">Người tải dữ liệu</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-warning btn-block">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </form><!-- END #form-search-advance -->
        <form id="form_lists" action="{{ route('admin.uploadDatas.action') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="" />
            <input type="hidden" name="status" value="" />
            <div class="table-responsive product-table">
                <table class="table table-bordered" id="exams_table">
                    <thead>
                        <tr>
                            <th>TT</th>
                            <th>Code</th>
                            <th>Đường dẫn</th>
                            <th>Màn hình</th>
                            <th>Trạng thái</th>
                            <th>Người tạo</th>
                            <th>Chỉnh sửa lần cuối</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $index=> $item)
                            @php
                               $createdBy= $item->createdBy;
                               $updatedBy= $item->updatedBy;
                               $url = str_replace('_?_','',str_replace('/','\_?_',$item->url));
                               $_url = 'D:/jtecdata/JTEC_PD_PROGAM/CMSWeb/jtecweb/public/'.$item->url;
                            @endphp
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>{{$item->code }}</td>
                                <td><a href="/{{ $url  }}" target="_blank" >{{ $url  }}</a></td>
                                <td>{{$item->type }}</td>
                                <td>
                                    @if (file_exists($_url))
                                        <span class="badge badge-success font-weight-100">Tải thành công</span>
                                    @else
                                        <span class="badge badge-warning">
                                            Tải lại
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div> {{ @$createdBy->first_name.' '.@$createdBy->last_name }}</div>
                                    <div> {{ $item->created_at }}</div>
                                </td>
                                <td>
                                    <div> {{ @$updatedBy->first_name.' '.@$updatedBy->last_name }}</div>
                                    <div> {{ $item->updated_at }}</div>
                                </td>
                                <td>
                                    <a title="Lịch sử thao tác" target="_blank" class="d-inline-block btn-info btn-sm text-white"
                                    href="{{ route('admin.activitys.index', ['modelId' => $item->id,'content_type' =>get_class($item)]) }}"><i
                                        class="fa fa-history"></i> </a>
                                        <a title="Xóa" class=" d-inline-block btn-danger btn-sm text-white"
                                        href="{{ route('admin.uploadDatas.trashed.destroy', ['id' =>$item->id]) }}"><i
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
                        <select name="per_page" class="form-control" style="display: inline;width: auto;" data-target="#form_lists">
                            @php $list = [5, 10, 20, 50, 100, 200]; @endphp
                            @foreach ($list as $num)
                                <option value="{{ $num }}" {{ $num == $per_page ? 'selected' : '' }}>{{ $num }}</option>
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
         get_data_select_name({
            object: '#updated_by',
            url: '{{ url('admin/employees/ajaxGetSelectByName') }}',
            data_id: 'id',
            data_code: 'code',
            data_first_name: 'first_name',
            data_last_name: 'last_name',
            title_default: 'Người tải dữ liệu',

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
