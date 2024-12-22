<style>
    .product{
        margin-top: -37px;
        margin-left: 115px;
        width: 177px;
        border: 1px solid black;
        margin-bottom: 60px;
        font-size: 8px;
    }
    .product-code{
        font-weight: bold;
        font-size: 20px;
        text-align: center;
        border-top:1px solid black;
    }
    .product-content{
        display: flex;
        height: 98px;
    }
    .product-info{
        width: 100%;
        display: flex;
        flex-direction: column;
        border: none;
        justify-content: space-around;
    }
    .product-info-item{
        margin-left: 5px;
        border-bottom:none;
        border-top: none;
        border-left: none;
        border-right: none;
    }
    .product-qr{
        border-top: none;
        border-left: none;
        border-bottom: none;
        border-right:1px solid black;
        width: 146px;
        height: 90px;
    }
    html{
        margin: 0;
        padding: 0;
    }
    body{
        margin: 0;
        padding: 0;
    }
    .info-text{
        font-size: 14px
    }
    </style>
    <div class="product">
        <div class="product-content">
            <div class="product-qr">
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(87)->generate('abc')) !!} " style="position: absolute;z-index: -1;left: 108px;top: -30px;">
            </div>
            <div class="product-info">
                <div class="product-info-item">Xưởng: <strong class="info-text">P2-3</strong></div>
                {{-- location_c : mã bộ phận --}}
                <div class="product-info-item">Kho: <strong class="info-text">H04</strong></div>
                <div class="product-info-item">C.đoạn: <strong class="info-text">Cắt</strong></div>
                <div class="product-info-item">Máy: <strong class="info-text">PC-MT034</strong></div>
                <div class="product-info-item">Tạo bởi:<strong>Nguyễn Văn A 13:01 20-09-24</strong></div>
            </div>
        </div>
        <div class="product-code">AESSX0.75FB</div>
    </div>


