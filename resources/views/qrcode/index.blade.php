@extends('frontend.layouts.master')

@section('title')
    {{ config('app.name') }} | {{ config('app.description') }}
@endsection

@section('main-content')
    <main class="main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 mt-5">

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h4>Tạo mã QR code</h4>
                        </div>
                        <div class="card-body">

                            {{-- <form action="{{ url('qrcode') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group">
              <input type="file" name="import_file" class="form-control" />
              <button type="submit" class="btn btn-primary">Xem mã Qrcode</button>
            </div>
            </form>
            <hr> --}}

                            <form class="cat-style" action="{{ url('qrcode/printfile') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input type="file" name="import_file_print" class="form-control" />
                                    <button type="submit" class="btn btn-primary">In dữ liệu - Bp Cắt</button>
                                </div>
                            </form>
                            <hr>
                            <form class="kho-style" action="{{ url('qrcode/printfile2') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input type="file" name="import_file_print2" class="form-control" />
                                    <button type="submit" class="btn btn-success">In dữ liệu - Bp Kho</button>
                                    <div class="custom-item">
                                        <a href={{ url('public/assets/frontend/kho.xlsx') }} target="_blank"
                                            class="btn btn-primary ml-1">Tải file mẫu</a>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <form action="{{ url('qrcode/generate') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input type="text" id="inputcode" name="InputCode" class="form-control" />
                                    <button type="submit" class="btn btn-primary">Tạo mã QR</button>
                                </div>
                            </form>
                            <hr>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Mã</th>
                                        <th>QR code</th>
                                        <th>Bộ phận</th>
                                    </tr>
                                </thead>
                                @if (@$inputCode)
                                    <tbody>
                                        <tr>
                                            <td>{{ $inputCode }}</td>
                                            <td style="text-align: center;">
                                                @php
                                                    $img =
                                                        'data:image/svg+xml;base64, ' .
                                                        base64_encode(
                                                            QrCode::size(100)->margin(1)->generate((string) $inputCode),
                                                        );
                                                @endphp
                                                <img src="{{ $img }}">
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                @endif

                                @if (@$collection)
                                    <tbody>
                                        @foreach ($collection[0] as $item)
                                            @if ($item[0])
                                                <tr>
                                                    <td>{{ $item[0] }}</td>
                                                    <td style="text-align: center;">
                                                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->margin(1)->generate((string) $item[0])) !!} ">
                                                    </td>
                                                    <td>{{ $item[1] }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                @endif
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        if (window.location.hash === '#kho') {
            $('.cat-style').css('display', 'none');
        } else if (window.location.hash === '#cat') {
            $('.kho-style').css('display', 'none');
        } else {
            $('.cat-style').css('display', 'block');
            $('.kho-style').css('display', 'block');
        }
    </script>
@endsection
