@extends('frontend.layouts.master')

@section('title')
    {{ config('app.name') }} | {{ config('app.description') }}
@endsection

@section('main-content')
    <main class="main">
        <div class="container text-center">
            <ul id="messages"></ul>
            <input id="input" autocomplete="off" placeholder="Type a message..." /><button id="send">Send</button>
            <br><br>
            <input id="room" autocomplete="off" placeholder="Room name" /><button id="createRoom">Create Room</button>
            <input id="joinRoom" autocomplete="off" placeholder="Join Room" /><button id="join">Join Room</button>
            <br><br>
            <input id="username" autocomplete="off" placeholder="Username" /><button id="register">Register</button>
            <input id="loginUsername" autocomplete="off" placeholder="Login Username" /><button id="login">Login</button>
            <input id="password" type="password" placeholder="Password" />
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
        var socket;
        var token;

        document.getElementById('login').addEventListener('click', function() {
            var username = document.getElementById('loginUsername').value;
            var password = document.getElementById('password').value;

            fetch('http://127.0.0.1:8091/auth/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password })
            })
            .then(response => response.json())
            .then(response => {
                token = response.access_token;
                console.log(response.access_token);
                socket = io("http://127.0.0.1:8091", {
                            auth: { token },
                            cors: {
                                origin: "http://192.168.207.6:8088",
                                methods: ["GET", "POST"],
                            },
                            transports : ['websocket']
                         });

                socket.on('connect', function() {
                    console.log('connected');
                    console.log(token);
                });
                socket.on('chat', function(msg) {
                    console.log('data1');
                    var item = document.createElement('li');
                    item.textContent = msg.sender + ': ' + msg.message;
                    console.log( msg.message);
                    document.getElementById('messages').appendChild(item);
                    window.scrollTo(0, document.body.scrollHeight);
                });

                socket.on('roomCreated', function(room) {
                    console.log('Room created:', room);
                });

                socket.on('userJoined', function(data) {
                    console.log(`${data.username} joined room: ${data.room}`);
                });
            });
        });

        document.getElementById('register').addEventListener('click', function() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;

            fetch('http://127.0.0.1:8091/auth/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password })
            })
            .then(response => response.json())
            .then(data => {
                token = data.access_token;
                socket = io("http://127.0.0.1:8091", {
                            auth: { token },
                            cors: {
                                origin: "http://192.168.207.6:8088",
                                methods: ["GET", "POST"],
                                // allowedHeaders: ["my-custom-header"],
                                // credentials: true
                            },
                            transports : ['websocket']
                         });

                socket.on('connect', function() {
                    console.log('connected');
                });

                socket.on('chat', function(msg) {
                    console.log('data2');
                    var item = document.createElement('li');
                    item.textContent = msg.sender + ': ' + msg.message;
                    document.getElementById('messages').appendChild(item);
                    window.scrollTo(0, document.body.scrollHeight);
                });

                socket.on('roomCreated', function(room) {
                    console.log('Room created:', room);
                });

                socket.on('userJoined', function(data) {
                    console.log(`${data.username} joined room: ${data.room}`);
                });
            });
        });

        document.getElementById('send').addEventListener('click', function() {
            var input = document.getElementById('input');
            if (input.value) {
                socket.emit('chat', {room:'checkdevices', sender: 'You', message: input.value });
                input.value = '';
            }
        });

        document.getElementById('createRoom').addEventListener('click', function() {
            var room = document.getElementById('room').value;
            socket.emit('createRoom', { room });
        });

        document.getElementById('join').addEventListener('click', function() {
            var room = document.getElementById('joinRoom').value;
            var username = document.getElementById('username').value;
            socket.emit('joinRoom', { room, username });
        });
    </script>
@endsection
