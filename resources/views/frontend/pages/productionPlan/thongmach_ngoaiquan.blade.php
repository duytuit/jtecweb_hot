<div class="form-group">
    @extends('frontend.layouts.master_no_container_header')
</div>
<div class="container-fluid">
    <form id="form-search-advance" action="{{ route('frontend.productionPlans.viewProductionPlan') }}" method="get" class="hidden">
        <div id="search-advance" class="search-advance">
            <div class="row form-group space-5">
                <div class="col-sm-1">
                    <a class="btn btn-success" href="{{ route('frontend.productionPlans.viewProductionPlan') }}">làm mới</a>
                </div>
                <div class="col-sm-1">
                    <input type="text" id="searchcode" name="keyword" value="{{@$filter['keyword']}}" placeholder="SCAN QR" class="form-control" />
                </div>
                <div class="col-sm-2">
                    <select name="bp" class="form-control" style="width: 100%;"  data-live-search="true" onchange="this.form.submit()">
                        <option value="1" {{ @$filter['bp'] == '1' ? 'selected' : '' }}>Kiểm tra ngoại quan</option>
                        <option value="2" {{ @$filter['bp'] == '2' ? 'selected' : '' }}>Kiểm tra thông mạch</option>
                    </select>
                    <noscript><input type="submit" value="Submit"></noscript>
                </div>
                <div class="col-sm-1">
                    <select name="product_code" class="form-control" style="width: 100%;"  data-live-search="true" onchange="this.form.submit()">
                        <option value="">Loại hàng</option>
                        <option value="1" {{ @$filter['product_code'] == '1' ? 'selected' : '' }}>Hàng mới</option>
                        <option value="2" {{ @$filter['product_code'] == '2' ? 'selected' : '' }}>Hàng X</option>
                        <option value="3" {{ @$filter['product_code'] == '3' ? 'selected' : '' }}>Hàng thường</option>
                    </select>
                    <noscript><input type="submit" value="Submit"></noscript>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-warning btn-block">Tìm kiếm</button>
                </div>
                <div class="col-sm-2">
                    <select name="history_plan" class="form-control" style="width: 100%;" data-live-search="true" onchange="this.form.submit()">
                        <option value="">Lịch sử đồng bộ</option>
                        @if ($history_plan)
                            @foreach ($history_plan as $_key => $item)
                                @php
                                    $_item = explode('ke_hoach_san_xuat_',$item)[1];
                                @endphp
                                <option value="ke_hoach_san_xuat_{{$_item}}" {{@$filter['history_plan'] == 'ke_hoach_san_xuat_'.$_item ? 'selected' : ''}}>{{$_item}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('frontend.productionPlans.asyncViewProductionPlan',Request::all()) }}" class="btn btn-success">Kế Hoạch: {{@$asyncProductionPlan ? json_decode(@$asyncProductionPlan)->time:''}}</a>
                </div>
                <div class="col-sm-2">
                    @switch($filter['bp'])
                        @case(1)
                            <a href="{{ route('frontend.productionPlans.asyncKTNQ') }}" class="btn btn-success">KTNQ: {{@$update_AsyncKTNQ ? json_decode(@$update_AsyncKTNQ)->time:''}}</a>
                            @break
                        @case(2)
                            <a href="{{ route('frontend.productionPlans.asyncKTTM') }}" class="btn btn-success">KTTM: {{@$update_AsyncKTTM ? json_decode(@$update_AsyncKTTM)->time:''}}</a>
                            @break
                        @default
                            <a href="{{ route('frontend.productionPlans.asyncKTNQ') }}" class="btn btn-success">KTNQ: {{@$update_AsyncKTNQ ? json_decode(@$update_AsyncKTNQ)->time:''}}</a>
                    @endswitch
                </div>
            </div>
        </div>
        @if (@$productionPlanHeaderWeekday)
            @foreach ($productionPlanHeaderWeekday as $_key => $item)
               <input type="hidden" name="filter_{{$_key}}" value="{{@$filter[$_key]}}">
            @endforeach
        @endif
        <input type="hidden" name="filter_9" value="{{@$filter[9]}}">
        <input type="hidden" name="filter_10" value="{{@$filter[10]}}">
        <input type="hidden" name="filter_11" value="{{@$filter[11]}}">
        <input type="hidden" name="filter_12" value="{{@$filter[12]}}">
        <input type="hidden" name="filter_13" value="{{@$filter[13]}}">
        <input type="hidden" name="filter_14" value="{{@$filter[14]}}">
    </form><!-- END #form-search-advance -->
        <div class="table-responsive product-table">
            <table class="table table-bordered table-hover" id="exams_table">
                <thead>
                    <tr>
                        <th rowspan="2">Update</th>
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
                        <th rowspan="2">在庫数Tồn Kho</th>
                        @if (@$productionPlanHeaderWeekday)
                            @foreach ($productionPlanHeaderWeekday as $_key => $item)
                               <th rowspan="2">
                                   <div>{{$item}}</div>
                                   <a href="javascript:;" class="btn_filter_status" data-key="{{$_key}}" data-status="{{@$filter[$_key]}}" data-target="#form-search-advance"> <i  class="btn btn-outline-secondary fa fa-filter" {{@$filter[$_key] == 2 ? 'style=color:red':'' }}></i></a>
                              </th>
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
                        <th>
                            <div>組付 Lráp</div>
                            <a href="javascript:;" class="btn_filter_status" data-key="9" data-status="{{@$filter[9]}}" data-target="#form-search-advance" {{@$filter[9] == 2 ?'style=background-color: red;':'' }}> <i  class="btn btn-outline-secondary fa fa-filter"></i></a>
                        </th>
                        <th>
                            <div>検査 KTTM</div>
                            <a href="javascript:;" class="btn_filter_status" data-key="10" data-status="{{@$filter[10]}}" data-target="#form-search-advance"> <i  class="btn btn-outline-secondary fa fa-filter"></i></a>
                        </th>
                        <th>
                            <div>検査 KTNQ</div>
                            <a href="javascript:;" class="btn_filter_status" data-key="11" data-status="{{@$filter[11]}}" data-target="#form-search-advance"> <i  class="btn btn-outline-secondary fa fa-filter"></i></a>
                        </th>
                        <th>
                            <div>組付 Lráp</div>
                            <a href="javascript:;" class="btn_filter_status" data-key="12" data-status="{{@$filter[12]}}" data-target="#form-search-advance"> <i  class="btn btn-outline-secondary fa fa-filter"></i></a>
                        </th>
                        <th>
                            <div>検査 KTTM</div>
                            <a href="javascript:;" class="btn_filter_status" data-key="13" data-status="{{@$filter[13]}}" data-target="#form-search-advance"> <i  class="btn btn-outline-secondary fa fa-filter" {{@$filter[13] == 2 ? 'style=color:red':'' }}></i></a>
                        </th>
                        <th>
                            <div>検査 KTNQ</div>
                            <a href="javascript:;" class="btn_filter_status" data-key="14" data-status="{{@$filter[14]}}" data-target="#form-search-advance"> <i  class="btn btn-outline-secondary fa fa-filter" {{@$filter[14] == 2 ? 'style=color:red':'' }}></i></a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lists as $index=> $item)
                    @php
                        $temp = @$check;
                        $description = json_decode($item->description);
                        $_ktnq = (array)json_decode($item->ktnq);
                        $_kttm = (array)json_decode($item->kttm);
                        $__code = explode('-',$item->code)[0];
                        if(is_numeric($item->code[0])){
                            $_code ='//192.168.207.6/JtecData/品番別制作資料関係/1.図面・チェックシート等/dau '.$item->code[0].'/'.$__code;
                        }else {
                            $_code ='//192.168.207.6/JtecData/品番別制作資料関係/1.図面・チェックシート等/dau chu/'.$__code;
                        }

                    @endphp
                         @if (@$description)
                         <tr>
                            <td>{{$item->updated_at->format('H:i d-m-y')}}</td>
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
                                <td style="background-color:blueviolet;color:white"><a target="_blank" href="{{ route('frontend.productionPlans.file_info',['folder_path'=>$_code])}}">{{$item->code}}</a></td>
                            @elseif($description->loai_hang == '1')
                                <td style="background-color: yellow"><a target="_blank" href="{{ route('frontend.productionPlans.file_info',['folder_path'=>$_code])}}">{{$item->code}}</a></td>
                            @else
                            <td><a target="_blank" href="{{ route('frontend.productionPlans.file_info',['folder_path'=>$_code])}}">{{$item->code}}</a></td>
                            @endif
                            <td {{($description->hangdangdo_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_lrap}}</td>
                            <td {{($description->hangdangdo_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_kttm}}</td>
                            <td {{($description->hangdangdo_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangdangdo_ktnq}}</td>
                            <td>{{$description->ton_kho}}</td>
                            <td>{{$description->thu2_le}}</td>
                            <td>{{$description->thu3_air}}</td>
                            <td>{{$description->thu4_le}}</td>
                            <td>{{$description->thu5_le}}</td>
                            <td>{{$description->thu5_air}}</td>
                            <td>{{$description->thu6_air}}</td>
                            <td>{{$description->thu6_sea_osaka}}</td>
                            <td>{{$description->thu6_sea_tokyo}}</td>
                            <td>{{@$description->thu6_sea_tokyo_1}}</td>
                            <td>{{@$description->thu6_sea_tokyo_2}}</td>
                            <td {{($description->hangxuatchualam_lrap <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_lrap}}</td>
                            <td {{($description->hangxuatchualam_kttm <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_kttm}}</td>
                            <td {{($description->hangxuatchualam_ktnq <0) ? 'style=background-color:#ff60d5;color:white':''}}>{{$description->hangxuatchualam_ktnq}}</td>
                            <td >{{$description->mau}}</td>
                            <td >{{$description->so_luong}}</td>
                            <td >{{$description->gia_cong}}</td>
                        </tr>
                         @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <form id="form_lists" action="{{ route('frontend.productionPlans.action') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="" />
            <input type="hidden" name="status" value="" />
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
<style>
    body{
        padding:0 !important;
    }
    .table-bordered td, .table-bordered th {
        border: 1px solid #dee2e6;
        padding: 3px !important;
        font-size: 12px;
    }
</style>
@section('scripts')
    <script>
        $('#searchcode').focus();
        $('#searchcode').codeScanner({
            maxEntryTime: 100, // milliseconds
            minEntryChars: 1, // characters
            loading:false,
            nextElement:'',
            onScan: function ($element, code) {
                    $('#form-search-advance').submit();
            }
        });
        $('select[name="per_page"]').change(function () {
            var target = $(this).data('target');
            var $form = $(target);

            $('input[name=method]', $form).val('per_page');

            $form.submit();
        });

        // .btn_filter_status

        $('a.btn_filter_status').click(function () {
            var target = $(this).data('target');
            var $form = $(target);
            var key = $(this).data('key');
            var status = $(this).data('status');
            if(status == 1 || status == null){
                status=2;
            }else{
                status=1;
            }
            $('input[name=filter_'+key+']', $form).val(status);
            $form.submit();
        });
    </script>
@endsection

