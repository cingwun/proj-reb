<div style="margin-bottom:5px;">

<button type="button" class="btn btn-success choose" onclick="this.form.file.click();">
        <span>選擇圖片</span>
    </button>
<button id="upload_btn" type="button" class="btn btn-primary" style="display:none;">
        <span>上傳圖片</span>
    </button>

    <input id="fileupload" type="file" name="file" style="display:none;" />

<input type="hidden" name="file_path" style="display:none;" />
<span id="file_name"></span>
<div id="thumb_image"></div>
</div>

<script src="{{URL::to('packages')}}/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
<script src="{{URL::to('packages')}}/jquery-file-upload/js/jquery.iframe-transport.js"></script>
<script src="{{URL::to('packages')}}/jquery-file-upload/js/jquery.fileupload.js"></script>
<script>

var thumb_image_width = 120;

if($("input[name='image_path']").val()!="") {
    $("#thumb_image").html('<img class="thumb_image" src="'+$("input[name='image_path']").val()+'?w='+thumb_image_width+'"/>');
    $(".choose span").text('重新選擇');
}

$(function () {
    console.log(thumb_image_width);
    $('#fileupload').fileupload({
    	url: '{{URL::route('fileUploadUrl','ajax');}}',
        formData:null,
        dataType: 'json',
         add: function (e, data) {
         	$('#file_name').text(data.files[0].name);
         	$('#upload_btn').show();
            $('#upload_btn')
                .click(function () {
                	$('#upload_btn').hide();
                    $('#file_name').text('上傳中...');
                    data.submit();
                });
        },
        done: function (e, data) {
            $('#file_name').text('上傳完成.');
            thumb_image(data.result.files);
            $(".choose span").text('重新選擇');
        }
    });

});

function thumb_image(file_path) {
    $("#thumb_image").html('<img class="thumb_image" src="'+file_path+'?w='+thumb_image_width+'"/>');
    $("input[name='image_path']").val(file_path);
}
</script>

