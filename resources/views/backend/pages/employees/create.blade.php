@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.employees.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.employees.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.layouts.partials.messages')
        <div class="create-page">
            <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data"
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
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="last_name">
                                        Tên nhân viên<span class="required">*</span>
                                    </label>
                                    <input type="text" data-parsley-required-message="Tên nhân viên là bắt buộc"
                                        class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}"
                                        placeholder="" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="code">
                                        Mã nhân viên <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-control"
                                        data-parsley-required-message="Mã nhân viên là bắt buộc" id="code"
                                        name="code" value="{{ old('code') }}" placeholder="" required>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="phone">
                                        Số điện thoại
                                    </label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{ old('phone') }}" placeholder="">
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="email">
                                        Địa chỉ mail
                                    </label>
                                    <input type="text" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" placeholder="">
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="bank_number">
                                        Số tài khoản ngân hàng
                                    </label>
                                    <input type="text" class="form-control" id="bank_number" name="bank_number"
                                        value="{{ old('bank_number') }}" placeholder="">
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="bank_name">
                                        Tên ngân hàng
                                    </label>
                                    <select class="form-control" id="bank_name" name="bank_name">
                                        <option value="">Chọn ngân hàng</option>
                                        @foreach ($banksLists as $banksList)
                                            <option value="{{ $banksList['id'] }}">
                                                {{ $banksList['code'] . ' - ' . $banksList['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-3 btn-group">
                                <div class="form-group w-100">
                                    <label class="control-label" for="">Bộ phận</label><br>
                                    <select class="form-control" id="department_id" name="department_id">
                                        <option value="">Chọn bộ phận</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="identity_card">
                                        Số CCCD
                                    </label>
                                    <input type="text" class="form-control" id="identity_card" name="identity_card"
                                        value="{{ old('identity_card') }}" placeholder="">
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="begin_date_company">
                                        Ngày vào công ty
                                    </label>
                                    <input type="text" class="form-control date_picker" name="begin_date_company"
                                        id="begin_date_company" value="" placeholder="" autocomplete="off"
                                        data-date-format="dd/mm/yyyy">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="end_date_company">
                                        Ngày nghỉ việc
                                    </label>
                                    <input type="text" class="form-control date_picker" name="end_date_company"
                                        id="end_date_company" value="" placeholder="" autocomplete="off"
                                        data-date-format="dd/mm/yyyy">
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="birthday">
                                        Ngày tháng năm sinh
                                    </label>
                                    <input type="text" class="form-control date_picker" name="birthday"
                                        id="birthday" value="{{ old('birthday') }}" placeholder="" autocomplete="off"
                                        data-date-format="dd/mm/yyyy">
                                </div>
                            </div> --}}
                            <div class="row fixed-bottom">
                                <div class="col-md-6 form-actions mx-auto">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-check"></i> Save
                                    </button>
                                    <a href="{{ route('admin.employees.index') }}" class="btn btn-dark">Quay lại</a>
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
        $('input.date_picker').datepicker({
            autoclose: true,
            dateFormat: "dd-mm-yy"
        }).val();
        $(".roles_select").select2({
            placeholder: "Thiết lập quyền"
        });
    </script>
@endsection
