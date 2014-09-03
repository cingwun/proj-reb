<a id="quickReservation" href="">快速預約</a>
<!--<iframe id="quickReservationForm" src="/reservation"></iframe>-->
<div class="reservation-panel" id="quickReservationForm">
    <form method="post" action="<?php echo URL::route('frontend.reservation.post')?>">
        <p><label>姓名</label><input type="text" name="name"/></p>
        <p><label>性別</label><label class="radioWrap"><input type="radio" name="sex" value="male"/>男</label><label class="radioWrap"><input type="radio" name="sex" value="female" checked />女</label></p>
        <p><label>電話</label><input type="text" name="phone"/></p>
        <p><label>Email</label><input type="text" name="email"/></p>
        <p><label>預約項目</label><input type="text" name="note"/></p>
        <button class="btn btn-submit"><span>我要預約</span></button>
    </form>
</div>

@section('bottomContent')
    {{ HTML::script(asset('js/index/js_index.js')) }}
    @parent
@stop