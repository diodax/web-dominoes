@extends('layouts.app')

@section('content')
    <div style="width: 100%; padding-right: 15px; padding-left: 15px;">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div>
                    <div>
                        <div id="container" style="width: 100%; height: 600px;"> <!--border:1px solid black;-->
                            <svg id="game-board" xmlns="http://www.w3.org/2000/svg"
                                 class="svg-container"
                                 version="1.1"
                                 viewBox="0 0 900 900"
                                 xmlns:xlink="http://www.w3.org/1999/xlink"
                                 xmlns:ev="http://www.w3.org/2001/xml-events">


                                <!-- The domino tree -->
                                <svg id="treeroot" x="420" y="390" width="60" height="120" style="overflow: visible;">
                                    <!-- The root piece of the domino -->
                                    <rect class="invisible rootLeaf "
                                        x="0%" y="0%" width="60" height="120"
                                        rx="10" ry="10" fill="none"
                                        stroke="#e55615" stroke-opacity="0.7" stroke-width="25">
                                    </rect>

                                    @if (is_null($gameState->tree->id))
                                        <rect id="rootShadow" x="0%" y="0%" width="60" height="120" rx="10" ry="10" fill="#f0f2f4" />
                                    @else
                                        <image id="{{ $gameState->tree->id }}" x="0%" y="0%" width="60" height="120" xlink:href="/img/bones/bone{{ $gameState->tree->id }}.png" />
                                    @endif

                                    <!-- The left branch of the domino tree -->
                                    @if (!is_null($gameState->tree->leftBranch->id))
                                        @include('game.partials.node-left', ['child' => $gameState->tree->leftBranch, 'level' => 1, 'isParentDouble' => ($gameState->tree->head == $gameState->tree->tail)])
                                    @endif

                                    <!-- The right branch of the domino tree -->
                                    @if (!is_null($gameState->tree->rightBranch->id))
                                        @include('game.partials.node-right', ['child' => $gameState->tree->rightBranch, 'level' => 1, 'isParentDouble' => ($gameState->tree->head == $gameState->tree->tail)])
                                    @endif

                                </svg>

                                <!-- Decides which hand to show based on authorization -->
                                @if (Auth::user()->username == $gameState->player1->username)
                                    @php ($playerHand = $gameState->player1->hand)
                                    @php ($opponenthand = $gameState->player2->hand)
                                    @php ($validPlayerPlays = $gameState->player1->validPlays)
                                @else
                                    @php ($playerHand = $gameState->player2->hand)
                                    @php ($opponenthand = $gameState->player1->hand)
                                    @php ($validPlayerPlays = $gameState->player2->validPlays)
                                @endif

                                {{-- <svg id="infoTab" x="900" y="700" width="200" height="400" style="overflow: visible;">
                                        <!-- Rounded corner rect element -->
                                        <foreignobject x="0" y="0" width="100%" height="100%">
                                            <div class="card" style="background-color: #f1f7fa;">
                                                <div class="row">
                                                    <h5>{{ $gameState->player1->username }} VS {{ $gameState->player2->username }}</h5>
                                                </div>
                                                <button class="btn btn-primary" type="button">Draw</button>

                                                <br/><br/><br/><br/><br/><br/>
                                            </div>
                                        </foreignobject>
                                </svg> --}}

                                <svg id="boneyard" x="900" y="730" width="200" height="150" style="overflow: visible;">
                                        <!-- Rounded corner rect element -->
                                        <foreignobject x="0" y="20" width="100%" height="100%">
                                            <div class="card" style="background-color: #f1f7fa;">
                                                <br/><br/><br/><br/><br/><br/>
                                            </div>
                                        </foreignobject>
                                        @php($boneyard = collect($gameState->boneyard))

                                        <!--Bones currently in the boneyard-->
                                        <image x="11%" y="20%" width="60" height="120" onclick="drawFromBoneyard(evt)" xlink:href="/img/bones/bone-hidden.png" />
                                        <text class="svg-text" x="49%" y="75" fill="black">Boneyard</text>
                                        <text class="svg-text" x="49%" y="110" fill="black">Count: {{ $boneyard->count() }}</text>

                                        @if (count($validPlayerPlays) > 0 || Auth::user()->username != $gameState->currentPlayer)
                                            <image x="11%" y="20%" width="60" height="120" opacity="0.5" xlink:href="/img/bones/bone-grey.png" />
                                        @endif
                                </svg>

                                <!-- A wrapper svg for each player's hand, to create a new positioning context for the tiles -->
                                <svg id="opponenthand" x="100" y="15" width="700" height="150" style="overflow: visible;">
                                    <!-- Rounded corner rect element -->
                                    <foreignobject x="0" y="0" width="100%" height="100%">
                                        <div class="card"><br/><br/><br/><br/><br/><br/></div>
                                    </foreignobject>
                                    {{-- <rect x="0" y="0" class="card" width="100%" height="100%" rx="15" ry="15" fill="#a7d2f1" stroke="#bddcf5"></rect> --}}

                                    <!-- Bones currently in the opponent's hand go here -->
                                    @foreach ($opponenthand as $bone)
                                        <image x="{{$loop->index + 1}}0%" y="2%" width="60" height="120" xlink:href="/img/bones/bone-hidden.png" />
                                    @endforeach
                                </svg>
                                <svg id="playerhand" x="100" y="730" width="700" height="150" style="overflow: visible;">
                                    <!-- Rounded corner rect element -->
                                    <foreignobject x="0" y="20" width="100%" height="100%">
                                        <div class="card">
                                            <br/><br/><br/><br/><br/><br/>
                                            {{-- <span class="badge badge-success" style="background-color: #03dac6;float: left; width: 140px; font-family: Lato,sans-serif; font-size: 125%;">Player's Turn</span> --}}
                                        </div>
                                    </foreignobject>
                                    {{-- <rect x="0" y="0" class="card" width="100%" height="100%" rx="15" ry="15" fill="#a7d2f1" stroke="#bddcf5"></rect> --}}

                                    <!-- Bones currently in the player's hand go here -->
                                    @foreach ($playerHand as $bone)
                                        <image id="{{ $bone['head'] }}-{{ $bone['tail'] }}" onclick="showPlayOptions(evt)" x="{{$loop->index + 1}}0%" y="0%" width="60" height="120" xlink:href="{{ $bone['image_url'] }}">
                                                <animate attributeName="y" from="0%" to="-15%" dur="0.1s" begin="mouseover" fill="freeze" />
                                                <animate attributeName="y" from="-15%" to="0%" dur="0.1s" begin="mouseout" fill="freeze" />
                                        </image>

                                        <!-- If the bone is not marked as playable this turn, put a gray semi-transparent layer over it -->
                                        @if (!in_array("{$bone['head']}-{$bone['tail']}", $validPlayerPlays))
                                            <image x="{{$loop->index + 1}}0%" y="0%" width="60" height="120" opacity="0.5" xlink:href="/img/bones/bone-grey.png" />
                                        @endif

                                    @endforeach
                                </svg>


                                <defs>
                                    <style id="svg-pan-zoom-controls-styles" type="text/css">
                                        .svg-pan-zoom-control {
                                            cursor: pointer;
                                            fill: black;
                                            fill-opacity: 0.333;
                                        }
                                        .svg-pan-zoom-control:hover {
                                            fill-opacity: 0.8;
                                        }
                                        .svg-pan-zoom-control-background {
                                            fill: white;
                                            fill-opacity: 0.5;
                                        }
                                        .svg-pan-zoom-control-background {
                                            fill-opacity: 0.8;
                                        }
                                        .card {
                                            box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                            background-color: #c0c4f4;
                                            font-family: Lato,sans-serif;
                                            font-size:1rem;
                                            font-weight: 400;
                                            line-height: 1.5;
                                            text-align: left;
                                        }
                                        .card:hover {
                                            box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
                                        }
                                    </style>
                                </defs>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ url('/js/svg-pan-zoom.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            if (window.timerId === undefined) {
                window.timerId = setInterval(checkTurn, 2000);
            }

            // window.zoomTiger = svgPanZoom('#game-board', {
            //     zoomEnabled: true,
            //     controlIconsEnabled: true,
            //     fit: true,
            //     center: true,
            // });
        });

        function drawFromBoneyard(evt) {
            submitTurnAction(evt, "DRAW", null, null);
        }

        function showPlayOptions(evt) {
            var svgobj = evt.target;

            var leftLeafId = @json($gameState->tree->leftLeafId);
            var leftBranchLeaf = @json($gameState->tree->leftBranchLeaf);

            var rightLeafId = @json($gameState->tree->rightLeafId);
            var rightBranchLeaf = @json($gameState->tree->rightBranchLeaf);

            var rootId = @json($gameState->tree->id);
            var rootHead = @json($gameState->tree->head);
            var rootTail = @json($gameState->tree->tail);

            var svgArray = svgobj.id.split("-");
            var bone = {
                id: svgobj.id,
                head: parseInt(svgArray[0]),
                tail: parseInt(svgArray[1])
            };

            // Resetting the state from the last click
            $('.rootLeaf, .leftLeaf, .rightLeaf').addClass('invisible');
            evt.stopPropagation();

            // Check which side should be highlighed as a valid play
            if (leftBranchLeaf != null && svgArray.includes(leftBranchLeaf.toString())) {
                $('.leftLeaf').removeClass('invisible');
                // Set a click event on the corresponding domino bone
                $('#' + leftLeafId).click(function(e){
                    submitTurnAction(e, "LAY", "L", bone);
                });
            }
            if (rightBranchLeaf != null && svgArray.includes(rightBranchLeaf.toString())) {
                $('.rightLeaf').removeClass('invisible');
                // Set a click event on the corresponding domino bone
                $('#' + rightLeafId).click(function(e){
                    submitTurnAction(e, "LAY", "R", bone);
                });
            }
            if (rootId == null || svgArray.includes(rootHead.toString()) || svgArray.includes(rootTail.toString())) {
                $('.rootLeaf').removeClass('invisible');
                // Set a click event on the corresponding domino bone
                $('#rootShadow').click(function(e){
                    // This is for the first piece that goes in the center
                    submitTurnAction(e, "LAY", "C", bone);
                });

                // This is for the second piece, that might go to the left or to the right branches
                if (rootId != null) {
                    var branch = "L";
                    if ((rootHead != rootTail) && (svgArray.includes(rootTail.toString()))) {
                        branch = "R";
                    }

                    $('#' + rootId).click(function(e){
                        submitTurnAction(e, "LAY", branch, bone);
                    });
                }
            }

            // Upon clicking that bone, an AJAX request with submitTurnAction() should be made
        }

        function submitTurnAction(e, type, branch, bone) {
            // Create the Move object to send via POST
            var move = {
                type: type,
                branchPlayedAt: branch || null,
                bone: bone || null
            };

            $.ajax({
                url: '{{ route('game.submit', ['id' => $gameState->id]) }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: JSON.stringify(move),
                contentType: 'application/json',
                success: function (response) {
                    if (response === true) {
                        console.log("Valid turn submitted!");
                        location.reload(true);
                    }  else {
                        console.log("Invalid turn submitted");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }

        function checkTurn() {
            // This action checks to see if the opponent has submitted their next move.
            // The HTTP response must return a single Boolean value: true if it is the player's turn or false.

            $.ajax({
                url: '{{ route('game.check', ['id' => $gameState->id]) }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: JSON.stringify({}),
                contentType: 'application/json',
                success: function (response) {
                    if (response === true) {
                        clearInterval(window.timerId);
                        if (window.timerId === undefined) {
                            location.reload(true);
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }
    </script>
@endsection

<style>
.svg-container {
    display: inline;
    width: inherit;
    min-width: inherit;
    max-width: inherit;
    height: inherit;
    min-height: inherit;
    max-height: inherit;
}
</style>
