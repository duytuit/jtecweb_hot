@extends('frontend.layouts.master')

@section('title')
    {{ config('app.name') }} | {{ config('app.description') }}
@endsection

@section('main-content')
    <main class="main">
      <div class="container">
          <h1>Chương trình điều khiển đèn</h1>
          <div>Chế độ:</div>
        <label for="normal">
              Bình thường
            <input type="radio" name="mode" id="normal" checked value="0">
        </label>
        <label for="flicker">
              Nhấp nháy
            <input type="radio" name="mode" id="flicker" value="1">
        </label>
        <div class="form-group"> <button id="den1" class="btn btn-secondary">Đèn 1</button></div>
        <div class="form-group"> <button id="den2" class="btn btn-secondary">Đèn 2</button></div>
        <div class="form-group"> <button id="den3" class="btn btn-secondary">Đèn 3</button></div>
        <div class="form-group"> <button id="den4" class="btn btn-secondary">Đèn 4</button></div>
        <ul id="messages"></ul>
      </div>
    </main>
@endsection
@section('styles')
 <style>
        ul { list-style-type: none; margin: 0; padding: 0; }
        li { padding: 8px; margin-bottom: 10px; background-color: #f3f3f3; }
        input { padding: 8px; width: calc(100% - 16px); }
 </style>
@endsection
@section('scripts')
<script>
    var den1=false;
    var den2=false;
    var den3=false;
    var den4=false;
    // Connect to the WebSocket server
    const ws = new WebSocket('wss://192.168.207.6:5007/ws'); // Replace with your WebSocket server URL
    //const ws = new WebSocket('ws://192.168.217.76:5000/ws'); // Replace with your WebSocket server URL

    // Log connection status
    ws.onopen = () => {
      console.log('Connected to WebSocket server');
    };

    ws.onclose = () => {
      console.log('Disconnected from WebSocket server');
    };

    ws.onerror = (error) => {
      console.error('WebSocket error:', error);
    };

    // Listen for messages from the server
    ws.onmessage = (event) => {
      const messagesList = document.getElementById('messages');
      const newMessage = document.createElement('li');
      newMessage.textContent = `Server: ${event.data}`;
      $("#messages").html(newMessage);
    };
    function test_json(){
        let json = {
            Event:15,
            Chanel:"dencanhbao_cd_dap",
            Status:2,
            MessageText:"den3",
            Mode:2
        }
        console.log(json);
        ws.send(JSON.stringify(json));

    }
    $("#den1").click(function(e){
        e.preventDefault();
        let name="den1";
        den1=!den1;
        if(den1){
            $(this).removeClass("btn-secondary");
            $(this).addClass("btn-primary");
        }else{
            $(this).removeClass("btn-primary");
            $(this).addClass("btn-secondary");
        }
        change_status(name,den1)
    })
    $("#den2").click(function(e){
        e.preventDefault();
        let name="den2";
        den2=!den2;
        if(den2){
            $(this).removeClass("btn-secondary");
            $(this).addClass("btn-primary");
        }else{
            $(this).removeClass("btn-primary");
            $(this).addClass("btn-secondary");
        }
        change_status(name,den2)
    })
    $("#den3").click(function(e){
        e.preventDefault();
        let name="den3";
        den3=!den3;
        if(den3){
            $(this).removeClass("btn-secondary");
            $(this).addClass("btn-primary");
        }else{
            $(this).removeClass("btn-primary");
            $(this).addClass("btn-secondary");
        }
        change_status(name,den3)
    })
    $("#den4").click(function(e){
        e.preventDefault();
        let name="den4";
        den4=!den4;
        if(den4){
            $(this).removeClass("btn-secondary");
            $(this).addClass("btn-primary");
        }else{
            $(this).removeClass("btn-primary");
            $(this).addClass("btn-secondary");
        }
        change_status(name,den4)
    })
    function change_status(lamp,status){
        let mode = $('input[name=mode]:checked').val();
        let json = {
            Event:15,
            Chanel:"dencanhbao_cd_dap",
            Status:status?1:0,
            MessageText:lamp,
            Mode:mode
        }
        console.log(json);
        ws.send(JSON.stringify(json));
    }
    </script>
@endsection
