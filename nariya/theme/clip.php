<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_javascript('<script src="'.NA_URL.'/js/jquery.form.min.js"></script>', 0);

na_script('fileinput');

?>
<!-- 클립 모달 시작 { -->
<div class="modal fade" id="clipModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div id="clipModalSize" class="modal-content">
			<div id="clipView"></div>
		</div>
	</div>
</div>
<!-- } 클립 모달 끝 -->

<!-- 업로드 모달 시작 { -->
<div class="modal fade" id="na_upload" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<form id="na_upload_form" action="<?php echo NA_URL ?>/bbs/upload.php" method="post" enctype="multipart/form-data">
					<div class="custom-file mb-3">
						<input type="file" name="na_file" class="custom-file-input" id="na_upload_file">
						<label class="custom-file-label" for="na_upload_file" data-browse="File">Select File</label>
					</div>
					<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-upload"></i> Upload File</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
 $(function(){
	$('#na_upload_form').ajaxForm({
		beforeSubmit: function () {
			var chkFile = $("input[name='na_file']").val();

			if(!chkFile) {
				alert("업로드 할 파일을 선택해 주세요.");
				return false;
			}

			var chkExt = chkFile.split('.').pop().toLowerCase();

			if($.inArray(chkExt, ['gif','png','jpg','jpeg']) == -1) {
				alert('이미지 파일(JPG/JPEG/GIF/PNG)만 업로드 할 수 있습니다.');
				return false;
			}
		},
		dataType: 'json',
		success: function(data){
			if(data.success) {

				document.getElementById("wr_content").value += '[' + data.success + ']\n';

				$('#na_upload').modal('hide');

				var fileInput = $("input[name='na_file']");

				if ($.browser.msie) { //IE
					fileInput.replaceWith(fileInput.clone(true));
				} else {
					fileInput.val('');
				}
			} else {
				var chkErr = data.error;
				if(!chkErr) {
					chkErr = '오류가 발생하였습니다.';
				}
				alert(chkErr);
				return false;
			}
		},
		error: function(){
			alert('오류가 발생하였습니다.');
		}                               
	});
});
</script>
<!-- } 업로드 모달 끝 -->