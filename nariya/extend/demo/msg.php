<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<?php if($msg == 'board') { ?>
	<div class="alert alert-warning mx-3 mx-sm-0 mb-3" role="alert">
		추가 기능 및 스타일 설정은 목록 상단의 <i class="fa fa-cog fa-spin txt-red"></i> 관리 옵션에 있는 <i class="fa fa-cogs"></i> 스킨설정 항목에서 할 수 있습니다.
	</div>
<?php } else if($msg == 'skin') { ?>
	<div class="alert alert-warning mx-3 mx-sm-0 mb-3" role="alert">
		기능 및 스타일 등 설정은 <i class="fa fa-cogs"></i> 스킨 설정에서 할 수 있습니다.
	</div>
<?php } ?>