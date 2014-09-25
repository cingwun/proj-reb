<div class="pager">
	<a class="firstPage pagerIcon" href="#">第一頁</a>
	<a class="prevPage pagerIcon" href="#">上一頁</a>
	&nbsp;&nbsp;
	@for($i = 1;$i <= ($rowsNum/8)+1; $i++)
	@if($i == $page)
	<span>1</span>
	@else
	<a href="{{$pageURL}}">{{$i}}</a>
	@endif
	@endfor
	&nbsp;&nbsp;
	<a class="nextPage pagerIcon" href="#">下一頁</a>
	<a class="lastPage pagerIcon" href="#">最後一頁</a>
</div><!-- ======================== pageNav end ======================== -->	