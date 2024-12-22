@extends('frontend.layouts.master')

@section('title')
    {{ config('app.name') }} | {{ config('app.description') }}
@endsection

@section('main-content')
    <main class="main">
        <div class="print-btn text-center">
            <a href="javascript:window.print()" class="btn btn-success">In dữ liệu A4</a>
            <a href="/qrcode#cat" class="btn btn-primary">Quay lại tạo Qrcode</a>
        </div>
        <div class="print-container">
            <div class="print-wrapper">
                @if (@$printcollection)
                    @php
                        $counter = 0;
                    @endphp

                    <div class="custom-size d-flex text-center justify-content-center">
                        <div class="custom-item">
                            <label for="">Căn chỉnh chữ</label>
                            <div>
                                <button class="btn btn-secondary text_minus">-</button>
                                <span class="fs-default"></span>
                                <button class="btn btn-secondary text_plus">+</button>
                            </div>
                        </div>
                        <div class="custom-item">
                            <label for="">Căn chỉnh ảnh</label>
                            <div>
                                <button class="btn btn-secondary img_minus">-</button>
                                <span class="img-default"></span>
                                <button class="btn btn-secondary img_plus">+</button>
                            </div>
                        </div>
                    </div>
                    @foreach ($printcollection[0] as $item)
                        @if ($item[0])
                            @if ($counter % 25 == 0)
                                <div class="wrapper-25 d-flex ">
                            @endif

                            <div class="card">
                                <div class="card-body">
                                    <div class="card-code">
                                        <strong>{{ $item[0] }}</strong>
                                    </div>
                                    <div class="card-qrcode">
                                        {{-- <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->margin(1)->generate((string) $item[0])) !!} "> --}}
                                        {!! QrCode::size(100)->margin(1)->generate((string) $item[0]) !!}
                                    </div>
                                    <div class="card-position">
                                        <strong>{{ $item[1] }}</strong>
                                    </div>
                                </div>
                            </div>

                            @if ($counter % 25 == 24 || $loop->last)
            </div>
            @endif

            @php
                $counter++;
            @endphp
            @endif
            @endforeach
            @endif
        </div>

        </div>
    </main>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            let font_size = $('.card-code, .card-position').css('font-size');
            $('.fs-default').text(font_size);

            let img_size = $('.card-qrcode svg').css('width');
            $('.img-default').text(img_size);


            $('.img_plus').click(function(e) {
                e.preventDefault();
                let img_size = $('.card-qrcode svg').css('width');
                let img_plus = (parseInt(img_size.replace("px", "")) + 1) + 'px';
                $('.card-qrcode svg').attr('width', img_plus);
                $('.card-qrcode svg').attr('height', img_plus);
                $('.img-default').text(img_plus);
            })
            $('.img_minus').click(function(e) {
                e.preventDefault();
                let img_size = $('.card-qrcode svg').css('width');
                let img_minus = (parseInt(img_size.replace("px", "")) - 1) + 'px';
                $('.card-qrcode svg').attr('width', img_minus);
                $('.card-qrcode svg').attr('height', img_minus);
                $('.img-default').text(img_minus);
            })

            $('.text_plus').click(function(e) {
                e.preventDefault();
                let font_size = $('.card-code, .card-position').css('font-size');
                let fs_plus = (parseInt(font_size.replace("px", "")) + 1) + 'px';
                $('.card-code, .card-position').css('font-size', fs_plus);
                $('.fs-default').text(fs_plus);
            })
            $('.text_minus').click(function(e) {
                e.preventDefault();
                let font_size = $('.card-code, .card-position').css('font-size');
                let fs_minus = (parseInt(font_size.replace("px", "")) - 1) + 'px';
                $('.card-code, .card-position').css('font-size', fs_minus);
                $('.fs-default').text(fs_minus);
            })
        });
    </script>
@endsection
