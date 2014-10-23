<?php
	$val = array('qs'=>'w=288&h=343&ar=i');
	$sizeAttr = 'width="288" height="343"';
	$images = BannersController::bannerShow('hot', 'spa');
?>

<div class="topBanner hotSlider">
	@foreach($images as &$i)
	<a class="slide" href="{{$i->link}}" target="{{$i->target}}"><img src="{{$i->image.'?'.$val['qs']}}" alt="{{$i->title}}" {{$sizeAttr}} /></a>
	@endforeach
</div>