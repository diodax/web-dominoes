@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div id="container" style="width: 100%; height: 600px; border:1px solid black; ">
                            <svg id="game-board" xmlns="http://www.w3.org/2000/svg"
                                 class="svg-container"
                                 version="1.1"
                                 viewBox="0 0 900 900"
                                 xmlns:xlink="http://www.w3.org/1999/xlink"
                                 xmlns:ev="http://www.w3.org/2001/xml-events">


                                <!-- The domino tree -->
                                <svg id="treeroot" x="420" y="390" width="60" height="120" style="overflow: visible;">

                                    @if (is_null($gameState->tree->id))
                                        @php ($leafclass = "rootLeaf")
                                    @else
                                        @php ($leafclass = "")
                                    @endif

                                    <!-- The root piece of the domino -->
                                    <rect class="invisible {{ $leafclass }}"
                                        x="0%" y="0%" width="60" height="120"
                                        rx="10" ry="10" fill="none"
                                        stroke="#e55615" stroke-opacity="0.7" stroke-width="25">
                                    </rect>

                                    @if (is_null($gameState->tree->id))
                                        <rect id="rootShadow" x="0%" y="0%" width="60" height="120" rx="10" ry="10" fill="white" />
                                    @else
                                        <image id="{{ $gameState->tree->id }}" x="0%" y="0%" width="60" height="120" xlink:href="/img/bones/bone{{ $gameState->tree->id }}.png" />
                                    @endif

                                    <!-- The left branch of the domino tree -->
                                    @if (!is_null($gameState->tree->leftBranch->id))
                                        @include('game.partials.node-left', ['child' => $gameState->tree->leftBranch, 'level' => 1])
                                    @endif

                                    <!-- The right branch of the domino tree -->
                                    @if (!is_null($gameState->tree->rightBranch->id))
                                        @include('game.partials.node-right', ['child' => $gameState->tree->rightBranch, 'level' => 1])
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

                                <!-- A wrapper svg for each player's hand, to create a new positioning context for the tiles -->
                                <svg id="opponenthand" x="100" y="0" width="700" height="150" style="overflow: visible;">
                                    <!-- Rounded corner rect element -->
                                    <rect x="0" y="0" width="100%" height="100%" rx="15" ry="15" fill="#a7d2f1" stroke="#bddcf5"></rect>

                                    <!-- Bones currently in the opponent's hand go here -->
                                    @foreach ($opponenthand as $bone)
                                        <image x="{{$loop->index + 1}}0%" y="0%" width="60" height="120" xlink:href="/img/bones/bone-hidden.png" />
                                    @endforeach
                                </svg>
                                <svg id="playerhand" x="100" y="750" width="700" height="150" style="overflow: visible;">
                                    <!-- Rounded corner rect element -->
                                    <rect x="0" y="0" width="100%" height="100%" rx="15" ry="15" fill="#a7d2f1" stroke="#bddcf5"></rect>

                                    <!-- Bones currently in the player's hand go here -->
                                    @foreach ($playerHand as $bone)
                                        <image id="{{ $bone->head }}-{{ $bone->tail }}" onclick="showPlayOptions(evt)" x="{{$loop->index + 1}}0%" y="0%" width="60" height="120" xlink:href="{{ $bone->image_url }}">
                                                <animate attributeName="y" from="0%" to="-15%" dur="0.1s" begin="mouseover" fill="freeze" />
                                                <animate attributeName="y" from="-15%" to="0%" dur="0.1s" begin="mouseout" fill="freeze" />
                                        </image>

                                        <!-- If the bone is not marked as playable this turn, put a gray semi-transparent layer over it -->
                                        @if (!in_array("{$bone->head}-{$bone->tail}", $validPlayerPlays))
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
            window.timerId = setInterval(checkTurn, 2000);

            // window.zoomTiger = svgPanZoom('#game-board', {
            //     zoomEnabled: true,
            //     controlIconsEnabled: true,
            //     fit: true,
            //     center: true,
            // });
        });

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
                    location.reload(true);
                    // alert("POST /submit received!");
                    // console.log(response);
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
                        window.clearInterval(window.timerId);
                        location.reload(true);
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
