<div class="pager">
	<a class="firstPage pagerIcon" href="#">第一頁</a>
	<a class="prevPage pagerIcon" href="#">上一頁</a>
	&nbsp;&nbsp;
	<span>1</span>
<% for @i in 2..10 %>
	<a href="#"><%= @i %></a>
<% end %>
	&nbsp;&nbsp;
	<a class="nextPage pagerIcon" href="#">下一頁</a>
	<a class="lastPage pagerIcon" href="#">最後一頁</a>
</div><!-- ======================== pageNav end ======================== -->	
