@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.admins.partials.title')
    @php $user = Auth::user(); @endphp
@endsection

@section('admin-content')
    @include('backend.pages.admins.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.layouts.partials.messages')
        <div class="create-page">
            @php
                if (!Route::is('admin.admins.profile.edit')) {
                    $route = route('admin.admins.update', $admin->id);
                } else {
                    $route = route('admin.admins.profile.update');
                }
            @endphp
            <form action="{{ $route }}" method="POST" data-parsley-validate data-parsley-focus="first"
                enctype="multipart/form-data" autocomplete="off">
                @csrf
                @method('put')
                <div class="form-body">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="first_name">Họ<span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        value="{{ $admin->first_name }}" placeholder="Nhập họ tên" required="" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="last_name">Tên<span
                                            class="optional">(không bắt buộc)</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        value="{{ $admin->last_name }}" placeholder="Nhập tên" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="designation">Giới thiệu bản thân<span
                                            class="optional">(không bắt buộc)</span></label>
                                    <input type="text" class="form-control" id="designation" name="designation"
                                        value="{{ $admin->designation }}" placeholder="Hãy giới thiệu về bản thân của bạn" required="" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="phone_no">Số điện thoại <span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="phone_no" name="phone_no"
                                        value="{{ $admin->phone_no }}" placeholder="Nhập số điện thoại" required="" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="email">Email<span
                                            class="required">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ $admin->email }}" placeholder="Email" required="" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="username">Tài Khoản<span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        value="{{ $admin->username }}" placeholder="Nhập tài khoản"
                                        data-parsley-type="alphanum" required readonly/>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label" for="status">Trạng thái<span
                                            class="required">*</span></label>
                                    <select class="form-control custom-select" id="status" name="status" required>
                                        <option value="1" {{ $admin->status == '1' ? 'selected' : null }}>Hoạt động
                                        </option>
                                        <option value="0" {{ $admin->status == '0' ? 'selected' : null }}>Không hoạt động
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label" for="visible_in_team">Hiển thị nhóm nhóm<span
                                            class="required">*</span></label>
                                    <select class="form-control custom-select" id="visible_in_team" name="visible_in_team"
                                        required>
                                        <option value="1" {{ $admin->visible_in_team == '1' ? 'selected' : null }}>
                                            Hoạt động
                                        </option>
                                        <option value="0" {{ $admin->visible_in_team == '0' ? 'selected' : null }}>
                                            Không hoạt động</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="password">Mật khẩu <span
                                            class="optional">(không bắt buộc)</span></label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        value="{{ old('password') }}" placeholder="Nhập mật khẩu"
                                        data-parsley-equalto="#password_confirmation" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="password_confirmation">Xác nhận mật khẩu<span
                                            class="optional">(không bắt buộc)</span></label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" value="{{ old('password') }}"
                                        placeholder="Nhập xác nhận mật khẩu" data-parsley-equalto="#password"
                                        autocomplete="off" />
                                </div>
                            </div>
                            @if (!Route::is('admin.admins.profile.edit'))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="roles">Nhóm Quyền<span
                                                class="optional">(không bắt buộc)</span></label>
                                        <br>
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
                            @endif
                            <div class="form-group">
                                <label class="control-label" for="languages">Ngôn ngữ<span
                                        class="optional">(không bắt buộc)</span></label>
                                <br>
                                <select class="roles_select form-control custom-select " id="languages"
                                    name="language_id" style="width: 100%;">
                                    @foreach ($languages as $language)
                                        <option value="{{ $language->id }}"
                                            {{ $admin->language_id == $language->id ? 'selected' : '' }}>
                                            {{ $language->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="avatar">Ảnh đại diện <span
                                            class="optional">(không bắt buộc)</span></label>
                                    <input type="file" class="form-control dropify" data-height="70"
                                        data-allowed-file-extensions="png jpg jpeg webp" id="avatar" name="avatar"
                                        data-default-file="{{ $admin->avatar != null ? asset('public/assets/images/admins/' . $admin->avatar) : null }}" />
                                </div>
                            </div>
                            {{-- <div class="col-md-12">
                                <label class="control-label">Social Links <span class="optional">(không bắt buộc)</span></label>

                                <div class="row">
                                    @php $user_social_links = json_decode(empty($admin->social_links) ? '' : $admin->social_links); @endphp

                                    @foreach ($social_links as $social => $socialName)
                                        @php $social_link = empty($user_social_links->$social) ? '' : $user_social_links->$social; @endphp
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {{ $socialName }}
                                                <input type="url" name="social_links[{{ $social }}]"
                                                    value="{{ $social_link }}" class="form-control" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="card-body">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Lưu</button>
                            <a href="{{ Route::is('admin.admins.profile.edit') ? route('admin.index') : route('admin.admins.index') }}"
                                class="btn btn-dark">Quay lại</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });
        $(".roles_select").select2({
            placeholder: "Select Roles to Assign for Access Pages"
        });
    </script>
@endsection
