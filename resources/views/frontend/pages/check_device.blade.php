@extends('frontend.layouts.master')

@section('title')
    {{ config('app.name') }} | {{ config('app.description') }}
@endsection

@section('main-content')
    <main class="main">
        <!-- Page Content -->
        <input type="hidden" id="joinRoom" value="checkdevices">
        <input type="hidden" id="username" value="{{$uuid}}">
        <input type="hidden" id="ip_client" value="{{$ip_client}}">
        <input type="hidden" id="device" value="{{$device}}">
        <input type="hidden" id="current_time">
        <div class="container text-center">
            <h3>THEO DÕI THIẾT BỊ IPAD</h3>
            <div class="display-date">
                <span id="day">Ngày</span>,
                <span id="daynum">00</span>
                <span id="month">Tháng</span>
                <span id="year">0000</span>
            </div>
            <div class="display-time" style="display: inline;"></div>
            <div>IP: <strong>{{$ip_client}}</strong></div>
            <div>Device: <strong>{{$device}}</strong></div>
            <a target="_blank" href="192.168.207.6/JtecData/品番別制作資料関係/1.図面・チェックシート等/dau 0/0324200025" class="btn btn-success"></a>
            {{-- <img src="data:image/png;base64,{!!DNS1D::getBarcodePNG('4', 'C39+',3,33)!!}" alt="barcode"   /> --}}
            {{-- <div>
                <div style="display: flex;gap: 0.2em;justify-content: center;">
                    <div>
                        <a href="javascript:;" class="btn text-light btn-danger" onclick="modal_form()">Xuất hàng</a>
                    </div>
                    <div >
                        <a class="btn btn-primary text-light expand-collapse-icon collapse-toggle"></a>
                    </div>
                </div>
                <div class="collapse" id="collapseExample">
                    Some placeholder content for the collapse component. This panel is hidden by default but revealed when the user activates the relevant trigger.
                </div>
            </div> --}}
        </div>
    </main>
@endsection
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
        background-color: white;
        transition: 0.3s ease-in-out all;
        border-radius: 0.03em;
        top: 16px;
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
         var socket;
         var socketId='';
         var room = document.getElementById('joinRoom').value;
         var username = document.getElementById('username').value;
         var ip_client = document.getElementById('ip_client').value;
         var device = document.getElementById('device').value;
         const displayTime = document.querySelector(".display-time");
         var _time = new Date();
         $('#current_time').val( _time.toLocaleTimeString()+ ' '+ _time.getDate()+'-'+(_time.getMonth()+1)+'-'+_time.getFullYear());
         var current_time = _time.toLocaleTimeString()+ ' '+ _time.getDate()+'-'+(_time.getMonth()+1)+'-'+_time.getFullYear();
         var count=0;
         var background='#09e3ef';
         console.log("abc"+_time.toLocaleTimeString("en-US", { hour12: false }));

        function check_device(status){
            $.ajax({
                        url: "{{ route('check_device_store') }}",
                        method: 'POST',
                        data: {
                            room:room,
                            username:username,
                            ip_client:ip_client,
                            device:device,
                            current_time:current_time,
                            status:status
                        },
                        success: function(data) {
                            console.log(data);
                        }
                    });
        }
        check_device('in');
        socket = io("http://192.168.207.6:8091", {
            cors: {
                origin: "http://192.168.207.6:8088",
                methods: ["GET", "POST"]
            },
            transports : ['websocket']
        });

        socket.on('connect', function() {
           console.log('connected');
           socketId += socket.id;
           console.log(socketId);

        });

        socket.emit('joinRoom', { room, username });

        socket.on('warning', function(data) {
          if(data.status == false){
            socket.emit('createRoom', { room });
          }
        });

        window.addEventListener('beforeunload', function (event) {
            let time = new Date();
            current_time = time.toLocaleTimeString()+ ' '+ time.getDate()+'-'+(time.getMonth()+1)+'-'+time.getFullYear();
            socket.emit('chat', {room:'checkdevices', sender: username, message: JSON.stringify({
                room:room,
                username:username,
                ip_client:ip_client,
                device:device,
                current_time:current_time,
                status:'out'
            }) });
        });

        // Time
        function showTime() {
            let time = new Date();
            displayTime.innerText = time.toLocaleTimeString();
            setTimeout(()=>{
                showTime();
                count+=1;
                if(count === 2){
                    socket.emit('chat', {room:'checkdevices', sender: username, message: JSON.stringify({
                        room:room,
                        username:username,
                        ip_client:ip_client,
                        device:device,
                        current_time:current_time,
                        status:'in'
                    }) });
                    count=0
                    if(background == '#09e3ef'){
                        background='transparent';
                        displayTime.style.cssText = 'display: inline;padding:3px;background:'+background;
                    }else{
                        background='#09e3ef';
                        displayTime.style.cssText = 'display: inline;padding:3px;background:'+background;
                    }
                }
            }, 1000);
        }
        showTime();
        // Date
        function updateDate() {
            let today = new Date();

            // return number
            let dayName = today.getDay(),
                dayNum = today.getDate(),
                month = today.getMonth(),
                year = today.getFullYear();

            const months = [
                "Tháng 1",
                "Tháng 2",
                "Tháng 3",
                "Tháng 4",
                "Tháng 5",
                "Tháng 6",
                "Tháng 7",
                "Tháng 8",
                "Tháng 9",
                "Tháng 10",
                "Tháng 11",
                "Tháng 12",
            ];
            const dayWeek = [
                "Chủ Nhật",
                "Thứ 2",
                "Thứ 3",
                "Thứ 4",
                "Thứ 5",
                "Thứ 6",
                "Thứ 7",
            ];
            // value -> ID of the html element
            const IDCollection = ["day", "daynum", "month", "year"];
            // return value array with number as a index
            const val = [dayWeek[dayName], dayNum, months[month], year];
            for (let i = 0; i < IDCollection.length; i++) {
                document.getElementById(IDCollection[i]).firstChild.nodeValue = val[i];
            }
        }
        $('#testttt').click(function(){
            window.open("file://\\192.168.207.6\JtecData\品番別制作資料関係\1.図面・チェックシート等\dau 0\0324200025");
        })
        updateDate();
        $(document).ready(function() {
            $('.collapse-toggle').click(function(){
                $(this).toggleClass('collapsed');
                $('#collapseExample').collapse('toggle')
            })
        })
    </script>
@endsection
