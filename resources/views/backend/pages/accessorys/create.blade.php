@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.requireds.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.requireds.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.layouts.partials.messages')
        <div class="create-page">
            <form action="{{ route('admin.accessorys.store') }}" method="POST" enctype="multipart/form-data" data-parsley-validate data-parsley-focus="first">
                @csrf
                <div class="form-body">
                    <div class="card-body">
                          <div class="row justify-content-md-center">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label class="control-label" for="code">Mã linh kiện<span class="required">*</span></label>
                                      <input type="text" class="form-control" id="code" name="code" value=""
                                          placeholder="Mã linh kiện" required
                                          data-parsley-required-message="Trường mã lịnh kiện là bắt buộc"/>
                                  </div>
                                  <div class="form-group">
                                    <div>
                                        <input name="type" id="hang_thuong"
                                        style="width:20px;height:20px; vertical-align: middle;" type="radio"
                                        value="0"
                                        data-parsley-error-message="Vui lòng chọn một loại số lượng.">
                                        <label for="hang_thuong">Hàng thường</label>
                                        <input name="type" id="hang_dac_biet"
                                        style="width:20px;height:20px; vertical-align: middle;" type="radio"
                                        value="1" >
                                        <label for="hang_dac_biet">Hàng đặc biệt</label>
                                        <input name="type" id="hang_van_phong_pham"
                                        style="width:20px;height:20px; vertical-align: middle;" type="radio"
                                        value="2" >
                                        <label for="hang_van_phong_pham">Hàng văn phòng phẩm</label>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label" for="note_type">Mô tả</label>
                                    <input type="text" class="form-control" id="note_type" name="note_type"/>
                                  </div>
                                  <div class="form-group">
                                      <label class="control-label" for="unit">Đơn vị</label>
                                      <input type="text" class="form-control" id="unit" name="unit" />
                                  </div>
                                  <div class="form-group">
                                      <label class="control-label" for="material_norms">Định mức</label>
                                      <input type="number" min="1" class="form-control" id="material_norms" name="material_norms"/>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="row fixed-bottom">
                          <div class="col-md-6 form-actions mx-auto">
                              <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i>
                                  Lưu</button>
                              <a href="{{ route('admin.accessorys.index') }}" class="btn btn-dark">Quay lại</a>
                          </div>
                      </div>
                  </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

    </script>
@endsection
