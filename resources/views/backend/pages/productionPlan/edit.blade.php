@extends('backend.layouts.master')

@section('title')
    @include('backend.pages.productionPlan.partials.title')
@endsection

@section('admin-content')
  @include('backend.pages.productionPlan.partials.header-breadcrumbs')
  <div class="container-fluid">
        @include('backend.layouts.partials.messages')
        <div class="create-page">
            @switch($type)
                @case(1)
                    <form action="{{ route('admin.productionPlans.updateKTNQ', ['id' => $productionPlan->id]) }}" method="POST" enctype="multipart/form-data"
                        data-parsley-validate data-parsley-focus="first">
                    @break
                @case(2)
                    <form action="{{ route('admin.productionPlans.updateKTTM', ['id' => $productionPlan->id]) }}" method="POST" enctype="multipart/form-data"
                        data-parsley-validate data-parsley-focus="first">
                    @break
                @default
                    <form action="{{ route('admin.productionPlans.updateKTNQ', ['id' => $productionPlan->id]) }}" method="POST" enctype="multipart/form-data"
                        data-parsley-validate data-parsley-focus="first">
            @endswitch
                @csrf
                <div class="form-body">
                    <div class="card-body">
                        <input type="hidden" name="productionPlanId" id="productionPlanId" value="{{ $productionPlan->id }}">
                            <div class="row w-100">
                                <label class="control-label">Mã sản phẩm</label>
                                <div class="row w-100">
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="code" name="code" value="{{ $productionPlan->code }}" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        @if (Auth::user()->id == 1)
                                           <button type="button" class="btn btn-primary add_item"><i class="fa fa-plus"></i></button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row w-100 list_items" id="tablecontents">
                                @if (@$data_productionPlan)
                                    @foreach ($data_productionPlan as $key => $item)
                                        <div class="row w-100 item_clone">
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    <label class="control-label">Thứ tự</label>
                                                    <input type="text" class="form-control order" value="{{@$item['order']}}" name="productionPlan[order][{{$key}}]" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="control-label" for="column_name">Tên cột</label>
                                                    <input type="text" class="form-control" id="column_name" value="{{@$item['column_name']}}" name="productionPlan[column_name][{{$key}}]">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label class="control-label" for="data_type">Kiểu dữ liệu</label>
                                                    <select name="productionPlan[data_type][{{$key}}]" id="data_type" onchange="changeDataType(this)" class="form-control" style="width: 100%;">
                                                        @if (@$item['data_type'] ==1)
                                                             <option value="1" selected>Dạng văn bản</option>
                                                        @elseif(@$item['data_type'] ==2)
                                                             <option value="2" selected>Dạng ảnh</option>
                                                        @else
                                                             <option value="3" selected>Dạng tệp tin</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 item_input_option">
                                                @switch($type)
                                                    @case(1)
                                                            @if (@$productionPlan->ktnq)
                                                                @php
                                                                    $ktnq = (array)json_decode($productionPlan->ktnq);
                                                                @endphp
                                                                @if (@$item['data_type'] ==1)
                                                                    <div class="form-group">
                                                                        <label class="control-label" >Dữ liệu</label>
                                                                        <input type="text" class="form-control data_row" name="productionPlan[data_row][{{$key}}]" value="{{$ktnq[$item['order']]}}">
                                                                    </div>
                                                                @else
                                                                    <div class="form-group">
                                                                        <label class="control-label" >Dữ liệu</label>
                                                                        <input type="file" class="form-control data_row" name="productionPlan[data_row][{{$key}}]" value="{{$ktnq[$item['order']]}}">
                                                                        <input type="hidden" name="productionPlan[data_row_temp][{{$key}}]" value="{{$ktnq[$item['order']]}}">
                                                                        <div><a href="{{asset('public/productionPlan/files/'.$ktnq[$item['order']])}}" target="_blank">{{$ktnq[$item['order']]}}</a></div>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                @if (@$item['data_type'] ==1)
                                                                    <div class="form-group">
                                                                        <label class="control-label" >Dữ liệu</label>
                                                                        <input type="text" class="form-control data_row" name="productionPlan[data_row][{{$key}}]">
                                                                    </div>
                                                                @else
                                                                    <div class="form-group">
                                                                        <label class="control-label" >Dữ liệu</label>
                                                                        <input type="file" class="form-control data_row" name="productionPlan[data_row][{{$key}}]">
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @break
                                                    @case(2)
                                                        @if (@$productionPlan->kttm)
                                                            @php
                                                                $kttm = (array)json_decode($productionPlan->kttm);
                                                            @endphp
                                                            @if (@$item['data_type'] ==1)
                                                                <div class="form-group">
                                                                    <label class="control-label" >Dữ liệu</label>
                                                                    <input type="text" class="form-control data_row" name="productionPlan[data_row][{{$key}}]" value="{{$kttm[$item['order']]}}">
                                                                </div>
                                                            @else
                                                                <div class="form-group">
                                                                    <label class="control-label" >Dữ liệu</label>
                                                                    <input type="file" class="form-control data_row" name="productionPlan[data_row][{{$key}}]" value="{{$kttm[$item['order']]}}">
                                                                    <input type="hidden" name="productionPlan[data_row_temp][{{$key}}]" value="{{$kttm[$item['order']]}}">
                                                                    <div><a href="{{asset('public/productionPlan/files/'.$kttm[$item['order']])}}" target="_blank">{{$kttm[$item['order']]}}</a></div>
                                                                </div>
                                                            @endif
                                                        @else
                                                            @if (@$item['data_type'] ==1)
                                                                <div class="form-group">
                                                                    <label class="control-label" >Dữ liệu</label>
                                                                    <input type="text" class="form-control data_row" name="productionPlan[data_row][{{$key}}]">
                                                                </div>
                                                            @else
                                                                <div class="form-group">
                                                                    <label class="control-label" >Dữ liệu</label>
                                                                    <input type="file" class="form-control data_row" name="productionPlan[data_row][{{$key}}]">
                                                                </div>
                                                            @endif
                                                        @endif
                                                        @break
                                                    @default
                                                        @if (@$productionPlan->ktnq)
                                                            @php
                                                                $ktnq = (array)json_decode($productionPlan->ktnq);
                                                            @endphp
                                                            @if (@$item['data_type'] ==1)
                                                                <div class="form-group">
                                                                    <label class="control-label" >Dữ liệu</label>
                                                                    <input type="text" class="form-control data_row" name="productionPlan[data_row][{{$key}}]" value="{{$ktnq[$item['order']]}}">
                                                                </div>
                                                            @else
                                                                <div class="form-group">
                                                                    <label class="control-label" >Dữ liệu</label>
                                                                    <input type="file" class="form-control data_row" name="productionPlan[data_row][{{$key}}]" value="{{$ktnq[$item['order']]}}">
                                                                    <input type="hidden" name="productionPlan[data_row_temp][{{$key}}]" value="{{$ktnq[$item['order']]}}">
                                                                    <div><a href="{{asset('public/productionPlan/files/'.$ktnq[$item['order']])}}" target="_blank">{{$ktnq[$item['order']]}}</a></div>
                                                                </div>
                                                            @endif
                                                        @else
                                                            @if (@$item['data_type'] ==1)
                                                                <div class="form-group">
                                                                    <label class="control-label" >Dữ liệu</label>
                                                                    <input type="text" class="form-control data_row" name="productionPlan[data_row][{{$key}}]">
                                                                </div>
                                                            @else
                                                                <div class="form-group">
                                                                    <label class="control-label" >Dữ liệu</label>
                                                                    <input type="file" class="form-control data_row" name="productionPlan[data_row][{{$key}}]">
                                                                </div>
                                                            @endif
                                                        @endif
                                                @endswitch
                                            </div>
                                            @if (Auth::user()->id == 1)
                                                <div class="col-sm-1">
                                                    <button type="button" class="btn btn-danger" style="margin-top: 34px;" onclick="removeItem(this)">
                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="row fixed-bottom">
                                <div class="col-md-6 form-actions mx-auto">
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Lưu</button>
                                    <a href="{{ url()->previous() }}" class="btn btn-dark">Quay lại</a>
                                </div>
                            </div>
                    </div>
                </div>
            </form>
        </div>
        <div style="display: none" >
            <div class="item_clone_add">
                <div class="row w-100 item_clone">
                    <div class="col-sm-1">
                        <div class="form-group">
                            <label class="control-label">Thứ tự</label>
                            <input type="text" class="form-control order" data-parsley-required-message="Thứ tự là bắt buộc" name="productionPlan[order][0]" required readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="column_name">Tên cột</label>
                            <input type="text" class="form-control" id="column_name" data-parsley-required-message="Tên cột là bắt buộc" name="productionPlan[column_name][0]" required>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label" for="data_type">Kiểu dữ liệu</label>
                            <select name="productionPlan[data_type][0]" id="data_type" onchange="changeDataType(this)" class="form-control" style="width: 100%;">
                                <option value="1" >Dạng văn bản</option>
                                <option value="2" >Dạng ảnh</option>
                                <option value="3" >Dạng tệp tin</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3 item_input_option">
                        <div class="form-group">
                            <label class="control-label" >Dữ liệu</label>
                            <input type="text" class="form-control data_row" data-parsley-required-message="Dữ liệu là bắt buộc" name="productionPlan[data_row][0]" required>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-danger" style="margin-top: 34px;" onclick="removeItem(this)">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: none">
            <div class="item_input_text">
                <div class="form-group">
                    <label class="control-label" >Dữ liệu</label>
                    <input type="text" class="form-control data_row" data-parsley-required-message="Dữ liệu là bắt buộc" name="productionPlan[data_row][0]" required>
                </div>
            </div>
            <div class="item_input_image">
                <div class="form-group">
                    <label class="control-label">Dữ liệu</label>
                    <input type="file" accept="image/png, image/jpg, image/jpeg, image/webp" class="form-control data_row" data-parsley-required-message="Dữ liệu là bắt buộc" name="productionPlan[data_row][0]" required>
                </div>
            </div>
            <div class="item_input_file">
                <div class="form-group">
                    <label class="control-label" >Dữ liệu</label>
                    <input type="file" accept="application/pdf,application/vnd.ms-excel" class="form-control data_row" data-parsley-required-message="Dữ liệu là bắt buộc" name="productionPlan[data_row][0]" required>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(".add_item").click(function(e) {
            var avails = $(".item_clone_add");
            var clone = avails.find('.item_clone').clone();
            let max_item = $(".list_items").find(".item_clone").length;
            let html =  '<div class="row w-100 item_clone">'+
                        '<div class="col-sm-1">'+
                        '    <div class="form-group">'+
                        '        <label class="control-label">Thứ tự</label>'+
                        '        <input type="text" class="form-control order" value="'+(max_item+1)+'" data-parsley-required-message="Thứ tự là bắt buộc" name="productionPlan[order][0]" required>'+
                        '    </div>'+
                        '</div>'+
                        '<div class="col-sm-3">'+
                        '    <div class="form-group">'+
                        '        <label class="control-label" for="column_name">Tên cột</label>'+
                        '        <input type="text" class="form-control" id="column_name" data-parsley-required-message="Tên cột là bắt buộc" name="productionPlan[column_name][0]" required>'+
                        '    </div>'+
                        '</div>'+
                        '<div class="col-sm-2">'+
                        '    <div class="form-group">'+
                        '        <label class="control-label" for="data_type">Kiểu dữ liệu</label>'+
                        '        <select name="productionPlan[data_type][0]" id="data_type" onchange="changeDataType(this)" class="form-control" style="width: 100%;">'+
                        '            <option value="1" >Dạng văn bản</option>'+
                        '            <option value="2" >Dạng ảnh</option>'+
                        '            <option value="3" >Dạng tệp tin</option>'+
                        '        </select>'+
                        '    </div>'+
                        '</div>'+
                        '<div class="col-sm-3 item_input_option">'+
                        '    <div class="form-group">'+
                        '        <label class="control-label" >Dữ liệu</label>'+
                        '        <input type="text" class="form-control data_row" data-parsley-required-message="Dữ liệu là bắt buộc" name="productionPlan[data_row][0]" required>'+
                        '    </div>'+
                        '</div>'+
                        '<div class="col-sm-1">'+
                        '    <button type="button" class="btn btn-danger" style="margin-top: 34px;" onclick="removeItem(this)">'+
                        '        <i class="fa fa-minus" aria-hidden="true"></i>'+
                        '    </button>'+
                        '</div>'+
                        '</div>';
            $(".list_items").append(html).find(".item_clone").each(function(key, value) {
                $(this).find("input").each(function(){
                   this.name = this.name.replace(/\d+/, key);
                //    if($(this).hasClass("order")){
                //      $(this).val(key+1);
                //     }
                });
                $(this).find("select").each(function(){
                   this.name = this.name.replace(/\d+/, key);
                   console.log(this.name);
                });
            });
            e.preventDefault();
        });
        function removeItem(that)
        {
            $(that).closest(".item_clone").remove();
            $(".list_items").find(".item_clone").each(function(key, value) {
                $(this).find("input").each(function() {
                    this.name = this.name.replace(/\d+/, key);
                    // if($(this).hasClass("order")){
                    //    $(this).val(key+1);
                    // }
                });
                $(this).find("select").each(function(){
                   this.name = this.name.replace(/\d+/, key);
                   console.log(this.name);
                });
            });
        }
        function changeDataType(event){
            if($(event).val() == 1){
                var avails = $(".item_input_text");
                var clone = avails.eq(0).clone();
                $(event).closest(".item_clone").find(".item_input_option").html(clone);

            }else if($(event).val() == 2){
                var avails = $(".item_input_image");
                var clone = avails.eq(0).clone();
                $(event).closest(".item_clone").find(".item_input_option").html(clone);
            }else{
                var avails = $(".item_input_file");
                var clone = avails.eq(0).clone();
                $(event).closest(".item_clone").find(".item_input_option").html(clone);
            }
            $(".list_items").find(".item_clone").each(function(key, value) {
                $(this).find("input").each(function() {
                    this.name = this.name.replace(/\d+/, key);
                    console.log(this.name);
                    // if($(this).hasClass("order")){
                    //  $(this).val(key+1);
                    // }
                });
                $(this).find("select").each(function(){
                   this.name = this.name.replace(/\d+/, key);
                   console.log(this.name);
                });
            });
        }
    $(document).ready(function () {
        $( "#tablecontents" ).sortable({
            items: ".item_clone",
            cursor: 'move',
            opacity: 0.6,
            disabled: false,
            update: function() {
               // sendOrderToServer();
            }
        });
    });
    </script>
@endsection
