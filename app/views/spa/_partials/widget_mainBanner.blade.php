<?php
	$val = array();
    $sizeAttr = '';
 	$bannerSize  = 'medium';
 	if(!empty($size))
 		$bannerSize = 'large';
 	
	if ($bannerSize=='large'){
	   $val = array('qs'=>'w=960&h=430&ar=i', 'class'=>'');
	   $sizeAttr = 'width="960" height="430"';
    }elseif ($bannerSize=='medium'){
	   $val = array('qs'=>'w=960&h=250&ar=i', 'class'=>'innerBanner');
	   $sizeAttr = 'width="960" height="250"';
    }else{
	   $val = array('qs'=>'w=700&h=300&ar=i', 'class'=>'innerBanner');
	   $sizeAttr = 'width="700" height="300"';
    }
    $images = BannersController::bannerShow($bannerSize,'spa');
?>
<div <?php echo ($bannerSize=='large') ? 'id="mainBanner"':'';?> class="topBanner bannerSlider <?php echo $val['class']?>">
	<?php foreach ($images as &$b):?>
    <a class="slide" href="<?php echo $b->link?>" target="<?php echo $b->target?>"><img src="<?php echo $b->image.'?'.$val['qs']?>" alt="<?php echo $b->title?>" <?php echo $sizeAttr?> /></a>
	<?php endforeach?>
	<?php if ($bannerSize=='large'):?>
	<div class="bannerNext npNav">Next</div><div class="bannerPrev npNav">Prev</div>
	<?php endif;?>
</div>
<!-- mainBanner end -->