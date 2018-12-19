@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div id="alerts-section" class="col-md-12">
                <!-- List of ongoing games will be refreshed here -->
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Members</div>
                    <div id="members_output" class="pre-scrollable list-group list-group-flush" style="height: 600px">
                        @foreach($members as $member)
                            @php($isMe = (Auth::user()->username === $member->user->username))
                            <div class="list-group-item">
                                <div class="media">
                                    <div class="media-body d-none d-lg-block ml-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{$member->user->username}}</h6>
                                            @if ($isMe)
                                                (You)
                                            @else
                                                <div>
                                                    <a href="{{route('game.create', [ 'first_user' => auth()->id(), 'second_user' => $member->user_id ])}}" class="btn btn-outline-info btn-sm float-right">Challenge</a>
                                                </div>
                                            @endif
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
            $("#chat_output").animate({scrollTop: $('#chat_output').prop("scrollHeight")}, 10000); // Scroll the chat output div

            // Set a recurring call to check ongoing games
            if (window.gamesTimerId === undefined) {
                window.gamesTimerId = setInterval(checkOngoingGames, 2000);
            }
        });

        function checkOngoingGames() {
            // This action checks to see if there are any ongoing games associated with the user in session
            $.ajax({
                url: '{{ route('game.ongoing') }}',
                type: 'GET',
                dataType: 'json',
                contentType: 'application/json',
                success: function (response) {
                    $('#alerts-section').html("");
                    var currentMember = @json(Auth::user()->username);

                    response.forEach(function (item, index) {
                        var opponentUsername = "";
                        if (currentMember === item.player1Username) {
                            opponentUsername = item.player2Username;
                        } else {
                            opponentUsername = item.player1Username;
                        }

                        $('#alerts-section').append(
                            `<div class="alert alert-info" role="alert" style="overflow: auto; line-height: 36px;">
                                <strong>Heads up!</strong> The member <i>${opponentUsername}</i> challenged you to play a game.
                                <a href="/game/${item.gameId}" class="btn btn-outline-info float-right">Start Playing</a>
                            </div>`);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }

        // Websocket
        let hostUrl = location.origin.replace(/^http/, 'ws') + ":8090";
        let ws = new WebSocket(hostUrl);
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
            var currentMember = @json(Auth::user()->username);

            members.forEach(function (item, index) {
                var challengeButton = '';
                var isMeText = '';
                if (currentMember != (item.user.username || "")) {
                    challengeButton = `<div><a href="/game/create?first_user={{auth()->id()}}&second_user=${item.user_id}" class="btn btn-outline-info btn-sm float-right">Challenge</a></div>`;
                } else {
                    isMeText = "(You)";
                }

                $('#members_output').append(
                    `<div class="list-group-item">
                        <div class="media">
                            <div class="media-body d-none d-lg-block ml-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">${item.user.username}</h6> ${isMeText}
                                    ${challengeButton}
                                </div>
                            </div>
                        </div>
                    </div>`);
            });
        }
    </script>
@endsection
