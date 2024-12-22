@extends('backend.auth.master')

@section('auth-content')
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background:url({{ asset('public/assets/backend/images/big/auth-bg.jpg') }}) no-repeat center center;">
    <div class="auth-box">
        <div id="loginform">
            <div class="logo">
                <span class="db"><img src="{{ asset('public/assets/frontend/images/logos/logo-jtec.png') }}" width="250" alt="logo" /></span>
                <h5 class="font-medium m-b-1">Đăng nhập trang quản trị</h5>
            </div>
            <!-- Form -->
            <div class="row">
                <div class="col-12">
                    @include('backend.layouts.partials.messages')
                    <form class="form-horizontal m-t-20" method="post" id="loginform" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" name="username" placeholder="Mã nhân viên" aria-label="Username" aria-describedby="basic-addon1">
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                            </div>
                            <input type="password" class="form-control form-control-lg" name="password" placeholder="Mật khẩu" aria-label="Password" aria-describedby="basic-addon1">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-numeric-container">
                                    <table class="table-numeric">
                                        <tbody>
                                            <tr>
                                                <td><button type="button" class="key" data-key="1">1</button></td>
                                                <td><button type="button" class="key" data-key="2">2</button></td>
                                                <td><button type="button" class="key" data-key="3">3</button></td>
                                            </tr>
                                            <tr>
                                                <td><button type="button" class="key" data-key="4">4</button></td>
                                                <td><button type="button" class="key" data-key="5">5</button></td>
                                                <td><button type="button" class="key" data-key="6">6</button></td>
                                            </tr>
                                            <tr>
                                                <td><button type="button" class="key" data-key="7">7</button></td>
                                                <td><button type="button" class="key" data-key="8">8</button></td>
                                                <td><button type="button" class="key" data-key="9">9</button></td>
                                            </tr>
                                            <tr>
                                                <td><button type="button" class="key-del" disabled>Del</button></td>
                                                <td><button type="button" class="key" data-key="0">0</button></td>
                                                <td><button type="button" class="key-clear" disabled>Clear</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember">Ghi nhớ mật khẩu</label>
                                    {{-- <a href="{{ route('admin.password.request') }}" class="text-dark float-right"><i class="fa fa-lock m-r-5"></i> Quên mật khẩu?</a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12 pb-2">
                                <button class="btn btn-block btn-lg btn-info" type="submit">Đăng nhập</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .input-numeric-container {
        background: #fff;
        margin: 0.1em auto;
        max-width: 350px;
    }

    .table-numeric {
        width: 100%;
        border-collapse: collapse;
    }

    .table-numeric td {
        vertical-align: top;
        text-align: center;
        width: 33.33333333333%;
        border: 0;
    }

    .table-numeric button {
        position: relative;
        cursor: pointer;
        display: block;
        width: 100%;
        box-sizing: border-box;
        padding: 0.1em 0.1em;
        font-size: 1em;
        border-radius: 0.1em;
        outline: none;
        user-select: none;
    }

    .table-numeric button:active {
        top: 2px;
    }

    .key {
        background: #fff;
        border: 1px solid #d8d6d6;
    }

    .key-del {
        background: #FF9800;
        border: 1px solid #ca7800;
        color: #fff;
    }

    .key-clear {
        background: #E91E63;
        border: 1px solid #c70a4b;
        color: #fff;
    }

    button[disabled] {
        opacity: 0.5;
        cursor: no-drop;
    }

    [data-numeric="hidden"] .table-numeric {
        display: none;
    }
</style>

@section('scripts')
<script>
     $('input[name="username"]').focus();

     var cursor='input[name="username"]';

     $( document ).ready(function(){


        $('input[name="username"]').focus(function() {
            cursor = 'input[name="username"]';
            console.log(cursor);
        }).keypress(function(e){
            if (e.keyCode == 13){
                $('input[name="password"]').focus();
                return false;
            }
        });

        $('input[name="password"]').focus(function() {
            cursor = 'input[name="password"]';
            console.log(cursor);
        });
        $( document ).on( "change", cursor, function(){

            var parents = $( ".input-numeric-container" );
            var input = $(cursor);
            var nonKeys = parents.find( ".key-del, .key-clear");
            var inputValue = input.val();

            if( $('input[name="username"]').val() == "" && $('input[name="password"]').val() == "" ){
                nonKeys.prop( "disabled", true );
            } else {
                nonKeys.prop( "disabled", false );
            }

        });


        $( document ).on( "click focus",cursor, function(){

            var parents = $( this ).parents( ".input-numeric-container" );
            var data = parents.attr( "data-numeric" );

            if( data ){
                if( data == "hidden" ){
                parents.find( ".table-numeric" ).show();
                }
            }

        });


        //key numeric
        $( document ).on( "click", ".key", function(){

            var number = $( this ).attr( "data-key" );
            var input = $(cursor);
            var inputValue = input.val();
            input.val( inputValue + number ).change();

        });

        //delete

        $('.key-del').click(function(){
            var input = $(cursor);
            var inputValue = input.val();
            console.log(inputValue);
            input.val( inputValue.slice(0, -1) ).change();
        })

        //clear

        $('.key-clear').click(function(){
            var input = $(cursor);
            console.log(input.val());
            input.val( "" ).change();
        })

    });
</script>
@endsection
