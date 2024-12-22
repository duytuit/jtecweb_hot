<div class="form-group">
    @extends('frontend.layouts.master_no_container_header')
</div>
<div class="container-fluid">
    <form id="form-search-advance" action="{{ route('frontend.productionPlans.index') }}" method="get" class="hidden">
        <div id="search-advance" class="search-advance">
            <div class="row form-group space-5">
                <div class="col-sm-2">
                    <input type="text" id="searchcode" name="keyword" value="{{@$filter['keyword']}}" placeholder="SCAN QR" class="form-control" />
                </div>
                <div class="col-sm-4">
                    <a href="{{ route('frontend.productionPlans.asyncProductionPlan') }}" class="btn btn-success">Đồng bộ
                        lúc: {{@$asyncProductionPlanDetail ? json_decode(@$asyncProductionPlanDetail)->time:''}}</a>
                </div>
            </div>
        </div>
    </form><!-- END #form-search-advance -->
    <form id="form_lists" action="{{ route('frontend.productionPlans.action') }}" method="post">
        @csrf
        <input type="hidden" name="method" value="" />
        <input type="hidden" name="status" value="" />
        <div class="table-responsive product-table">
            <table class="table table-bordered" id="exams_table">
                <thead>
                    <tr>
                        <th rowspan="2" width="20">TT</th>
                        <th rowspan="2" width="180">Mã hàng</th>
                        <th rowspan="2" width="20">∆</th>
                        <th rowspan="2" width="30">Lot No.DENNO CẮT+DẬP</th>
                        <th rowspan="2" width="30">Lot No.DENNO CẮM</th>
                        <th rowspan="2" width="30">Lot No.DENNO LẮP RÁP</th>
                        <th rowspan="2" width="100">SLSX (PCS)</th>
                        <th rowspan="2" width="100">Mã EDP</th>
                        <th rowspan="2" width="180">Ngày sản xuất</th>
                        <th rowspan="2" width="180">Ngày hoàn thành</th>
                        <th colspan="2" width="180">Lịch Xuất</th>
                        <th rowspan="2" >Lần SCAN cuối</th>
                    </tr>
                    <tr>
                        <th width="180">Toa</th>
                        <th width="180">KH lẻ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lists as $index=> $item)
                    @php
                        $plan_lot_no = json_decode($item->plan_lot_no);
                    @endphp
                         @if (@$plan_lot_no)
                         <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{$item->code }}</td>
                            <td>{{$plan_lot_no->a}}</td>
                            <td>{{$plan_lot_no->lot_no_denno_cat_dap}}</td>
                            <td>{{$plan_lot_no->lot_no_denno_cam}}</td>
                            <td>{{$plan_lot_no->lot_no_denno_lrap}}</td>
                            <td>{{$plan_lot_no->soluongsanxuat_pcs}}</td>
                            <td>{{$plan_lot_no->ma_edp}}</td>
                            <td>{{@$plan_lot_no->ngaysanxuat->date ? date('d-m-Y',strtotime($plan_lot_no->ngaysanxuat->date)) : ''}}</td>
                            <td>{{$plan_lot_no->ngayhoanthanh}}</td>
                            <td>{{@$plan_lot_no->toa->date ? date('d-m-Y',strtotime($plan_lot_no->toa->date)) : ''}}</td>
                            <td>{{@$plan_lot_no->khachhangle->date ? date('d-m-Y',strtotime($plan_lot_no->khachhangle->date)) : ''}}</td>
                            <td>
                                @if (@$plan_lot_no->scan)
                                     @php
                                         $scan = json_decode($plan_lot_no->scan);
                                         $scan = array_reverse($scan);
                                     @endphp
                                      <div class="information-export">
                                        @foreach ($scan as $index_1 => $item_1 )
                                            @if ($index_1 == 0)
                                                <div >
                                                    <a class="btn btn-outline-success expand-collapse-icon collapse-toggle" onclick="infoStatus(this)">{{ $item_1}}</a>
                                                </div>
                                            @else
                                            <div class="collapse">
                                                {{ $item_1}}
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>
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
                    <select name="per_page" class="form-control" style="display: inline;width: auto;"
                        data-target="#form_lists">
                        @php $list = [5, 10, 20, 50, 100, 200]; @endphp
                        @foreach ($list as $num)
                        <option value="{{ $num }}" {{ $num==$per_page ? 'selected' : '' }}>{{ $num }}</option>
                        @endforeach
                    </select>
                </span>
            </div>
        </div>
    </form>
</div>
<style>
    .expand-collapse-icon {
      font-size: 200px;
      width: 100%;
      height: 100%;
      position: relative;
      display: inline-block;
  }

  .expand-collapse-icon::before, .expand-collapse-icon::after {
      content: "";
      position: absolute;
      width: 1em;
      height: .16em;
      top: calc( (1em / 2 ) - .08em );
      background-color: rgb(74, 83, 71);
      transition: 0.3s ease-in-out all;
      border-radius: 0.03em;
      top: 13px;
      left: 5px;
  }

  .expand-collapse-icon::after {
      transform: rotate(90deg);
  }

  .collapsed.expand-collapse-icon::after {
      transform: rotate(180deg);
  }


  .collapsed.expand-collapse-icon::before {
      transform: rotate(90deg) scale(0);
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
        $(document).ready(function () {
            $('select[name="per_page"]').change(function () {
            var target = $(this).data('target');
            var $form = $(target);
            $('input[name=method]', $form).val('per_page');
                $form.submit();
            });
        })
        function infoStatus(event){
            $(event).toggleClass('collapsed');
            $(event).closest(".information-export").find('.collapse').collapse('toggle')
        }
    </script>
@endsection

