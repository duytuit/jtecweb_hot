<style>
    .product{
        margin-top: -37px;
        margin-left: 115px;
        width: 177px;
        border: 1px solid black;
        margin-bottom: 60px;
        font-size: 9px;
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
        font-weight: bold;
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
        font-size: 13px
    }
    </style>
    <div class="product">
        @php
            $content_form = json_decode($required->content_form);
            $department = $required->department;
            $employee = $required->employee;
        @endphp
        <div class="product-content">
            <div class="product-qr">
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(87)->generate($required->code)) !!} " style="position: absolute;z-index: -1;left: 108px;top: -30px;">
            </div>
            <div class="product-info">
                <div class="product-info-item">Xưởng: <strong class="info-text">{{@$content_form->location_order}}</strong></div>
                {{-- location_c : mã bộ phận --}}
                <div class="product-info-item">Kho: <strong class="info-text">{{@$required->location}}</strong></div>
                <div class="product-info-item">C.đoạn: <strong class="info-text">{{@$department->name}}</strong></div>
                <div class="product-info-item">Máy: <strong class="info-text">{{@$content_form->pc_name}}</strong></div>
                <div class="product-info-item">Tạo bởi:<strong>{{@$employee->first_name.' '.@$employee->last_name.' '.$required->created_at}}</strong></div>
            </div>
        </div>
        <div class="product-code">{{$required->code}}</div>
    </div>
