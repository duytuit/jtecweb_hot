@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.employees.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.employees.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.layouts.partials.messages')
        <div class="create-page">
            <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data"
                data-parsley-validate data-parsley-focus="first">
                @csrf
                {{-- @method('PUT') --}}
                <input type="hidden" name="adminId" value="{{ $admin->id }}">
                <div class="form-body">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="first_name">Họ và tên đệm<span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        value="{{ $employee->first_name }}" placeholder="" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="last_name">Tên nhân viên <span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        value="{{ $employee->last_name }}" placeholder="" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="code">Mã nhân viên <span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="code" name="code"
                                        value="{{ $employee->code }}" placeholder="" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="password">Password <span
                                            class="optional">(optional)</span></label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        value="" placeholder="Nhập Password" autocomplete="off" />
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="email">Địa chỉ email</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                        value="{{ $employee->email }}" placeholder="">
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="bank_number">Số tài khoản ngân hàng</label>
                                    <input type="text" class="form-control" id="bank_number" name="bank_number"
                                        value="{{ $employee->bank_number }}" placeholder="">
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="bank_name">Tên ngân hàng</label>
                                    <select class="form-control" id="bank_name" name="bank_name">
                                        @foreach ($banksLists as $banksList)
                                            @if ($banksList['id'] == $employee->bank_name)
                                                <option value="{{ $employee->bank_name }}">
                                                    {{ $banksList['code'] . ' - ' . $banksList['name'] }}</option>
                                            @endif
                                        @endforeach
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
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" {{$department->id == $employee->employeeDepartment->department_id ? 'selected' : null}}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- chưa sửa --}}
                            <div class="col-md-3 btn-group">
                                <div class="form-group w-100">
                                    <label class="control-label" for="">Chức vụ</label><br>
                                    <select class="form-control" id="positions" name="positions">
                                        @foreach ($positions as $key => $position)
                                            @if ($employee->positions == $key)
                                                <option value="{{ $employee->positions }}">{{ $position }}
                                                </option>
                                            @endif
                                        @endforeach
                                        @foreach ($positions as $key => $position)
                                            <option value="{{  $key }}">
                                                {{ $position }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 btn-group">
                                <div class="form-group w-100">
                                    <label class="control-label" for="">Quyền hạn</label><br>
                                    <select class="roles_select form-control custom-select " id="roles"
                                        name="roles[]" multiple style="width: 100%;">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ $admin->hasrole($role->name) ? 'selected' : null }}>
                                                {{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="identity_card">Số CCCD</label>
                                    <input type="text" class="form-control" id="identity_card" name="identity_card"
                                        value="{{ $employee->identity_card }}" placeholder="">
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="begin_date_company">Ngày vào công ty</label>
                                    <input type="text" class="form-control date_picker" name="begin_date_company"
                                        id="begin_date_company" value="{{ $employee->begin_date_company }}"
                                        placeholder="" autocomplete="off" data-date-format="dd/mm/yyyy">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="end_date_company">Ngày nghỉ việc</label>
                                    <input type="text" class="form-control date_picker" name="end_date_company"
                                        id="end_date_company" value="{{ $employee->end_date_company }}" placeholder=""
                                        autocomplete="off" data-date-format="dd/mm/yyyy">
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="birthday">Ngày tháng năm sinh</label>
                                    <input type="text" class="form-control date_picker" name="birthday"
                                        id="birthday" value="{{ $employee->birthday }}" placeholder=""
                                        autocomplete="off" data-date-format="dd/mm/yyyy">
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="status">Status </label>
                                    <select class="form-control custom-select" id="status" name="status">
                                        <option value="1" {{ $employee->status == '1' ? 'selected' : null }}>Active
                                        </option>
                                        <option value="0" {{ $employee->status == '0' ? 'selected' : null }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="worker">Tình trạng làm việc </label>
                                    <select class="form-control" id="worker" name="worker">
                                        @foreach ($workers as $worker)
                                            @if ($employee->worker == $worker['id'])
                                                <option value="{{ $employee->worker }}">{{ $worker['name'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                        @foreach ($workers as $worker)
                                            <option value="{{ $worker['id'] }}">
                                                {{ $worker['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="card-body">
                                <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i>
                                    Lưu</button>
                                <a href="{{ route('admin.employees.index') }}" class="btn btn-dark">Quay lại</a>
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
        $(".categories_select").select2({
            placeholder: "Select a Category"
        });
        $(".roles_select").select2({
            placeholder: "Select Roles to Assign for Access Pages"
        });
    </script>
@endsection
