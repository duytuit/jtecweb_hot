@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.productionPlan.partials.title')
@endsection

@section('admin-content')
    @include('backend.pages.productionPlan.partials.header-breadcrumbs')
    <div class="container-fluid">
        @include('backend.pages.productionPlan.partials.top-show')
        @include('backend.layouts.partials.messages')
        <form id="form-search-advance" action="{{ route('admin.productionPlans.index') }}" method="get" class="hidden">
            <div id="search-advance" class="search-advance">
                <div class="row form-group space-5">
                    <div class="col-sm-2">
                        <input type="text" name="keyword" value="{{@$filter['keyword']}}" placeholder="Nhập từ khóa" class="form-control" />
                    </div>
                    <div class="col-sm-2">
                        <select name="bp" class="form-control" style="width: 100%;"  data-live-search="true" onchange="this.form.submit()">
                            <option value="1" {{ @$filter['bp'] == '1' ? 'selected' : '' }}>Kiểm tra ngoại quan</option>
                            <option value="2" {{ @$filter['bp'] == '2' ? 'selected' : '' }}>Kiểm tra thông mạch</option>
                        </select>
                        <noscript><input type="submit" value="Submit"></noscript>
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-warning btn-block">Tìm kiếm</button>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('admin.productionPlans.asyncProductionPlan') }}" class="btn btn-success">Kế Hoạch Đồng bộ lúc: {{@$asyncProductionPlan ? json_decode(@$asyncProductionPlan)->time:''}}</a>
                    </div>
                    <div class="col-sm-3">
                        @switch($filter['bp'])
                            @case(1)
                                <a href="{{ route('admin.productionPlans.asyncKTNQ') }}" class="btn btn-success">KTNQ Đồng bộ lúc: {{@$update_AsyncKTNQ ? json_decode(@$update_AsyncKTNQ)->time:''}}</a>
                                @break
                            @case(2)
                                <a href="{{ route('admin.productionPlans.asyncKTTM') }}" class="btn btn-success">KTTM Đồng bộ lúc: {{@$update_AsyncKTTM ? json_decode(@$update_AsyncKTTM)->time:''}}</a>
                                @break
                            @default
                                <a href="{{ route('admin.productionPlans.asyncKTNQ') }}" class="btn btn-success">KTNQ Đồng bộ lúc: {{@$update_AsyncKTNQ ? json_decode(@$update_AsyncKTNQ)->time:''}}</a>
                        @endswitch
                    </div>
                </div>
            </div>
        </form><!-- END #form-search-advance -->
        <form id="form_lists" action="{{ route('admin.productionPlans.action') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="" />
            <input type="hidden" name="status" value="" />
            <div class="table-responsive product-table">
                <table class="table table-bordered" id="exams_table">
                    <thead>
                        <tr>
                            <th rowspan="2">TT</th>
                            <th rowspan="2">Thao tác</th>
                            @if ($productionPlanKTNQ)
                                @php
                                    $check = 0;
                                @endphp
                                @foreach ($productionPlanKTNQ as $item)
                                    @if ($item['data_type'] > 1)
                                        @php
                                            $check++;
                                        @endphp
                                       <th rowspan="2">{{$item['column_name']}}</th>
                                    @endif
                                @endforeach
                            @endif
                            @switch($filter['bp'])
                                @case(1)
                                    @if ($productionPlanKTNQ)
                                        @foreach ($productionPlanKTNQ as $item)
                                            @if ($item['data_type'] == 1)
                                                <th rowspan="2">{{$item['column_name']}}</th>
                                            @endif
                                        @endforeach
                                    @endif
                                    @break
                                @case(2)
                                    @if ($productionPlanKTTM)
                                        @foreach ($productionPlanKTTM as $item)
                                            @if ($item['data_type'] == 1)
                                                <th rowspan="2">{{$item['column_name']}}</th>
                                            @endif
                                        @endforeach
                                    @endif
                                    @break
                                @default
                                    @if ($productionPlanKTNQ)
                                        @foreach ($productionPlanKTNQ as $item)
                                            @if ($item['data_type'] == 1)
                                                <th rowspan="2">{{$item['column_name']}}</th>
                                            @endif
                                        @endforeach
                                    @endif
                            @endswitch
                            <th rowspan="2">Mã hàng 1</th>
                            {{-- <th rowspan="2">Mã hàng 2</th> --}}
                            <th colspan="3">仕掛品-HÀNG DỞ DANG</th>
                            {{-- <th rowspan="2">在庫数Tồn Kho</th> --}}
                            @if (@$productionPlanHeaderWeekday)
                                @foreach ($productionPlanHeaderWeekday as $item)
                                   <th rowspan="2">{{$item}}</th>
                                @endforeach
                            @else
                                <th rowspan="2">Thứ 2 LẺ</th>
                                <th rowspan="2">Thứ 3 AIR</th>
                                <th rowspan="2">Thứ 4 LẺ</th>
                                <th rowspan="2">Thứ 5 LẺ</th>
                                <th rowspan="2">Thứ 5 AIR</th>
                                <th rowspan="2">Thứ 6 AIR</th>
                                <th rowspan="2">Thứ 6 SEA OSAKA</th>
                                <th rowspan="2">Thứ 6 SEA TOKYO</th>
                            @endif
                            {{-- <th rowspan="2">Mẫu</th> --}}
                            <th colspan="3">SỐ LƯỢNG HÀNG XUẤT CHƯA LÀM</th>
                            {{-- <th rowspan="2">単価</th>
                            <th rowspan="2">回路数</th> --}}
                            {{-- <th colspan="3">SỐ LƯỢNG DÂY CẮT CHƯA LÀM</th> --}}
                            <th rowspan="2">Mẫu</th>
                            <th rowspan="2">Dây cắt</th>
                            <th rowspan="2">Bên gia công</th>
                        </tr>
                        <tr>
                            <th>組付 Lráp</th>
                            <th>検査 KTTM</th>
                            <th>検査 KTNQ</th>
                            <th>組付 Lráp</th>
                            <th>検査 KTTM</th>
                            <th>検査 KTNQ</th>
                            {{-- <th>組付 Lráp</th>
                            <th>検査 KTTM</th>
                            <th>検査 KTNQ</th> --}}
                            {{-- <th>挿入 Cắm</th>
                            <th>組付 Lráp</th>
                            <th>Buredo</th>
                            <th>検査 KTTM</th>
                            <th>検査 KTNQ</th>
                            <th>Dập</th>
                            <th>挿入 Cắm</th>
                            <th>組付 Lráp</th>
                            <th>Buredo</th>
                            <th>検査 KTTM</th>
                            <th>検査 KTNQ</th>
                            <th>Dập</th>
                            <th>挿入 Cắm</th>
                            <th>組付 Lráp</th>
                            <th>Buredo</th>
                            <th>検査 KTTM</th>
                            <th>検査 KTNQ</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $index=> $item)
                        @php
                            $temp = @$check;
                            $description = json_decode($item->description);
                            $_ktnq = (array)json_decode($item->ktnq);
                            $_kttm = (array)json_decode($item->kttm);
                        @endphp
                             @if (@$description)
                             <tr>
                                <td>{{ $index+1 }}</td>
                                @switch($filter['bp'])
                                    @case(1)
                                        <td>
                                            <a href="{{ route('admin.productionPlans.edit', ['id' => $item->id,'type'=>'1']) }}" class="btn btn-sm btn-primary"><span class="fa fa-edit"></span></a>
                                        </td>
                                        @break
                                    @case(2)
                                        <td>
                                            <a href="{{ route('admin.productionPlans.edit', ['id' => $item->id,'type'=>'2']) }}" class="btn btn-sm btn-primary"><span class="fa fa-edit"></span></a>
                                        </td>
                                        @break
                                    @default
                                        <td>
                                            <a href="{{ route('admin.productionPlans.edit', ['id' => $item->id,'type'=>'1']) }}" class="btn btn-sm btn-primary"><span class="fa fa-edit"></span></a>
                                        </td>
                                @endswitch
                                @if ($productionPlanKTNQ)
                                    @foreach ($productionPlanKTNQ as $ktnq)
                                        @if ($_ktnq)
                                            @if ($ktnq['data_type'] > 1)
                                                <td><a href="{{@$_ktnq[$ktnq['order']] ? asset('public/productionPlan/files/'.$_ktnq[$ktnq['order']]):''}}" target="_blank">{{@$_ktnq[$ktnq['order']]}}</a></td>
                                                @php
                                                    $temp--;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                                @switch($filter['bp'])
                                    @case(1)
                                        @if ($productionPlanKTNQ)
                                            @foreach ($productionPlanKTNQ as $ktnq)
                                                @if ($_ktnq)
                                                    @if ($ktnq['data_type'] == 1)
                                                        <td>{{@$_ktnq[$ktnq['order']]}}</td>
                                                    @endif
                                                @else
                                                    <td></td>
                                                @endif
                                            @endforeach
                                        @endif
                                        @break
                                    @case(2)
                                        @if ($temp)
                                            @for ($i = 0; $i < $temp; $i++)
                                                <td></td>
                                            @endfor
                                        @endif
                                        @if ($productionPlanKTTM)
                                            @foreach ($productionPlanKTTM as $kttm)
                                                @if ($_kttm)
                                                    @if ($kttm['data_type'] == 1)
                                                        <td>{{ !is_string(@$_kttm[$kttm['order']]) ? date('d/m/Y',strtotime(@$_kttm[$kttm['order']]->date)) : $_kttm[$kttm['order']]}}</td>
                                                    @else
                                                        <td><a href="{{@$_kttm[$kttm['order']] ? asset('public/productionPlan/files/'.$_kttm[$kttm['order']]):''}}" target="_blank">{{@$_kttm[$kttm['order']]}}</a></td>
                                                    @endif
                                                @else
                                                    <td></td>
                                                @endif
                                            @endforeach
                                        @endif
                                        @break
                                    @default
                                        @if ($productionPlanKTNQ)
                                            @foreach ($productionPlanKTNQ as $ktnq)
                                                @if ($_ktnq)
                                                    @if ($ktnq['data_type'] == 1)
                                                        <td>{{@$_ktnq[$ktnq['order']]}}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td></td>
                                                @endif
                                            @endforeach
                                        @endif
                                @endswitch
                                @if ($description->loai_hang == '2')
                                    <td style="background-color:blueviolet;color:white">{{$item->code }}</td>
                                @elseif($description->loai_hang == '1')
                                    <td style="background-color: yellow">{{$item->code }}</td>
                                @else
                                    <td>{{$item->code }}</td>
                                @endif
                                <td {{($description->hangdangdo_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_lrap}}</td>
                                <td {{($description->hangdangdo_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_kttm}}</td>
                                <td {{($description->hangdangdo_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_ktnq}}</td>
                                {{-- @switch($filter['bp'])
                                    @case(1)
                                        <td {{($description->hangdangdo_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_lrap}}</td>
                                        <td {{($description->hangdangdo_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_kttm}}</td>
                                        <td {{($description->hangdangdo_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_ktnq}}</td>
                                        @break
                                    @case(2)
                                        <td {{($description->hangdangdo_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_kttm}}</td>
                                        <td {{($description->hangdangdo_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_kttm}}</td>
                                        <td {{($description->hangdangdo_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_kttm}}</td>
                                        @break
                                    @default
                                        <td {{($description->hangdangdo_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_lrap}}</td>
                                        <td {{($description->hangdangdo_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_kttm}}</td>
                                        <td {{($description->hangdangdo_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_ktnq}}</td>
                                @endswitch --}}
                                <td>{{$description->thu2_le}}</td>
                                <td>{{$description->thu3_air}}</td>
                                <td>{{$description->thu4_le}}</td>
                                <td>{{$description->thu5_le}}</td>
                                <td>{{$description->thu5_air}}</td>
                                <td>{{$description->thu6_air}}</td>
                                <td>{{$description->thu6_sea_osaka}}</td>
                                <td>{{$description->thu6_sea_tokyo}}</td>
                                <td {{($description->hangxuatchualam_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_lrap}}</td>
                                <td {{($description->hangxuatchualam_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_kttm}}</td>
                                <td {{($description->hangxuatchualam_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_ktnq}}</td>
                                {{-- <td {{($description->soluongdaycatchualam_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_lrap}}</td>
                                <td {{($description->soluongdaycatchualam_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_kttm}}</td>
                                <td {{($description->soluongdaycatchualam_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_ktnq}}</td> --}}
                                <td >{{$description->mau}}</td>
                                <td>{{$description->so_luong}}</td>
                                <td >{{$description->gia_cong}}</td>
                                {{-- @switch($filter['bp'])
                                    @case(1)
                                       <td {{($description->hangxuatchualam_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_ktnq}}</td>
                                       <td {{($description->soluongdaycatchualam_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_ktnq}}</td>
                                       @break
                                    @case(2)
                                        <td {{($description->hangxuatchualam_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_kttm}}</td>
                                        <td {{($description->soluongdaycatchualam_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_kttm}}</td>
                                        @break
                                    @default
                                        <td {{($description->hangxuatchualam_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_ktnq}}</td>
                                        <td {{($description->soluongdaycatchualam_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_ktnq}}</td>
                                    @break
                                @endswitch --}}
                                {{-- <td {{($description->hangxuatchualam_dap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_dap}}</td>
                                <td {{($description->hangxuatchualam_cam <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_cam}}</td>
                                <td {{($description->hangxuatchualam_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_lrap}}</td>
                                <td {{($description->hangxuatchualam_buredo <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_buredo}}</td>
                                <td {{($description->hangxuatchualam_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_kttm}}</td>
                                <td {{($description->hangxuatchualam_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_ktnq}}</td>

                                <td {{($description->soluongdaycatchualam_dap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_dap}}</td>
                                <td {{($description->soluongdaycatchualam_cam <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_cam}}</td>
                                <td {{($description->soluongdaycatchualam_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_lrap}}</td>
                                <td {{($description->soluongdaycatchualam_buredo <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_buredo}}</td>
                                <td {{($description->soluongdaycatchualam_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_kttm}}</td>
                                <td {{($description->soluongdaycatchualam_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_ktnq}}</td> --}}
                            </tr>
                             @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <span class="record-total">Tổng: {{ $lists->total() }} bản ghi</span>
                </div>
                <div class="col-sm-6 text-center">
                    <div class="pagination-panel">
                        {{ $lists->appends(Request::all())->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
                <div class="col-sm-3 text-right">
                    <span>
                        Hiển thị
                        <select name="per_page" class="form-control" style="display: inline;width: auto;" data-target="#form_lists">
                            @php $list = [5, 10, 20, 50, 100, 200]; @endphp
                            @foreach ($list as $num)
                                <option value="{{ $num }}" {{ $num == $per_page ? 'selected' : '' }}>{{ $num }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
    </script>
@endsection
@section('styles')
    <style>
        .cards {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .cards_item {
            display: flex;
            padding: 1rem;
            width: 100%;
        }
        .card {
            background-color: white;
            border-radius: 0.25rem;
            box-shadow: 0 15px 30px -14px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            cursor: pointer;
            width: 320px;
            height: 130px;
        }
        .card:hover {
            border: 2px solid blueviolet;
        }
        .card_content {
            padding: 1rem;
            background: linear-gradient(to bottom left, #EF8D9C 40%, #FFC39E 100%);
            height: 100%;
        }
        .card_title {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: capitalize;
            margin: 0px;
        }
        .card_title a {
            color: inherit;
        }
        .card_text {
            color: #ffffff;
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 1.25rem;
            font-weight: 400;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
            padding: 3px !important;
        }
    </style>
@endsection

