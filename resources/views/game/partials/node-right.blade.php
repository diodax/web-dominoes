@if (is_null($child->child))
    @php ($leafclass = "rightLeaf")
@else
    @php ($leafclass = "")
@endif

@if ($level == 1)
    <svg id="right-level-{{ $level }}" x="100%" y="25%" width="60" height="120" style="overflow: visible;">
@else
    <svg id="right-level-{{ $level }}" x="200%" y="0%" width="60" height="120" style="overflow: visible;">
@endif
    <rect class="invisible {{ $leafclass }}"
        x="0%" y="0%" width="60" height="120"
        rx="10" ry="10" fill="none"
        stroke="#e55615" stroke-opacity="0.7" stroke-width="25"
        transform="rotate(-90 30 30)">
    </rect>
    <image id="{{ $child->id }}" x="0%" y="0%" width="60" height="120" xlink:href="/img/bones/bone{{ $child->id }}.png" transform="rotate(-90 30 30)" />

    <!-- The child of the domino branch -->
    @if (!is_null($child->child))
        @include('game.partials.node-right', ['child' => $child->child , 'level' => $level + 1])
    @endif
</svg>
