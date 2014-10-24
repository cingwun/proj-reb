@extends('spa._layouts.default')

@section('bodyId')
{{''}}
@stop

@section('mainBanner')
@stop

@section('content')
<aside id="indexSetContent" class="hotEv">
	<h2 class="titleRp title_spa-hotEv">熱門推薦</h2>
	@include('spa._partials.widget_hotBanner')
</aside>
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
