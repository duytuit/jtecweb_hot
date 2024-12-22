@extends('backend.layouts.master')
@php
    use App\Helpers\ArrayHelper;
@endphp
@section('title')
    @include('backend.pages.checkdevices.partials.title')
@endsection
<style type="text/css">
    .info-person-device div{
        line-height: 1;
    }
    .device_client{
        width: 350px;
    }
</style>
@section('admin-content')
    @include('backend.pages.checkdevices.partials.header-breadcrumbs')
        <div class="container-fluid">
            @include('backend.pages.checkdevices.partials.top-show')
            @include('backend.layouts.partials.messages')
            <!-- START #show list device -->
            <input type="hidden" id="joinRoom" value="checkdevices">
            <input type="hidden" id="username" value="bDZPBxBmr9jhwUZy">
            <div class="col-sm-12" style="padding: 5px;">
                <div style="text-align: center">
                    <h3>Giám sát thiết bị</h3>
                    <div><span>Tổng số thiết bị Online: </span><strong class="total" style="font-size: 32px;color: red"></strong></div>
                </div>
                <div style="border: 1px solid;min-height: 135px;display: flex">
                    <div class="list_devices" style="display: flex;flex-wrap:wrap">
                        {{-- <div class="info-person-device" data-ip_client="" style="display:flex;padding: 5px;">
                            <div>
                                <img style="object-fit: contain;object-position: top center;" src="{{ '../../public/assets/images/pages/tablet.png' }}" width="97">
                            </div>
                            <div style="padding-left: 3px;">
                                <div>IP:</div>
                                <div class="ip_client"></div>
                                <div>Device:</div>
                                <div class="device_client">Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36</div>
                                <div>IN:</div>
                                <div class="status_client"></div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
    <script src="{{ asset('public/assets/frontend/js/socket.io.min.js') }}"></script>
    <script>
        var socket;
        var socketId='';

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

        socket.on("disconnect", () => {
           console.log('disconnect');
           console.log(socket.id);
        });

        socket.on('userJoined', function(data) {
            console.log(`${data.username} joined room: ${data.room}`);
        });

        socket.on('chat', function(msg) {
            info = JSON.parse(msg.message);
            let check = 0;
            $('.info-person-device').each(function(i, obj) {
               let ip_client = $(this).find('.ip_client').text();
               if(info.ip_client == ip_client){
                  check = 1;
               }
            });

            if(check == 0){
                $(".list_devices").append('<div class="info-person-device" data-ip_client="'+info.ip_client+'" style="display:flex;padding: 5px;">'+
                                            '<div>'+
                                            '    <img style="object-fit: contain;object-position: top center;" src="{{"../../public/assets/images/pages/tablet.png" }}" width="97">'+
                                            '</div>'+
                                            '<div style="padding-left: 3px;">'+
                                            '    <div>IP:</div>'+
                                            '    <div class="ip_client">'+info.ip_client+'</div>'+
                                            '    <div>Device:</div>'+
                                            '    <div class="device_client">'+info.device+'</div>'+
                                            '    <div>IN:</div>'+
                                            '    <div class="status_client">'+info.current_time+'</div>'+
                                            '</div>'+
                                           '</div>');
            }
            if(info.status == 'out'){
                $('div[data-ip_client="'+info.ip_client+'"]').remove();
            }
            var count = $(".info-person-device").length;
            $(".total").text(count);
        });
        var room = document.getElementById('joinRoom').value;
        var username = document.getElementById('username').value;
        socket.emit('joinRoom', { room, username });
        socket.on('warning', function(data) {
          if(data.status == false){
            socket.emit('createRoom', { room });
          }
        });
    </script>
@endsection
