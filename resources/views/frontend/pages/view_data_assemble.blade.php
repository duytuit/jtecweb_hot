@extends('frontend.layouts.master_no_header')

@section('title')
    {{ config('app.name') }} | {{ config('app.description') }}
@endsection

@section('main-content')
    <div class="menu_sidebar show">
        <div class="menu_togle">
            <a href="javascript:;" class="has-arrow btn_togle"></a>
        </div>
        <form id="formPost" action="{{ url('/viewDataAssemble') }}" method="get" style="margin: 0;">
            <div style="padding: 2px;">
                <div>
                   <table>
                        <tr>
                            <td colspan="5">
                                <input type="text" name="code" id="findCode" class="form-control form-control-sm" style="height: calc(1em + .2rem + 0px);padding: .25rem .2rem;" placeholder="Scan mã lot">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" align="center">
                                @if (@$url && $working_hour)
                                    <button type="button" class="btn btn-primary btn-sm status" onclick="start(this)">Bắt đầu</button>
                                @endif
                            </td>
                            <td colspan="2" align="center">
                                <div class="display-date"></div>
                                <div class="display-time"></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                Mã sản phẩm
                            </td>
                            <td align="center">
                                Giờ công
                            </td>
                            <td align="center">
                                Số lượng
                            </td>
                            <td align="center">
                                Thời gian bắt đầu
                            </td>
                            <td align="center">
                                Dự kiến kết thúc
                            </td>
                        </tr>
                        <tr >
                            <td align="center">
                                @if (@$url)
                                    {{@$code}}
                                    <div>{{@$lot_no ? "($lot_no)":''}}</div>
                                @else
                                     <div style="color: red">{{$message}}</div>
                                @endif
                            </td>
                            <td align="center">
                                @if (@$url)
                                    {{@$working_hour}}
                                @endif
                            </td>
                            <td align="center">
                                 <input type="number" id="quantity" style="width:40px" min="1" max="100" value="1">
                            </td>
                            <td align="center">
                                @if (@$url)
                                    <div id="set_start_time"></div>
                                @endif
                            </td>
                            <td  align="center">
                                @if (@$url)
                                    <div id="set_end_time"></div>
                                @endif
                            </td>
                        </tr>
                   </table>
                </div>
            </div>
        </form>
    </div>
    {{-- <div style="margin-top: -115px;">
        <object id="fileBase64" data="{{@$fileBase64}}" width="100%" style="height:136vh;margin-left: -12px;" type="application/pdf"></object>
    </div> --}}
    <div style="margin-top: -115px;">
       <iframe src="{{@$url}}" width="100%" style="height:125vh"></iframe>
       {{-- <iframe src="public\data_laprap\YA13E01075P1-02-v1.pdf" width="100%" style="height:125vh"></iframe> --}}
    </div>
    {{-- <embed src="{{@$fileBase64}}" width="100%" height="height:100vh"> --}}
    <input type="hidden" id="working_hour" value="{{@$working_hour}}">
@endsection
 <style>
    .btn-sm {
        font-size: 0.4rem !important;
        padding: 0.1rem !important;
    }
    table, th, td {
        border: 1px solid #007bff;
        border-collapse: collapse;
        font-size: 0.4rem !important;
        line-height: 1;
    }
    .has-arrow{
        width: 100%;
        height: 100%;
        display: block;
    }
    .menu_togle .has-arrow::after {
        position: absolute;
        content: "";
        width: 7px;
        height: 7px;
        border-width: 1px 0 0 1px;
        border-style: solid;
        border-color: black;
        margin-left: 10px;
        -webkit-transform: rotate(-45deg) translate(0, -50%);
        -ms-transform: rotate(-45deg) translate(0, -50%);
        -o-transform: rotate(-45deg) translate(0, -50%);
        transform: rotate(-45deg) translate(0, -50%);
        -webkit-transform-origin: top;
        -ms-transform-origin: top;
        -o-transform-origin: top;
        transform-origin: top;
        top: 9px;
        right: 4px;
        -webkit-transition: all 0.3s ease-out;
        -o-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
    }
    .menu_togle>.has-arrow.active::after {
        right: 7px;
        -webkit-transform: rotate(135deg) translate(0, -50%);
        -ms-transform: rotate(135deg) translate(0, -50%);
        -o-transform: rotate(135deg) translate(0, -50%);
        transform: rotate(135deg) translate(0, -50%);
    }
    .menu_sidebar{
        background: aqua;
        position: fixed;
        top: 0;
        left:-177px;
        width: 177px;
        z-index: 100;
        transition: 0.3s ease-in;
        border: 1px solid #007bff;
    }
    .menu_togle{
        position: absolute;
        top: -1px;
        left: 176px;
        background-color: aqua;
        height: 20px;
        width: 20px;
        border: 1px solid #007bff;
    }
    .show{
        left:0;
    }
     /* Initially hide the navbar */
     .hidden-navbar {
            top: -60px; /* Adjust based on the height of your navbar */
        }
    body::-webkit-scrollbar {
        -webkit-appearance: none;
    }
    body::-webkit-scrollbar:vertical {
        width: 15px
    }
    body::-webkit-scrollbar:horizontal {
        height: 15px;
    }
    body::-webkit-scrollbar-thumb {
        border-radius: 8px;
        border: 2px solid white;
        background-color: rgba(0, 0, 0, .5);
    }
    body::-webkit-scrollbar-track {
        background-color: #fff;
        border-radius: 8px;
    }
 </style>
