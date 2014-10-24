@extends('spa._layouts.default')

@section('bodyId')
{{'service'}}
@stop

<?php
$titleType = 'news';
?>

@section('content')
@include('spa._partials.widget_setContent')
<div id="mainContent" role="main">
<script>
  (function() {
    var cx = '007704823241692022177:6np-68t2xs4';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:searchresults-only></gcse:searchresults-only>
</div><!-- ======================== searchContent end ======================== -->
@stop



@section('bottom')
@stop
