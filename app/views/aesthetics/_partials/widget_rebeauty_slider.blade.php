<!-- sliderBox -->
<div class="slider" id="sliderBox">
    <span class="btn-close" alt="關閉">關閉</span>
    <div class="main-title"><?php echo $title?></div>
    <div class="photo-num">第<span class="no number">1</span>張&nbsp;/&nbsp;共<span class="number"><?php echo sizeof($images)?></span>張</div>
    <div class="clear"></div>
    <div class="wrapper">
        <ul class="container-images">
            <?php foreach($images as $image):?>
            <li><a href="<?php echo $image->image?>" title="<?php echo $image->text?>" rel="imgGroup">
            		<img src="<?php echo $image->image?>?w=130&h=130&ar=i" alt="<?php echo $image->text?>" width="130" height="130" />
            		<p><?php echo $image->text?></p></a>
            </a></li>
            <?php endforeach;?>
        </ul>
    </div><!-- wrapper end -->
    <span class="nex prevCcontrol" id="prev"></span>
    <span class="pre nextCcontrol" id="next"></span>
    <div class="image-block">
        <img src="" class="hidden"/>
        <div class="title hidden"></div>
        <div class="loader hidden"></div>
    </div>
</div>
<!-- slider end -->

@section('headContent')
    {{ HTML::style(asset('packages/colorbox/colorbox.css')) }}
    {{ HTML::style(asset('plugins/slider/css_slider.css')) }}
@stop

@section('bottomContent')
    @parent
    {{ HTML::script(asset('packages/colorbox/jquery.colorbox-min.js')) }}
    {{ HTML::script(asset('plugins/slider/js_slider.js')) }}
@stop