@section('scripts')
    <script>

        const displayTime = document.querySelector(".display-time");
        const displayDate = document.querySelector(".display-date");
        var start_date = null;
        // Time
        function showTime() {
          let time = new Date();
          displayTime.innerText = time.toLocaleTimeString();
          setTimeout(showTime, 1000);
        }
        showTime();
        // Date
        function updateDate() {
            let today = new Date();
            // return number
            const dayWeek = [
                "CN",
                "Thứ 2",
                "Thứ 3",
                "Thứ 4",
                "Thứ 5",
                "Thứ 6",
                "Thứ 7",
            ];
            let dayName = today.getDay(),
                dayNum = today.getDate(),
                month = today.getMonth(),
                year = today.getFullYear();
                displayDate.innerText = dayWeek[dayName]+', '+dayNum+'-'+(month+1)+'-'+year;
        }
        updateDate();
        $('#findCode').focus();
        $(document).hover(function(){
            $('#findCode').focus();
        })
        $('#findCode').codeScanner({
            maxEntryTime: 200, // milliseconds
            minEntryChars: 6, // characters
            onScan: function ($element, code) {
                 $('#formPost').submit()
            }
        });
        $(document).ready(function(){
            $('#findCode').keypress(function (e) {
                if (e.which === 13) {
                    $('#formPost').submit()
                }
            })
        })
        $('.btn_togle').click( function() {
            $('#findCode').focus();
            $('.menu_sidebar').toggleClass('show');
            $('.menu_sidebar .menu_togle').find('.has-arrow').toggleClass('active');
        });
        window.onload = function() {
            document.body.scrollTop = document.body.scrollHeight / 2;
            document.documentElement.scrollTop = document.documentElement.scrollHeight / 2;
        };
        function start(event){
            console.log($('#working_hour').val());
            if($(event).text() == 'Bắt đầu'){
                if($('#working_hour').val()){
                    $(event).text('Kết thúc')
                    start_date = new Date();
                    var newDateObj = new Date();
                    $('#set_start_time').text(start_date.toLocaleTimeString());
                    $('#set_end_time').text(new Date(newDateObj.setTime(start_date.getTime() + (parseFloat($('#working_hour').val()) * 60000) * parseFloat($('#quantity').val())) ).toLocaleTimeString() );
                }
            }else{
                if(start_date){
                    $(event).text('Bắt đầu')
                    end_date = new Date();
                    var diff = end_date.getTime() - start_date.getTime();
                    let seconds  = diff / 1000;
                    port_form(end_date,seconds,$('#quantity').val());
                    swal("THỜI GIAN HOÀN THÀNH CỦA BẠN LÀ: " +toHHMMSS(seconds));
                }
            }
        }
        function toHHMMSS(secs){
            var sec_num = parseInt(secs, 10)
            var hours   = Math.floor(sec_num / 3600)
            var minutes = Math.floor(sec_num / 60) % 60
            var seconds = sec_num % 60

            return [hours,minutes,seconds]
                .map(v => v < 10 ? "0" + v : v)
                .filter((v,i) => v !== "00" || i > 0)
                .join(":")
        }
        async function port_form(end_date,seconds,quantity){
            let param = {
                    code: "{{$code}}",
                    lot_no:"{{$lot_no}}",
                    index:"{{$index}}",
                    working_hour:"{{$working_hour}}",
                    start_time:  start_date.getFullYear()+'-'+("0"+(start_date.getMonth()+1)).slice(-2)+'-'+("0" + start_date.getDate()).slice(-2)+' '+start_date.toLocaleTimeString("en-US", { hour12: false }),
                    end_time: end_date.getFullYear()+'-'+("0"+(end_date.getMonth()+1)).slice(-2)+'-'+("0" + end_date.getDate()).slice(-2)+' '+end_date.toLocaleTimeString("en-US", { hour12: false }),
                    complete_time:toHHMMSS(seconds),
                    quantity:quantity
            };
            await call_api('POST',"{{ route('assembleStore') }}",param);
        }
        function call_api(method,url,param) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url:url,
                    method: method,
                    data: param,
                    success: function (response) {
                        resolve(response)
                    },
                    error: function(error){
                        reject(error)
                    }
                })
            })
        }
    </script>
@endsection
