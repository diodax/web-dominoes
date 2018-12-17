@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Members</div>
                    <div id="members_output" class="pre-scrollable list-group list-group-flush" style="height: 600px">
                        @foreach($members as $member)
                            <div class="list-group-item">
                                <div class="media">
                                    <div class="media-body d-none d-lg-block ml-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{$member->user->username}}</h6>
                                            @switch($member->status)
                                                @case(constant('App\ChatMemberStatus::ONLINE'))
                                                    <span class="badge badge-pill badge-success">Online</span>
                                                    <div>
                                                        <a href="{{route('game.create', [ 'first_user' => auth()->id(), 'second_user' => $member->user_id ])}}" class="btn btn-sm float-right">Challenge</a>
                                                    </div>
                                                    @break
                                                @case(constant('App\ChatMemberStatus::PLAYING'))
                                                    <span class="badge badge-pill badge-danger">Playing</span>
                                                    <div>
                                                        <button class="btn btn-sm float-right" disabled>Challenge</button>
                                                    </div>
                                                    @break
                                                @default
                                                    <span class="badge badge-pill badge-secondary">Offline</span>
                                                    <div>
                                                        <button class="btn btn-sm float-right" disabled>Challenge</button>
                                                    </div>
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$chat->title}}<span id="total_client" class="float-right"></span></div>
                    <div class="card-body">
                        <div id="chat_output" class="pre-scrollable" style="height: 600px">
                            @foreach($chat->messages as $message)
                                @if($message->user_id == auth()->id())
                                    <span class="text-success"><b>{{$message->user_id}}. {{$message->user->username}}
                                            :</b> {{$message->body}} <span
                                                class="text-warning float-right">{{date('Y-m-d h:i a', strtotime($message->created_at))}}</span></span>
                                    <br><br>
                                @else
                                    <span class="text-info"><b>{{$message->user_id}}. {{$message->user->username}}
                                            :</b> {{$message->body}} <span
                                                class="text-warning float-right">{{date('Y-m-d h:i a', strtotime($message->created_at))}}</span></span>
                                    <br><br>
                                @endif
                            @endforeach
                        </div>
                        <input id="chat_input" class="form-control" placeholder="Write Message and Press Enter"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">

        $('document').ready(function () {
            $("#chat_output").animate({scrollTop: $('#chat_output').prop("scrollHeight")}, 1000); // Scroll the chat output div
        });

        // Websocket
        let ws = new WebSocket("ws://localhost:8090");
        ws.onopen = function (e) {
            // Connect to websocket
            console.log('Connected to websocket');
            ws.send(
                JSON.stringify({
                    'type': 'open',
                    'chat_id': '{{$chat->id}}',
                    'user_id': '{{auth()->id()}}'
                })
            );

            // Bind onkeyup event after connection
            $('#chat_input').on('keyup', function (e) {
                if (e.keyCode === 13 && !e.shiftKey) {
                    let chat_msg = $(this).val();
                    ws.send(
                        JSON.stringify({
                            'type': 'chat',
                            'user_id': '{{auth()->id()}}',
                            'chat_id': '{{$chat->id}}',
                            'user_name': '{{auth()->user()->username}}',
                            'chat_msg': chat_msg
                        })
                    );
                    $(this).val('');
                    console.log('{{auth()->id()}} sent ' + chat_msg);
                }
            });
        };
        ws.onerror = function (e) {
            // Error handling
            console.log(e);
            alert('Check if WebSocket server is running!');
        };
        ws.onclose = function(e) {
            console.log(e);
            ws.send(
                JSON.stringify({
                    'type': 'close',
                    'chat_id': '{{$chat->id}}',
                    'user_id': '{{auth()->id()}}'
                })
            );

            alert('Check if WebSocket server is running!');
        };
        ws.onmessage = function (e) {
            let json = JSON.parse(e.data);
            switch (json.type) {
                case 'chat':
                    $('#chat_output').append(json.msg); // Append the new message received
                    $("#chat_output").animate({scrollTop: $('#chat_output').prop("scrollHeight")}, 1000); // Scroll the chat output div
                    //console.log("Received " + json.msg);
                    break;

                case 'socket':
                    $('#total_client').html(json.msg);
                    refreshMembers(json.members);
                    console.log(json.members);
                    console.log("Received socket message " + json.msg);
                    break;
            }
        };

        function refreshMembers(members = []) {
            $('#members_output').html("");

            members.forEach(function (item, index) {

                var badge = '';
                switch (item.status.toString()) {
                    case "{{ constant('App\ChatMemberStatus::ONLINE') }}":
                        badge = `<span class="badge badge-pill badge-success">Online</span>
                                <div><a href="/game/create?first_user={{auth()->id()}}&second_user=${item.user_id}" class="btn btn-sm float-right">Challenge</a></div>`;
                        break;
                    case "{{ constant('App\ChatMemberStatus::PLAYING') }}":
                        badge = `<span class="badge badge-pill badge-danger">Playing</span>
                                <div><button class="btn btn-sm float-right" disabled>Challenge</button></div>`;
                        break;
                    default:
                        badge = `<span class="badge badge-pill badge-secondary">Offline</span>
                                <div><button class="btn btn-sm float-right" disabled>Challenge</button></div>`;
                }

                $('#members_output').append(
                    `<div class="list-group-item">
                        <div class="media">
                            <div class="media-body d-none d-lg-block ml-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">${item.user.username}</h6>
                                    ${badge}
                                </div>
                            </div>
                        </div>
                    </div>`);
            });
        }
    </script>
@endsection
