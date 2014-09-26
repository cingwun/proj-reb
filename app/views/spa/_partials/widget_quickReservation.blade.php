<div class="quickReservation">
	<a id="quickReservation" href="https://docs.google.com/forms/d/18WJkxACO6qmqsIYHzqtXHkP_EqU5NZrza-UsGh3R1gg/viewform" target="_blank">快速預約</a>
	<!-- <iframe id="quickReservationWrap" src=""></iframe>
	 -->

	<form action="{{URL::route('spa.reservation.form.write')}}" method="post" id="quickReservationForm">
		<p><label>姓名</label><input type="text" name="name" /></p>
		<p><label>性別</label><label class="radioWrap"><input type="radio" name="sex" value="women" checked />女<span>(限女性)</span></label></p>
		<p><label>電話</label><input type="text" name="phone" /></p>
		<p><label>Email</label><input type="text" name="email" /></p>
		<p><label>預約項目</label><input type="text" name="improve_item" /></p>
		<button class="btn sent" type="submit"><span>我要預約</span></button>
	</form>
</div>