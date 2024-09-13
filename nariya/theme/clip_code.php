<?php
include_once('./_common.php');

// 클립보드
$is_clip = (isset($clip) && $clip) ? true : false;

// 클립모달
$is_clip_modal = false;

$g5['title'] = '코드 등록';
include_once(G5_THEME_PATH.'/head.sub.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.NA_URL.'/css/modal.css">', 0);

// 모달 내 모달
$is_modal_win = true;

// Loader
if(is_file(G5_THEME_PATH.'/_loader.php')) {
	include_once(G5_THEME_PATH.'/_loader.php');
} else {
	include_once(NA_PATH.'/theme/loader.php');
}

?>

<?php if($is_clip) { ?>
<!-- 클립보드 복사 시작 { -->
<script src="<?php echo NA_URL ?>/js/clipboard.min.js"></script>
<div class="modal fade" id="clipModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h5 class="modal-title text-white"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> <b>Clipboard</b></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span class="text-white" aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<textarea type="text" id="txtClip" class="form-control"></textarea>
				<div class="text-center pt-3">
					<button type="button" class="btn btn-primary btn-lg btn-clip en" data-clipboard-target="#txtClip">
						Copy Code
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 클립보드 복사 끝 { -->
<?php } ?>

<div id="topNav" class="bg-primary text-white">
	<div class="p-3">
		<button type="button" class="close clip-close" aria-label="Close">
			<span aria-hidden="true" class="text-white">&times;</span>
		</button>
		<h5><i class="fa fa-code" aria-hidden="true"></i> CODE</h5>
	</div>
</div>

<div id="topHeight"></div>

<ul class="list-group f-de">
	<li class="list-group-item bg-light">
		<b>코드 입력</b>
	</li>
	<li class="list-group-item">
		<div class="input-group">
			<input type="text" id="txtCode" class="form-control" placeholder="대표 코드 종류 입력 ex) css, html, js...">
			<div class="input-group-append">
				<button type="button" class="btn btn-primary clip-txt">
					<i class="fa fa-code" aria-hidden="true"></i>
					코드 생성
				</button>
			</div>
		</div>
	</li>
	<li class="list-group-item border-0">
		<textarea id="codeZone" rows="25" name="codeZone" class="form-control"></textarea>
	</li>
</ul>

<script>
$(window).on('load', function () {
	na_nav('topNav', 'topHeight', 'fixed-top');
});

$(document).ready(function() {
	<?php if($is_clip) { ?>
		var clipboard = new ClipboardJS('.btn-clip');
		clipboard.on('success', function(e) {
			alert("복사가 되었으니 Ctrl + V 를 눌러 붙여넣기해 주세요.");
			$('#clipModal').modal('hide');
			window.parent.closeClipModal();
		});
		clipboard.on('error', function(e) {
			alert("복사가 안되었으니 Ctrl + C 를 눌러 복사해 주세요.");
		});

		$('.clip-txt').click(function() {
			var txt = $('#txtCode').val();
			var code = $('#codeZone').val();
			if(!txt) {
				alert('대표 코드 종류를 입력해 주세요.');
				$('#txtCode').focus();
				return false;
			}
			if(!code) {
				alert('등록할 코드를 입력해 주세요.');
				$('#codeZone').focus();
				return false;
			}
			var clip = "[code=" + txt + "]" + code + "[/code]";
			$("#txtClip").val(clip);
			$('#clipModal').modal('show');
		});
	<?php } else { ?>
		$('.clip-txt').click(function() {
			var txt = $('#txtCode').val();
			var code = $('#codeZone').val();
			if(!txt) {
				alert('대표 코드 종류를 입력해 주세요.');
				$('#txtCode').focus();
				return false;
			}
			if(!code) {
				alert('등록할 코드를 입력해 주세요.');
				$('#codeZone').focus();
				return false;
			}
			var clip = "[code=" + txt + "]" + code + "[/code]";
			parent.document.getElementById("wr_content").value += clip;
			window.parent.closeClipModal();
		});
	<?php } ?>
	$('.clip-close').click(function() {
		window.parent.closeClipModal();
	});
});
</script>

<?php 
include_once(G5_THEME_PATH.'/tail.sub.php');