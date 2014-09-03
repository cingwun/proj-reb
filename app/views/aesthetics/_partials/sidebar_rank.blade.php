<div class="setListWrap">
<h3 class="titleRp h3_hot">美麗排行榜</h3>
        <ul class="setList hotList">
                @foreach(RanksController::sidebarData() as $key=>$rank)
                <li><i>{{ $key+1 }}</i><a href="{{ $rank->link }}" target="{{ $rank->target }}">{{ $rank->title }}</a></li>
                @endforeach
        </ul>

</div> <!-- setListWrap end -->
