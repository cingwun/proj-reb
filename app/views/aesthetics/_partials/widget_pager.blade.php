<?php
/*
 * this is a pager widget that it's used to create a page bar
 * @param (int) currPage
 * @param (int) total
 * @param (int) length
 * @param (string) URL
 * @param (string) qs
 */

 if ($total==0 && $total<=$length){
 	$totalPage = 1;
 	$st = $end = 1;
 }else{
 	$totalPage = ceil($total/$length);
 	$half = round($length/2);
 	$st = $currPage - $half;
 	if ($st<=0)
 		$st = 1;
 	$end = $st + $length - 1;
 	if ($end>$totalPage){
 		$end = $totalPage;
 		$st = ($totalPage<$length) ? 1 : $totalPage-$length+1;
 	}
 }

 $qs = (empty($qs)) ? '' : '&';
 $URL .= '?' . $qs . 'page=';
?>
<div class="pager">
	<?php if ($currPage!=1):?>
	<a class="firstPage pagerIcon" href="<?php echo $URL.'1'?>">第一頁</a>
	<?php endif;?>
	<?php if ($currPage!=1):?>
	<a class="prevPage pagerIcon" href="<?php echo $URL.($currPage-1)?>">上一頁</a>
	<?php endif;?>
	&nbsp;&nbsp;
	<?php
		for($i=$st; $i<=$end; $i++):
			echo ($i!=$currPage) ? sprintf('<a href="%s%d">%d</a>', $URL, $i, $i) : sprintf('<span>%d</span>', $i);
		endfor;
	?>
	&nbsp;&nbsp;
	<?php if($totalPage>1 && $currPage!=$totalPage):?>
	<a class="nextPage pagerIcon" href="<?php echo $URL.($currPage+1)?>">下一頁</a>
	<?php endif;?>
	<?php if($totalPage!=1 && $currPage<$totalPage):?>
	<a class="lastPage pagerIcon" href="<?php echo $URL.$totalPage?>">最後一頁</a>
	<?php endif;?>
</div>
<!-- pageNav end -->