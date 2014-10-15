<?php
    $cases = \Wintness::where('status', '=', '1')
                      ->where('isInSiderbar', '=', '1')
                      ->where('lang', App::getLocale())
                      ->orderBy('sort', 'desc')
                      ->orderBy('updated_at', 'desc')
                      ->take(5)
                      ->get(array('id', 'title', 'description', 'img_before', 'img_after'));
?>
<div class="setListWrap">
<h3 class="titleRp h3_share">美麗見證</h3>
    <ul class="setList shareList">
        <?php
            if (sizeof($cases)>0):
                foreach($cases as $case):
                    $before = json_decode($case->img_before, true);
                    $after = json_decode($case->img_after, true);
        ?>
        <li>
            <a href="<?php echo URL::route('frontend.wintness.article', array('id'=>$case->id))?>">
                <div class="imgWrap"><img src="<?php echo $before[0]['image'] . '?w=116&h=100&ar=i'?>" alt="before" width="116" height="100"/><img src="<?php echo $after[0]['image'] . '?w=116&h=100&ar=i'?>" alt="after" width="116" height="100"/></div>
                <h4><?php echo $case->title?></h4>
                <p><?php echo \Text::preEllipsize(HTML::entities($case->description), 30)?></p>
            </a>
        </li>
        <?php
                endforeach;
            endif;
        ?>
    </ul>
</div> <!-- setListWrap end -->
