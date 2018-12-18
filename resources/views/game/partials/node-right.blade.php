@if (is_null($child->child))
    @php ($leafclass = "rightLeaf")
@else
    @php ($leafclass = "")
@endif

@if ($child->orientation == "L")
    @php ($rotateAttr = "rotate(-90)")
    @php ($xPosLevel1Attr = "150%")
    @if ($isParentDouble)
        @php ($xPosLevelPlusAttr = "150%")
    @else
        @php ($xPosLevelPlusAttr = "200%")
    @endif
@elseif ($child->orientation == "R")
    @php ($rotateAttr = "rotate(90)")
    @php ($xPosLevel1Attr = "150%")
    @if ($isParentDouble)
        @php ($xPosLevelPlusAttr = "150%")
    @else
        @php ($xPosLevelPlusAttr = "200%")
    @endif
@elseif ($child->orientation == "U")
    @php ($rotateAttr = "rotate(0)")
    @php ($xPosLevel1Attr = "150%")
    @php ($xPosLevelPlusAttr = "150%")
@endif

@if ($level == 1)
    <svg id="right-level-{{ $level }}" x="{{ $xPosLevel1Attr }}" y="0%" width="60" height="120" style="overflow: visible;">
@else
    <svg id="right-level-{{ $level }}" x="{{ $xPosLevelPlusAttr }}" y="0%" width="60" height="120" style="overflow: visible;">
@endif
    <rect class="invisible {{ $leafclass }}"
        x="0%" y="0%" width="60" height="120"
        rx="10" ry="10" fill="none"
        stroke="#e55615" stroke-opacity="0.7" stroke-width="25"
        style="transform-origin: 50% 50%;"
        transform="{{ $rotateAttr }}">
    </rect>
    <image id="{{ $child->id }}" x="0%" y="0%" width="60" height="120" style="transform-origin: 50% 50%;" xlink:href="/img/bones/bone{{ $child->id }}.png" transform="{{ $rotateAttr }}" />

    <!-- The child of the domino branch -->
    @if (!is_null($child->child))
        @include('game.partials.node-right', ['child' => $child->child , 'level' => $level + 1, 'isParentDouble' => ($child->head == $child->tail)])
    @endif
</svg>
