<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<div class="btn-group" role="group">
	<button type="button" class="btn nofocus p-0 mx-1" title="이모티콘" onclick="na_clip('emo');">
		<i class="fa fa-smile-o fa-md fa-fw text-black-50" aria-hidden="true"></i>
		<span class="sr-only">이모티콘</span>
	</button>
	<button type="button" class="btn nofocus p-0 mx-1" title="폰트어썸" onclick="na_clip('fa');">
		<i class="fa fa-font-awesome fa-md fa-fw text-black-50" aria-hidden="true"></i>
		<span class="sr-only">폰트어썸</span>
	</button>
	<button type="button" class="btn nofocus p-0 mx-1" title="동영상" onclick="na_clip('video');">
		<i class="fa fa-youtube-play fa-md fa-fw text-black-50" aria-hidden="true"></i>
		<span class="sr-only">동영상</span>
	</button>
	<button type="button" class="btn nofocus p-0 mx-1" title="이미지" data-toggle="modal" data-target="#na_upload">
		<i class="fa fa-picture-o fa-md fa-fw text-black-50" aria-hidden="true"></i>
		<span class="sr-only">이미지</span>
	</button>
	<?php if(isset($boset['na_code']) && $boset['na_code']) { ?>
		<button type="button" class="btn nofocus p-0 mx-1" title="코드" onclick="na_clip('code');">
			<i class="fa fa-code fa-md fa-fw text-black-50" aria-hidden="true"></i>
			<span class="sr-only">코드</span>
		</button>
	<?php } ?>
	<?php if((isset($nariya['google_key']) && $nariya['google_key']) || (isset($nariya['kakaomap_key']) && $nariya['kakaomap_key'])) { ?>
		<button type="button" class="btn nofocus p-0 mx-1" title="지도" onclick="na_clip('map');">
			<i class="fa fa-map-marker fa-md fa-fw text-black-50" aria-hidden="true"></i>
			<span class="sr-only">지도</span>
		</button>
	<?php } ?>
	<button type="button" class="btn nofocus d-none d-sm-block p-0 mx-1" title="댓글창 늘이기" onclick="na_textarea('wr_content','down');">
		<i class="fa fa-plus-circle fa-md fa-fw text-black-50" aria-hidden="true"></i>
		<span class="sr-only">댓글창 늘이기</span>
	</button>
	<button type="button" class="btn nofocus d-none d-sm-block p-0 mx-1" title="댓글창 줄이기" onclick="na_textarea('wr_content','up');">
		<i class="fa fa-minus-circle fa-md fa-fw text-black-50" aria-hidden="true"></i>
		<span class="sr-only">댓글창 줄이기</span>
	</button>
	<button type="button" class="btn nofocus p-0 ml-1" title="새 댓글 작성" onclick="comment_box('','c');">
		<i class="fa fa-refresh fa-md fa-fw text-black-50" aria-hidden="true"></i>
		<span class="sr-only">새 댓글 작성</span>
	</button>
</div>
