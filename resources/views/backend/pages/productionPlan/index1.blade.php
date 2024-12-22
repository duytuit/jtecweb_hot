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
                        <button class="btn btn-warning btn-block">Tìm kiếm</button>
                    </div>
                    <div class="col-sm-4">
                        <a href="{{ route('admin.productionPlans.asyncProductionPlan') }}" class="btn btn-success">Đồng bộ lúc: {{@$asyncProductionPlan ? json_decode(@$asyncProductionPlan)->time:''}}</a>
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
                                @foreach ($productionPlanKTNQ as $item)
                                  <th rowspan="2">{{$item['column_name']}}</th>
                                @endforeach
                            @endif
                            <th rowspan="2">Mã hàng 1</th>
                            <th rowspan="2">Mã hàng 2</th>
                            <th colspan="5">仕掛品-HÀNG DỞ DANG</th>
                            <th rowspan="2">在庫数Tồn Kho</th>
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
                            <th rowspan="2">Mẫu</th>
                            <th colspan="6">SỐ LƯỢNG HÀNG XUẤT CHƯA LÀM</th>
                            <th rowspan="2">単価</th>
                            <th rowspan="2">回路数</th>
                            <th colspan="6">SỐ LƯỢNG DÂY CẮT CHƯA LÀM</th>
                            <th colspan="3">TỔNG LỊCH XUẤT</th>
                            <th colspan="12">SỐ LƯỢNG HÀNG XUẤT CHƯA LÀM</th>
                            <th rowspan="2">Chỉnh sửa lần cuối</th>
                        </tr>
                        <tr>
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
                            <th>検査 KTNQ</th>
                            <th>Dập</th>
                            <th>挿入 Cắm</th>
                            <th>組付 Lráp</th>
                            <th>Buredo</th>
                            <th>検査 KTTM</th>
                            <th>検査 KTNQ</th>
                            <th>単価</th>
                            <th>回路数</th>
                            <th>Tổng SL Xuất</th>
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
                            <th>検査 KTNQ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $index=> $item)
                        @php
                            $description = json_decode($item->description);
                            $_ktnq = (array)json_decode($item->ktnq);
                        @endphp
                             @if (@$description)
                             <tr>
                                <td>{{ $index+1 }}</td>
                                <td>
                                    <a href="{{ route('admin.productionPlans.edit', ['id' => $item->id]) }}" class="btn btn-sm btn-primary"><span class="fa fa-edit"></span></a>
                                </td>
                                @if ($productionPlanKTNQ)
                                    @foreach ($productionPlanKTNQ as $ktnq)
                                        @if ($_ktnq)
                                           @if ($ktnq['data_type'] == 1)
                                               <td>{{@$_ktnq[$ktnq['order']]}}</td>
                                           @else
                                               <td><a href="{{@$_ktnq[$ktnq['order']] ? asset('public/productionPlan/files/'.$_ktnq[$ktnq['order']]):''}}" target="_blank">{{@$_ktnq[$ktnq['order']]}}</a></td>
                                           @endif
                                        @else
                                           <td></td>
                                        @endif
                                    @endforeach
                                @endif
                                @if ($description->loai_hang == 'Mã con')
                                    <td style="background-color:blueviolet;color:white">{{$item->code }}</td>
                                @elseif($description->loai_hang == 'Hàng mới')
                                    <td style="background-color: yellow">{{$item->code }}</td>
                                @else
                                    <td>{{$item->code }}</td>
                                @endif
                                {{-- <td>{{$item->lot_no}}</td> --}}
                                <td {{($description->hangdangdo_cam <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_cam }}</td>
                                <td {{($description->hangdangdo_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_lrap}}</td>
                                <td {{($description->hangdangdo_buredo <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_buredo}}</td>
                                <td {{($description->hangdangdo_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_kttm}}</td>
                                <td {{($description->hangdangdo_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_ktnq}}</td>
                                {{-- <td>{{$description->ton_kho}}</td> --}}
                                <td>{{$description->thu2_le}}</td>
                                <td>{{$description->thu3_air}}</td>
                                <td>{{$description->thu4_le}}</td>
                                <td>{{$description->thu5_le}}</td>
                                <td>{{$description->thu5_air}}</td>
                                <td>{{$description->thu6_air}}</td>
                                <td>{{$description->thu6_sea_osaka}}</td>
                                <td>{{$description->thu6_sea_tokyo}}</td>
                                {{-- <td>{{$description->mau}}</td> --}}
                                <td {{($description->hangxuatchualam_dap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_dap}}</td>
                                <td {{($description->hangxuatchualam_cam <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_cam}}</td>
                                <td {{($description->hangxuatchualam_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_lrap}}</td>
                                <td {{($description->hangxuatchualam_buredo <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_buredo}}</td>
                                <td {{($description->hangxuatchualam_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_kttm}}</td>
                                <td {{($description->hangxuatchualam_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_ktnq}}</td>
                                {{-- <td>{{number_format($description->don_gia,0,'.',',')}}</td>
                                <td>{{number_format($description->so_luong,0,'.',',')}}</td> --}}
                                <td {{($description->soluongdaycatchualam_dap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_dap}}</td>
                                <td {{($description->soluongdaycatchualam_cam <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_cam}}</td>
                                <td {{($description->soluongdaycatchualam_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_lrap}}</td>
                                <td {{($description->soluongdaycatchualam_buredo <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_buredo}}</td>
                                <td {{($description->soluongdaycatchualam_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_kttm}}</td>
                                <td {{($description->soluongdaycatchualam_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluongdaycatchualam_ktnq}}</td>
                                {{-- <td>{{number_format($description->tonglichxuat_dongia,0,'.',',')}}</td>
                                <td>{{number_format($description->tonglichxuat_soluong,0,'.',',')}}</td>
                                <td>{{number_format($description->tonglichxuat_soluongxuat,0,'.',',')}}</td>
                                <td {{($description->soluonghangxuat_dap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluonghangxuat_dap}}</td>
                                <td {{($description->soluonghangxuat_cam <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluonghangxuat_cam}}</td>
                                <td {{($description->soluonghangxuat_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluonghangxuat_lrap}}</td>
                                <td {{($description->soluonghangxuat_buredo <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluonghangxuat_buredo}}</td>
                                <td {{($description->soluonghangxuat_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluonghangxuat_kttm}}</td>
                                <td {{($description->soluonghangxuat_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluonghangxuat_ktnq}}</td>
                                <td {{($description->soluonghangxuatdaycat_dap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluonghangxuatdaycat_dap}}</td>
                                <td {{($description->soluonghangxuatdaycat_cam <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluonghangxuatdaycat_cam}}</td>
                                <td {{($description->soluonghangxuatdaycat_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluonghangxuatdaycat_lrap}}</td>
                                <td {{($description->soluonghangxuatdaycat_buredo <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluonghangxuatdaycat_buredo}}</td>
                                <td {{($description->soluonghangxuatdaycat_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluonghangxuatdaycat_kttm}}</td>
                                <td {{($description->soluonghangxuatdaycat_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->soluonghangxuatdaycat_ktnq}}</td>
                                <td></td> --}}
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

    </style>
@endsection

