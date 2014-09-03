
<div style="margin-bottom:5px;">
圖片集：<br/>
<button type="button" class="btn btn-success choose" onclick="this.form.images.click();">
        <span>加入圖片</span>
    </button>
<button id="upload_btn" type="button" class="btn btn-primary" style="display:none;">
        <span>上傳圖片</span>
    </button>

<div id="images" style="overflow:hidden;">
{{--For Images Addition--}}
</div>

    <input id="images_upload" type="file" name="images" style="display:none;" />


<input type="hidden" name="file_path" style="display:none;" />
<span id="file_name"></span>
<div id="thumb_image"></div>
</div>

{{--If none include before
<script src="{{URL::to('packages')}}/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
<script src="{{URL::to('packages')}}/jquery-file-upload/js/jquery.iframe-transport.js"></script>
<script src="{{URL::to('packages')}}/jquery-file-upload/js/jquery.fileupload.js"></script>
--}}

<script type="text/javascript">

	var thumb_image_width = 120;

	$(function () {
	    $('#images_upload').fileupload({
	    	url: '{{URL::route('fileUploadUrl','ajax');}}',
	        formData:null,
	        dataType: 'json',
	        done: function (e, data) {
	            add_image(data.result.files);
	        }
	    });
	});

	function add_image(file_path, text) {
		text = text || "";
		$("#images").html($("#images").html()+image_html(file_path, text));
	}

	function image_html(file_path, text) {
		var image_html = '<div class="each-image" style="display:block;float:left;"><img src="'+file_path+'?w='+thumb_image_width+'" /><br><input type="hidden" name="'+images_input_name+'" value="'+file_path+'" /><input type="text" class="form-control" name="'+descriptions_input_name+'" size="12" value="'+text+'" placeholder="圖片描述" style="width:170px;" /><button type="button" class="btn btn-danger" onclick="del(this);" style="margin:0 0 10px 0;">X</button></div>';
		return image_html;
	}

	function del(dom) {
		$(dom).parent("div").empty();
	}

	@if(isset($images))
		@foreach($images as $key=>$image)
			add_image("{{$image->image}}","{{$image->text}}");
		@endforeach
	@endif


</script>