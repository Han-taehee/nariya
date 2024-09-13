<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// 데모 메시지
na_demo_msg('skin');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$shingo_skin_url.'/style.css">', 0);

// 목록 헤드
$wset['head_color'] = isset($wset['head_color']) ? $wset['head_color'] : '';
$wset['head_skin'] = isset($wset['head_skin']) ? $wset['head_skin'] : '';

$head_color = ($wset['head_color']) ? $wset['head_color'] : 'primary';
if($wset['head_skin']) {
	add_stylesheet('<link rel="stylesheet" href="'.NA_URL.'/skin/head/'.$wset['head_skin'].'.css">', 0);
	$head_class = 'list-head';
} else {
	$head_class = 'na-table-head border-'.$head_color;
}

?>

<!-- 신고 목록 시작 { -->
<form name="fshingolist" method="post" action="#" onsubmit="return fshingo_submit(this);">
<input type="hidden" name="sw"       value="move">
<input type="hidden" name="page"     value="<?php echo $page; ?>">
<input type="hidden" name="pressed"  value="">

	<!-- 페이지 정보 및 버튼 시작 { -->
	<div id="shingo_btn_top" class="clearfix f-de font-weight-normal mb-2 pl-3 pr-2 px-sm-0">
		<div class="d-flex align-items-center">
			<div id="bo_list_total" class="flex-grow-1">
				전체 <b><?php echo number_format($total_count) ?></b> / <?php echo $page ?> 페이지
			</div>
			<?php if($is_admin || IS_DEMO) { ?>
				<div class="btn-group" role="group">
					<button type="button" class="btn btn_admin nofocus dropdown-toggle dropdown-toggle-empty dropdown-toggle-split p-1" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" title="관리 옵션">
						<i class="fa fa-cog fa-spin fa-fw fa-md" aria-hidden="true"></i>
						<span class="sr-only">관리 옵션</span>
					</button>
					<div class="dropdown-menu dropdown-menu-right p-0 border-0 bg-transparent text-right">
						<div class="btn-group-vertical">
							<a href="javascript:;" class="btn btn-primary py-2" role="button">
								<label class="p-0 m-0" for="allCheck">
									<i class="fa fa-check-square-o fa-fw" aria-hidden="true"></i> 
									전체선택						
								</label>
							</a>
							<button type="submit" name="btn_submit" value="잠금처리" onclick="document.pressed=this.value" class="btn btn-primary py-2">
								<i class="fa fa-lock fa-fw" aria-hidden="true"></i> 
								잠금처리
							</button>
							<button type="submit" name="btn_submit" value="잠금해제" onclick="document.pressed=this.value" class="btn btn-primary py-2">
								<i class="fa fa-unlock fa-fw" aria-hidden="true"></i>
								잠금해제
							</button>
							<button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-primary py-2">
								<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> 
								선택삭제
							</button>
						</div>
					</div>
					<?php if($admin_href) { ?>
						<a href="<?php echo $admin_href ?>" title="스킨 설정" class="btn btn_b01 btn-setup nofocus p-1">
							<i class="fa fa-cogs fa-fw fa-md" aria-hidden="true"></i></a>
							<span class="sr-only">스킨 설정</span>
						</a>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<!-- } 페이지 정보 및 버튼 끝 -->

	<!-- 신고 목록 시작 { -->
	<div id="new_list" class="mb-4">

		<!-- 목록 헤드 -->
		<div class="d-block d-md-none w-100 mb-0 bg-<?php echo $head_color ?>" style="height:4px;"></div>

		<div class="na-table d-none d-md-table w-100 mb-0">
			<div class="<?php echo $head_class ?> d-md-table-row">
				<div class="d-md-table-cell nw-5 px-md-1">번호</div>
				<div class="d-md-table-cell pr-md-1">
					<?php if ($is_admin) { ?>
					<label class="float-left mb-0">
						<span class="sr-only">목록 전체 선택</span>
						<input type="checkbox" onclick="if (this.checked) all_checked(true); else all_checked(false);">
					</label>
					<?php } ?>
					제목
				</div>
				<div class="d-md-table-cell nw-10 pl-2 pr-md-1">작성자</div>
				<div class="d-md-table-cell nw-6 pr-md-1">접수일</a></div>
				<?php if($is_admin) { ?>
					<div class="d-md-table-cell nw-5 pr-md-1">신고인</a></div>
				<?php } ?>
			</div>
		</div>

		<ul class="na-table d-md-table w-100">
		<?php
			$list_cnt = count($list);
			for ($i=0; $i < $list_cnt; $i++) {

				// 글 구분
				if($list[$i]['comment']) {
					$list[$i]['wr_subject'] = '댓글 <span class="na-bar"></span> '.$list[$i]['wr_subject'];
				}

				// 아이콘
				if ($list[$i]['flag']) {
					$wr_icon = '<span class="na-icon na-secret"></span>';
				} else if ((strtotime($list[$i]['regdate']) + 86400) >= G5_SERVER_TIME) {
					$wr_icon = '<span class="na-icon na-new"></span>';
				} else {
					$wr_icon = '';
				}

			?>
			<li class="d-md-table-row px-3 py-2 p-md-0 text-md-center text-muted border-bottom">
				<div class="d-none d-md-table-cell nw-5 f-sm font-weight-normal py-md-2 px-md-1">
					<span class="sr-only">번호</span>
					<?php echo $list[$i]['num'] ?>
				</div>
				<div class="d-md-table-cell text-left py-md-2 pr-md-1">
					<div class="na-title">
						<div class="na-item">
							<?php if ($is_admin) { ?>
								<input type="checkbox" class="mb-0 mr-2" name="chk_bn_id[]" value="<?php echo $i ?>" id="chk_bn_id_<?php echo $i ?>">
								<input type="hidden" name="bo_table[<?php echo $i ?>]" value="<?php echo $list[$i]['bo_table'] ?>">
								<input type="hidden" name="wr_id[<?php echo $i ?>]" value="<?php echo $list[$i]['wr_id'] ?>">
							<?php } ?>
							<a href="<?php echo $list[$i]['href'] ?>" class="na-subject">
								<?php echo $wr_icon ?>
								<?php echo $list[$i]['wr_subject'] ?>
							</a>
							<?php if(!$list[$i]['comment'] && $list[$i]['wr_comment']) { ?>
								<div class="na-info">
									<span class="sr-only">댓글</span>
									<span class="count-plus orangered">
										<?php echo $list[$i]['wr_comment']; ?>
									</span>
								</div>
							<?php } ?>
						</div>
					</div>
					<?php 
					// 신고인
					if ($is_admin && $list[$i]['mb_ids']) { 
						$mb_ids = '';
						$arr = explode(",", $list[$i]['mb_ids']);
						$arr_cnt = count($arr);
						for($z=0; $z < $arr_cnt; $z++) {
							if($z > 0) $mb_ids .= ', ';
							$mb_ids .= '<a onclick="win_profile(this.href); return false;" href="'.G5_BBS_URL.'/profile.php?mb_id='.urlencode($arr[$z]).'">'.$arr[$z].'</a>'.PHP_EOL;
						}
					?>
						<div class="collapse" id="reporter_<?php echo $i ?>">
							<div class="f-sm text-black-50 bg-light border px-2 py-1 my-2 mb-md-0 mt-md-1">
								<?php echo $mb_ids ?>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="float-right float-md-none d-md-table-cell nw-10 nw-md-auto text-left f-sm font-weight-normal pl-2 py-md-2 pr-md-1">
					<span class="sr-only">작성자</span>
					<?php echo na_name_photo($list[$i]['mb_id'], $list[$i]['name']) ?>
				</div>
				<div class="float-left float-md-none d-md-table-cell nw-6 nw-md-auto f-sm font-weight-normal py-md-2 pr-3 pr-md-1">
					<span class="sr-only">접수일</span>
					<?php echo na_date($list[$i]['regdate'], 'orangered', 'H:i', 'm.d', 'm.d') ?>
				</div>
				<?php if ($is_admin) { ?>
					<div class="float-left float-md-none d-md-table-cell nw-5 nw-md-auto f-sm font-weight-normal py-md-2 pr-3 pr-md-1">
						<a data-toggle="collapse" href="#reporter_<?php echo $i ?>" aria-expanded="false" aria-controls="reporter_<?php echo $i ?>">
							<i class="fa fa-eye fa-md text-black-50"></i>
							<span class="sr-only">신고인 보기</span>
						</a>
					</div>
				<?php } ?>
				<div class="clearfix d-block d-md-none"></div>
			</li>
		<?php }  ?>
		</ul>
		<?php if (!$list_cnt) { ?>
			<div class="f-de font-weight-normal px-3 py-5 text-muted text-center border-bottom">게시물이 없습니다.</div>
		<?php } ?>
	</div>
	<!-- } 신고 목록 끝 -->
</form>

<!-- 신고 페이지네이션 시작 { -->
<div class="font-weight-normal px-3 px-sm-0 mb-4">
	<ul class="pagination justify-content-center en mb-0">
		<?php echo na_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "?page="); ?>
	</ul>
</div>
<!-- } 신고 페이지네이션 끝 -->

<?php if ($is_admin || IS_DEMO) { ?>
<div class="sr-only">
	<input type="checkbox" id="allCheck" onclick="if (this.checked) all_checked(true); else all_checked(false);">
</div>
<script>
function all_checked(sw) {
	var f = document.fshingolist;

	for (var i=0; i<f.length; i++) {
		if (f.elements[i].name == "chk_bn_id[]")
			f.elements[i].checked = sw;
	}
}
function fshingo_submit(f) {

	f.pressed.value = document.pressed;

	var cnt = 0;
	for (var i=0; i<f.length; i++) {
		if (f.elements[i].name == "chk_bn_id[]" && f.elements[i].checked)
			cnt++;
	}

	if (!cnt) {
		alert(document.pressed+"할 게시물을 하나 이상 선택하세요.");
		return false;
	}

	if(document.pressed == "잠금처리") {
		if (!confirm("선택한 게시물을 정말 "+document.pressed+" 하시겠습니까?\n\n잠금처리시 해당 게시물은 비밀글로 자동 전환 됩니다.")) {
			return false;
		}
	}

	if(document.pressed == "잠금해제") {
		if (!confirm("선택한 게시물을 정말 "+document.pressed+" 하시겠습니까?\n\n잠금해제시 신고 내역은 자동 삭제가 됩니다.")) {
			return false;
		}
	}

	if(document.pressed == "선택삭제") {
		if (!confirm("선택한 게시물을 정말 "+document.pressed+" 하시겠습니까?\n\n게시물과 신고 내역 모두 삭제되며, 한번 삭제한 자료는 복구할 수 없습니다.")) {
			return false;
		}
	}

	f.action = "./shingo_delete.php";

	return true;
}
</script>
<?php } ?>
