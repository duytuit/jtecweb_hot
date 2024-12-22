@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.dynamicColumn.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.dynamicColumn.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.layouts.partials.messages')
        <div class="create-page">
            <form action="{{ route('admin.dynamicColumns.store') }}" method="POST" enctype="multipart/form-data"
                data-parsley-validate data-parsley-focus="first">
                @csrf
                <div class="form-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="col-6 align-items-center justify-content-center mx-auto">
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label" for="title">Tiêu đề<span class="required">*</span></label>
                                    <input type="text" class="form-control"data-parsley-required-message="Tiêu đề là bắt buộc" id="title" name="title" value="{{ old('title') }}" required>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label" for="description">Mô tả<span class="required">*</span></label>
                                    <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}">
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label" for="model">Nhóm tiêu đề</label>
                                    <input type="text" class="form-control" id="type" name="type" value="{{ old('type') }}" placeholder="Hãng sản xuất">
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label" for="model">Bảng quản lý</label>
                                    <input type="text" class="form-control" id="model" name="model" value="{{ old('model') }}" placeholder="Màu sắc">
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label" for="gop_cot">Gộp cột</label>
                                    <input type="text" class="form-control" id="gop_cot" name="gop_cot" value="{{ old('gop_cot') }}" placeholder="Gộp dòng">
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label" for="gop_dong">Gộp dòng</label>
                                    <input type="text" class="form-control" id="gop_dong" name="gop_dong" value="{{ old('gop_dong') }}" placeholder="Gộp dòng">
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label">Ghi chú</label>
                                    <input class="form-control" type="text" name="note" value="{{ old('note') }}" />
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label">Trạng thái</label>
                                    <input type="checkbox" id="_status" data-id="" data-url="" name="status"
                                        value="1" checked class="d-none" />
                                    <label for="_status" class="toggle">
                                        <div class="slider"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="row fixed-bottom">
                                <div class="col-md-6 form-actions mx-auto">
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i>
                                        Lưu</button>
                                    <a href="{{ route('admin.dynamicColumns.index') }}" class="btn btn-dark">Quay lại</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
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
