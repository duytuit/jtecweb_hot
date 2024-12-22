@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.uploadData.partials.title')
@endsection

@section('admin-content')
  @include('backend.pages.uploadData.partials.header-breadcrumbs')
  <div class="container-fluid">
        @include('backend.layouts.partials.messages')
        <div class="create-page">
            <form action="{{ route('admin.uploadDatas.update', ['id' => $asset->id]) }}" method="POST" enctype="multipart/form-data"
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
                        <input type="hidden" name="assetId" id="assetId" value="{{ $asset->id }}">
                        <div class="col-6 align-items-center justify-content-center mx-auto">
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label" for="code">Mã dữ liệu<span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control"
                                        data-parsley-required-message="Mã dữ liệu là bắt buộc" id="code" name="code"
                                        value="{{ $asset->code }}" placeholder="" required="">
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label" for="name">Tên dữ liệu<span
                                            class="required">*</span></label>
                                    <input type="text" data-parsley-required-message="Tên dữ liệu là bắt buộc"
                                        class="form-control" id="name" name="name" value="{{ $asset->name }}"
                                        placeholder="" required="">
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label" for="model">Hãng sản xuất</label>
                                    <input type="text" class="form-control" id="model" name="model" value="{{ $asset->model }}" placeholder="Hãng sản xuất">
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label" for="color">Màu sắc</label>
                                    <input type="text" class="form-control" id="color" name="color" value="{{ $asset->color }}" placeholder="Màu sắc">
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label" for="image">Ảnh</label>
                                    <input type="file" class="form-control dropify" data-height="100"
                                        data-allowed-file-extensions="png jpg jpeg webp" id="image" name="image" data-default-file="{{ $asset->image != null ? asset('public/assets/images/asset/' . $asset->image) : null }}" />
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label">Người quản lý</label>
                                    <div class="select_manager">
                                        <select name="manager_by" id="manager_by" class="form-control" style="width:100%">
                                             @if ($manager)
                                                <option value="{{$manager->id}}">{{$manager->code.'-'.$manager->first_name.' '.$manager->last_name}}</option>
                                             @else
                                                <option value="">Người quản lý</option>
                                             @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label">Ghi chú</label>
                                    <input class="form-control" type="text" name="note" value="{{ $asset->note }}" />
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="form-group">
                                    <label class="control-label">Trạng thái</label>
                                    <input type="checkbox" id="_status" name="status"
                                        value="1" {{ $asset->status ? 'checked' : '' }} class="d-none" />
                                    <label for="_status" class="toggle">
                                        <div class="slider"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="row fixed-bottom">
                                <div class="col-md-6 form-actions mx-auto">
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i>
                                        Lưu</button>
                                    <a href="{{ route('admin.assets.index') }}" class="btn btn-dark">Quay lại</a>
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
        $(".categories_select").select2({
            placeholder: "Select a Category"
        });
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
