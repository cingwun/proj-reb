
<div class="pager">
	<a class="firstPage pagerIcon" href="{{$pageURL}}?page=1">第一頁</a>
	<a class="prevPage pagerIcon" href="@if($page-1 != 0) {{$pageURL}}?page={{$page-1}}@endif">上一頁</a>
	&nbsp;&nbsp;
	@for($i = 1;$i <= ($rowsNum/8)+1; $i++)
	@if($i == $page)
	<span>{{$i}}</span>
	@else
	<a href="{{$pageURL}}?page={{$i}}">{{$i}}</a>
	@endif
	@endfor
	&nbsp;&nbsp;
	<a class="nextPage pagerIcon" href="@if($page != floor($rowsNum/8)+1) {{$pageURL}}?page={{$page+1}}@endif">下一頁</a>
	<a class="lastPage pagerIcon" href="{{$pageURL}}?page={{floor($rowsNum/8)+1}}">最後一頁</a>
</div><!-- ======================== pageNav end ======================== -->	
