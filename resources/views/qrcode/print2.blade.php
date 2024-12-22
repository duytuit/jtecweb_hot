@extends('frontend.layouts.master')

@section('title')
    {{ config('app.name') }} | {{ config('app.description') }}
@endsection

@section('main-content')
    <main class="main">
        <div class="print-btn text-center">
            <a href="javascript:window.print()" class="btn btn-success">In dữ liệu A4</a>
            <a href="/qrcode#kho" class="btn btn-primary">Quay lại tạo Qrcode</a>
        </div>
        <div class="print-container">
            <div class="print2-wrapper">
                @if (@$printcollection2)
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
                        {{-- <div class="custom-item">
                            <label for="">Số lượng trong 1 trang</label>
                            <div>
                                <button class="btn btn-secondary quantity_minus">-</button>
                                <span id="quantityDefault" class="quantity-default"></span>
                                <button class="btn btn-secondary quantity_plus">+</button>
                            </div>
                        </div> --}}
                    </div>
                    @foreach ($printcollection2[0] as $item)
                        @if ($item[0])
                            @if ($counter % 12 == 0)
                                <div class="wrapper-div d-flex ">
                            @endif
                            <script></script>
                            <div class="card">
                                <div class="card-title justify-content-between d-flex">
                                    <span class="title-left"> {{ $item[1] . ' ' . $item[2] }}</span>
                                    <span class="title-right text-right">{{ $item[3] . ' ' . $item[4] }}</span>
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-around">
                                    <div class="card-code text-center">
                                        <span class="d-block">CODE</span>
                                        <strong>{{ $item[0] }}</strong>
                                    </div>
                                    <div class="card-qrcode">
                                        {!! QrCode::size(100)->margin(1)->generate((string) $item[2] . $item[0]) !!}
                                    </div>
                                </div>
                            </div>
                            @if ($counter % 12 == 11 || $loop->last)
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
            let quantity = 12;
            $('.quantity-default').text(quantity);


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
                let font_size = $('.card-code').css('font-size');
                let fs_plus = (parseInt(font_size.replace("px", "")) + 1) + 'px';
                $('.card-code').css('font-size', fs_plus);
                $('.fs-default').text(fs_plus);
            })
            $('.text_minus').click(function(e) {
                e.preventDefault();
                let font_size = $('.card-code').css('font-size');
                let fs_minus = (parseInt(font_size.replace("px", "")) - 1) + 'px';
                $('.card-code').css('font-size', fs_minus);
                $('.fs-default').text(fs_minus);
            })

            // $('.quantity_plus').click(function(e) {
            //     e.preventDefault();
            //     let quantity = $('.quantity-default').text();
            //     let quantity_plus = (parseInt(quantity.replace("px", "")) + 1);
            //     $('.quantity-default').text(quantity_plus);
            // })
            // $('.quantity_minus').click(function(e) {
            //     e.preventDefault();
            //     let quantity = $('.quantity-default').text();
            //     let quantity_minus = (parseInt(quantity.replace("px", "")) - 1);
            //     $('.quantity-default').text(quantity_minus);
            // })
        });
    </script>
@endsection
