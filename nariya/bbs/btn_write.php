<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<div class="btn-group btn-group-lg" role="group">
	<button type="button" class="btn btn-basic" title="이모티콘" onclick="na_clip('emo', '<?php echo $is_dhtml_editor ?>');">
		<i class="fa fa-smile-o fa-fw" aria-hidden="true"></i>
		<span class="sr-only">이모티콘</span>
	</button>
	<button type="button" class="btn btn-basic" title="폰트어썸" onclick="na_clip('fa', '<?php echo $is_dhtml_editor ?>');">
		<i class="fa fa-font-awesome fa-fw" aria-hidden="true"></i>
		<span class="sr-only">폰트어썸</span>
	</button>
	<button type="button" class="btn btn-basic" title="동영상" onclick="na_clip('video', '<?php echo $is_dhtml_editor ?>');">
		<i class="fa fa-youtube-play fa-fw" aria-hidden="true"></i>
		<span class="sr-only">동영상</span>
	</button>
	<?php if(isset($boset['na_code']) && $boset['na_code']) { ?>
		<button type="button" class="btn btn-basic" title="코드" onclick="na_clip('code', '<?php echo $is_dhtml_editor ?>');">
			<i class="fa fa-code fa-fw" aria-hidden="true"></i>
			<span class="sr-only">코드</span>
		</button>
	<?php } ?>
	<?php if((isset($nariya['google_key']) && $nariya['google_key']) || (isset($nariya['kakaomap_key']) && $nariya['kakaomap_key'])) { ?>
		<button type="button" class="btn btn-basic" title="지도" onclick="na_clip('map', '<?php echo $is_dhtml_editor ?>');">
			<i class="fa fa-map-marker fa-fw" aria-hidden="true"></i>
			<span class="sr-only">지도</span>
		</button>
	<?php } ?>
	<?php if ($is_member) { // 임시 저장된 글 기능 ?>
		<button type="button" id="btn_autosave" data-toggle="modal" data-target="#saveModal" class="btn btn-basic" title="임시 저장된 글 목록 열기">
			<i class="fa fa-repeat fa-fw" aria-hidden="true"></i>
			<span class="sr-only">임시저장글</span>
			<span id="autosave_count" class="orangered"><?php echo $autosave_count; ?></span>
		</button>
	<?php } ?>
</div>