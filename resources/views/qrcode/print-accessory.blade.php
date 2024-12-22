<style>
    .product {
        margin-top: -30px;
        margin-left: 30px;
        width: 350px;
        border: 1px solid black;
    }

    .product-code {
        font-weight: bold;
        font-size: 20px;
        text-align: center;
        border-bottom: 1px solid black;
    }

    .product-content {
        display: flex;
    }

    .product-info {
        width: 100%;
        display: flex;
        flex-direction: column;
        border: none;
        justify-content: space-around;
    }

    .product-info-item {
        margin-left: 5px;
        border-bottom: none;
        border-top: none;
        border-left: none;
        border-right: none;
    }

    .product-qr {
        border-top: none;
        border-left: none;
        border-bottom: none;
        border-right: 1px solid black;
    }

    html {
        margin: 0;
        padding: 0;
    }

    body {
        margin: 0;
        padding: 0;
    }
</style>
<div class="product">
    @php
        $content_form = json_decode($required->content_form);
        $department = $required->department;
        $employee = $required->employee;
    @endphp
    @if ($required->type == 0)
         <div class="product-code">Dây: {{$required->code}}</div>
    @elseif ($required->type == 1)
         <div class="product-code">Tanshi: {{$required->code}}</div>
    @elseif ($required->type == 2)
         <div class="product-code">Ống: {{$required->code}}</div>
    @elseif ($required->type == 3)
         <div class="product-code">Băng dính: {{$required->code}}</div>
    @else
         <div class="product-code">{{$required->code}}</div>
    @endif

    <div class="product-content">
        <div class="product-qr">
            <img
                src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($required->code)) !!} ">
        </div>
        <div class="product-info">
            <div class="product-info-item">Vị trí xưởng: <strong>{{@$content_form->location_order}}</strong></div>
            {{-- location_c : mã bộ phận --}}
            <div class="product-info-item">Vị trí kho: <strong>{{@$content_form->location}}</strong></div>
            <div class="product-info-item">Công đoạn: <strong>{{@$department->name}}</strong></div>
            <div class="product-info-item">Máy: <strong>{{@$content_form->pc_name}}</strong></div>
            <div class="product-info-item">Người tạo: <strong>{{@$employee->code.'-'.@$employee->first_name.'
                    '.@$employee->last_name.' '.$required->created_at}}</strong></div>
        </div>
    </div>
</div>
