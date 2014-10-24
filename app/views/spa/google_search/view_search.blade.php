<?php
$titleType = 'news';
?>

@extends('spa._layouts.default')

@section('bodyId')
{{'index'}}
@stop

@section('mainBanner')
@stop

@section('content')
@include('spa._partials.widget_setContent')
<div id="mainContent" class="postBox" role="main">
<script>
  (function() {
    var cx = '017730374180726895139:6xs9iqhrlp8';
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
</div><!-- ======================== mainContent end ======================== -->
@stop



@section('bottom')
@stop
