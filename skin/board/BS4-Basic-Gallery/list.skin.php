<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);

// 스킨설정
$is_skin_setup = (($is_admin == 'super' || IS_DEMO) && is_file($board_skin_path.'/setup.skin.php')) ? true : false;

// 이미지 영역 및 썸네일 크기 설정
$boset['thumb_w'] = (!isset($boset['thumb_w']) || $boset['thumb_w'] == "") ? 400 : (int)$boset['thumb_w'];
$boset['thumb_h'] = (!isset($boset['thumb_h']) || $boset['thumb_h'] == "") ? 225 : (int)$boset['thumb_h'];

if($boset['thumb_w'] && $boset['thumb_h']) {
	$img_height = ($boset['thumb_h'] / $boset['thumb_w']) * 100;
} else {
	$img_height = (isset($boset['thumb_d']) && $boset['thumb_d']) ? $boset['thumb_d'] : '56.25';
}

$head_color = (isset($boset['head_color']) && $boset['head_color']) ? $boset['head_color'] : 'primary';

$boset['xl'] = isset($boset['xl']) ? (int)$boset['xl'] : 0;
$boset['lg'] = isset($boset['lg']) ? (int)$boset['lg'] : 0;
$boset['md'] = isset($boset['md']) ? (int)$boset['md'] : 3;
$boset['sm'] = isset($boset['sm']) ? (int)$boset['sm'] : 0;
$boset['xs'] = isset($boset['xs']) ? (int)$boset['xs'] : 2;
$gallery_row_cols = na_row_cols($boset['xs'], $boset['sm'], $boset['md'], $boset['lg'], $boset['xl']);

// 글 이동
$is_list_link = false;
$boset['target'] = isset($boset['target']) ? $boset['target'] : '';
switch($boset['target']) {
	case '1' : $target = ' target="_blank"'; break;
	case '2' : $is_list_link = true; break;
	case '3' : $target = ' target="_blank"'; $is_list_link = true; break;
	default	 : $target = ''; break; 
}

// No 이미지
$no_img = isset($boset['no_img']) ? na_url($boset['no_img']) : '';

// 글 수
$list_cnt = count($list);

?>

<!-- 게시판 목록 시작 { -->
<div id="bo_list_wrap" class="mb-4">

	<!-- 검색창 시작 { -->
	<div id="bo_search" class="collapse<?php echo ((isset($boset['search_open']) && $boset['search_open']) || $stx) ? ' show' : ''; ?>">
		<div class="alert bg-light border p-2 p-sm-3 mb-3 mx-3 mx-sm-0">
			<form id="fsearch" name="fsearch" method="get" class="m-auto" style="max-width:600px;">
				<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
				<input type="hidden" name="sca" value="<?php echo $sca ?>">
				<div class="form-row mx-n1">
					<div class="col-6 col-sm-3 px-1">
						<label for="sfl" class="sr-only">검색대상</label>
						<select name="sfl" class="custom-select">
							<?php echo get_board_sfl_select_options($sfl); ?>
						</select>
					</div>
					<div class="col-6 col-sm-3 px-1">
						<select name="sop" class="custom-select">
							<option value="and"<?php echo get_selected($sop, "and") ?>>그리고</option>
							<option value="or"<?php echo get_selected($sop, "or") ?>>또는</option>
						</select>	
					</div>
					<div class="col-12 col-sm-6 pt-2 pt-sm-0 px-1">
						<label for="stx" class="sr-only">검색어</label>
						<div class="input-group">
							<input type="text" id="bo_stx" name="stx" value="<?php echo stripslashes($stx) ?>" required class="form-control" placeholder="검색어를 입력해 주세요.">
							<div class="input-group-append">
								<button type="submit" class="btn btn-primary" title="검색하기">
									<i class="fa fa-search" aria-hidden="true"></i>
									<span class="sr-only">검색하기</span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- } 검색창 끝 -->

    <?php 
	// 게시판 카테고리
	if ($is_category) 
		include_once($board_skin_path.'/category.skin.php'); 
	?>

	<form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
		<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
		<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
		<input type="hidden" name="stx" value="<?php echo $stx ?>">
		<input type="hidden" name="spt" value="<?php echo $spt ?>">
		<input type="hidden" name="sca" value="<?php echo $sca ?>">
		<input type="hidden" name="sst" value="<?php echo $sst ?>">
		<input type="hidden" name="sod" value="<?php echo $sod ?>">
		<input type="hidden" name="page" value="<?php echo $page ?>">
		<input type="hidden" name="sw" value="">

		<!-- 게시판 페이지 정보 및 버튼 시작 { -->
		<div id="bo_btn_top" class="clearfix f-de font-weight-normal mb-2">
			<div class="d-sm-flex align-items-center">
				<div id="bo_list_total" class="flex-sm-grow-1">
					<div class="px-3 px-sm-0">
						<?php echo (isset($sca) && $sca) ? $sca : '전체'; ?>
						<b><?php echo number_format((int)$total_count) ?></b> / <?php echo $page ?> 페이지
					</div>
					<div class="d-block d-sm-none border-top my-2"></div>
				</div>
				<div class="px-3 px-sm-0 text-right">
					<?php if ($is_admin == 'super' || $admin_href || $is_auth || IS_DEMO) {  ?>
						<div class="btn-group" role="group">
							<button type="button" class="btn btn_admin nofocus dropdown-toggle dropdown-toggle-empty dropdown-toggle-split p-1" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" title="게시판 관리 옵션">
								<i class="fa fa-cog fa-spin fa-fw fa-md" aria-hidden="true"></i>
								<span class="sr-only">게시판 관리 옵션</span>
							</button>
							<div class="dropdown-menu dropdown-menu-right p-0 border-0 bg-transparent text-right">
								<div class="btn-group-vertical">
									<?php if ($admin_href) { ?>
										<a href="<?php echo $admin_href ?>" class="btn btn-primary py-2" role="button">
											<i class="fa fa-cog fa-fw" aria-hidden="true"></i> 보드설정
										</a>
									<?php } ?>
									<?php if($is_skin_setup) { ?>
										<a href="<?php echo na_setup_href('board', $bo_table) ?>" class="btn btn-primary btn-setup py-2" role="button">
											<i class="fa fa-cogs fa-fw" aria-hidden="true"></i> 스킨설정
										</a>
									<?php } ?>
									<?php if ($is_checkbox) { ?>
										<a href="javascript:;" class="btn btn-primary py-2" role="button">
											<label class="p-0 m-0" for="allCheck">
												<i class="fa fa-check-square-o fa-fw" aria-hidden="true"></i> 
												전체선택						
											</label>
											<div class="sr-only">
												<input type="checkbox" id="allCheck" onclick="if (this.checked) all_checked(true); else all_checked(false);">
											</div>
										</a>
										<button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-primary py-2">
											<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> 
											선택삭제
										</button>
										<button type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value" class="btn btn-primary py-2">
											<i class="fa fa-files-o fa-fw" aria-hidden="true"></i> 
											선택복사
										</button>
										<button type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value" class="btn btn-primary py-2">
											<i class="fa fa-arrows fa-fw" aria-hidden="true"></i>
											선택이동
										</button>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php }  ?>
					<?php if ($rss_href) { ?>
						<a href="<?php echo $rss_href ?>" class="btn btn_b01 nofocus p-1" title="RSS">
							<i class="fa fa-rss fa-fw fa-md" aria-hidden="true"></i>
							<span class="sr-only">RSS</span>
						</a>
					<?php } ?>
					<div class="btn-group" role="group">
						<button type="button" class="btn btn_b01 nofocus dropdown-toggle dropdown-toggle-empty dropdown-toggle-split p-1" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" title="게시물 정렬">
							<?php
								switch($sst) {
									case 'wr_datetime'	:	$sst_icon = 'history'; 
															$sst_txt = '날짜순 정렬'; 
															break;
									case 'wr_hit'		:	$sst_icon = 'eye'; 
															$sst_txt = '조회순 정렬'; 
															break;
									case 'wr_good'		:	$sst_icon = 'thumbs-o-up'; 
															$sst_txt = '추천순 정렬'; 
															break;
									case 'wr_nogood'	:	$sst_icon = 'thumbs-o-down'; 
															$sst_txt = '비추천순 정렬'; 
															break;
									default				:	$sst_icon = 'sort-numeric-desc'; 
															$sst_txt = '게시물 정렬'; 
															break;
								}
							?>
							<i class="fa fa-<?php echo $sst_icon ?> fa-fw fa-md" aria-hidden="true"></i>
							<span class="sr-only"><?php echo $sst_txt ?></span>
						</button>
						<div class="dropdown-menu dropdown-menu-right p-0 border-0 bg-transparent text-right">
							<div class="btn-group-vertical bg-white border rounded py-1">
								<?php echo str_replace('>', ' class="btn px-3 py-1 text-left" role="button">', subject_sort_link('wr_datetime', $qstr2, 1)) ?>
									날짜순
								</a>
								<?php echo str_replace('>', ' class="btn px-3 py-1 text-left" role="button">', subject_sort_link('wr_hit', $qstr2, 1)) ?>
									조회순
								</a>
								<?php if($is_good) { ?>
									<?php echo str_replace('>', ' class="btn px-3 py-1 text-left" role="button">', subject_sort_link('wr_good', $qstr2, 1)) ?>
										추천순
									</a>
								<?php } ?>
								<?php if($is_nogood) { ?>
									<?php echo str_replace('>', ' class="btn px-3 py-1 text-left" role="button">', subject_sort_link('wr_nogood', $qstr2, 1)) ?>
										비추천순
									</a>
								<?php } ?>
							</div>
						</div>
					</div>
					<button type="button" class="btn btn_b01 nofocus p-1" title="게시판 검색" data-toggle="collapse" data-target="#bo_search" aria-expanded="false" aria-controls="bo_search">
						<i class="fa fa-search fa-fw fa-md" aria-hidden="true"></i>
						<span class="sr-only">게시판 검색</span>
					</button>
					<?php if ($write_href && !$wr_id) { ?>
						<a href="<?php echo $write_href ?>" class="btn btn-primary nofocus py-1 ml-2" role="button">
							<i class="fa fa-pencil" aria-hidden="true"></i>
							쓰기
						</a>
					<?php } ?>
				</div>
			</div>
		</div>
		<!-- } 게시판 페이지 정보 및 버튼 끝 -->

		<!-- 게시물 목록 시작 { -->
		<section id="bo_list" class="mb-4">

			<!-- 목록 헤드 -->
			<div class="w-100 mb-0 bg-<?php echo $head_color ?>" style="height:4px;"></div>

			<ul class="na-table d-md-table w-100 mb-3">
			<?php
			// 공지
			if($board['bo_notice']) {
				for ($i=0; $i < $list_cnt; $i++) { 

					if(!$list[$i]['is_notice'])
						continue;

					$wr_icon = '';
					$is_lock = false;
					if ($list[$i]['icon_secret']) {
						$wr_icon = '<span class="na-icon na-secret"></span>';
						$is_lock = true;
					} else if ($list[$i]['icon_new']) {
						$wr_icon = '<span class="na-icon na-new"></span>';
					}

					// 현재 글
					$li_css = ($wr_id == $list[$i]['wr_id']) ? ' bg-light' : '';

					// 현재 글
					if($wr_id == $list[$i]['wr_id']) {
						$li_css = ' bg-light';
						$list[$i]['num'] = '<span class="na-text text-primary">열람</span>';
						$list[$i]['subject'] = '<b class="text-primary">'.$list[$i]['subject'].'</b>';
					} else {
						$li_css = '';
						$list[$i]['num'] = '<span class="na-notice bg-'.$head_color.'"></span><span class="sr-only">공지사항</span>';
						$list[$i]['subject'] = '<b>'.$list[$i]['subject'].'</b>';
					}
			?>
				<li class="d-md-table-row px-3 py-2 p-md-0 text-md-center text-muted border-bottom<?php echo $li_css;?>">
					<div class="d-none d-md-table-cell nw-5 f-sm font-weight-normal py-md-2 px-md-1">
						<?php echo $list[$i]['num'] ?>
					</div>
					<div class="d-md-table-cell text-left py-md-2 pr-md-1">
						<div class="na-title float-md-left">
							<div class="na-item">
								<?php if ($is_checkbox) { ?>
									<input type="checkbox" class="mb-0 mr-2" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
								<?php } ?>
								<a href="<?php echo $list[$i]['href'] ?>" class="na-subject">
									<?php echo $wr_icon; ?>
									<?php echo $list[$i]['subject'] ?>
								</a>
								<?php
									if(isset($list[$i]['icon_file']))
										echo '<span class="na-ticon na-file"></span>'.PHP_EOL;
								?>
								<?php if($list[$i]['wr_comment']) { ?>
									<div class="na-info mr-3">
										<span class="sr-only">댓글</span>
										<span class="count-plus orangered">
											<?php echo $list[$i]['wr_comment'] ?>
										</span>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="float-right float-md-none d-md-table-cell nw-10 nw-md-auto text-left f-sm font-weight-normal pl-2 py-md-2 pr-md-1">
						<span class="sr-only">등록자</span>
						<?php echo na_name_photo($list[$i]['mb_id'], $list[$i]['name']) ?>
					</div>
					<div class="float-left float-md-none d-md-table-cell nw-6 nw-md-auto f-sm font-weight-normal py-md-2 pr-md-1">
						<i class="fa fa-clock-o d-md-none" aria-hidden="true"></i>
						<span class="sr-only">등록일</span>
						<?php echo na_date($list[$i]['wr_datetime'], 'orangered', 'H:i', 'm.d', 'Y.m.d') ?>
					</div>
					<div class="float-left float-md-none d-md-table-cell nw-4 nw-md-auto f-sm font-weight-normal py-md-2 pr-md-1">
						<i class="fa fa-eye d-md-none" aria-hidden="true"></i>
						<span class="sr-only">조회</span>
						<?php echo $list[$i]['wr_hit'] ?>
					</div>
					<div class="clearfix d-block d-md-none"></div>
				</li>
			<?php 
				}
			} // 공지 ?>
			</ul>

			<div id="bo_gallery" class="px-3 px-sm-0 border-bottom mb-4">
				<ul class="row<?php echo $gallery_row_cols ?> mx-n2">
				<?php
				// 리스트
				$n = 0;
				$cap_new = (isset($boset['new']) && $boset['new']) ? $boset['new'] : 'primary';
				for ($i=0; $i < $list_cnt; $i++) { 

					// 공지는 제외	
					if($list[$i]['is_notice'])
						continue;

					// 글수 체크
					$n++;

					// 이미지용
					$wr_alt = get_text(str_replace('"', '', $list[$i]['wr_subject']));

					// 아이콘 체크
					$wr_icon = $wr_tack = $wr_cap = '';
					if ($list[$i]['icon_secret']) {
						$is_lock = true;
						$wr_icon = '<span class="na-icon na-secret"></span>';
					}

					// 링크 이동
					if($is_list_link && $list[$i]['wr_link1']) {
						$list[$i]['href'] = $list[$i]['link_href'][1];
					}

					// 전체 보기에서 분류 출력하기
					if(!$sca && $is_category && $list[$i]['ca_name']) {
						$list[$i]['subject'] = $list[$i]['ca_name'].' <span class="na-bar"></span> '.$list[$i]['subject'];
					}

					// 새 글, 현재 글 스타일
					$wr_now = '';
					if ($wr_id == $list[$i]['wr_id']) {
						$list[$i]['subject'] = '<b class="text-primary">'.$list[$i]['subject'].'</b>';
						$wr_now = '<div class="wr-now"></div>';
						$wr_cap = '<span class="label-cap en bg-orangered">Now</span>';
					} else if($list[$i]['icon_new']) {
						$wr_cap = '<span class="label-cap en bg-'.$cap_new.'">New</span>';
					}

					// 이미지 추출
					$img = na_wr_img($bo_table, $list[$i]);

					// 썸네일 생성
					$thumb = ($boset['thumb_w']) ? na_thumb($img, $boset['thumb_w'], $boset['thumb_h']) : $img;

					if(!$thumb && $no_img) {
						$thumb = $no_img;
					}
				?>
					<li class="col px-2 pb-4">
						<div class="img-wrap bg-light mb-2 na-round" style="padding-bottom:<?php echo $img_height ?>%;">
							<div class="img-item">
								<?php if ($is_checkbox) { ?>
									<span class="chk-box">
										<input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
									</span>
								<?php } ?>
								<a href="<?php echo $list[$i]['href'] ?>"<?php echo $target ?>>
									<?php echo $wr_now ?>
									<?php echo $wr_tack ?>
									<?php echo $wr_cap ?>
									<?php if($thumb) { ?>
										<img src="<?php echo $thumb ?>" alt="<?php echo $wr_alt ?>" class="img-render na-round">
									<?php } ?>
								</a>
							</div>
						</div>
						<div class="na-title">
							<div class="na-item">
								<a href="<?php echo $list[$i]['href'] ?>" class="na-subject"<?php echo $target ?>>
									<?php echo $wr_icon ?>
									<?php echo $list[$i]['subject'] ?>
								</a>
								<?php if($list[$i]['wr_comment']) { ?>
									<div class="na-info">
										<span class="sr-only">댓글</span>
										<span class="count-plus orangered">
											<?php echo $list[$i]['wr_comment'] ?>
										</span>
									</div>
								<?php } ?>
							</div>
						</div>

						<div class="clearfix font-weight-normal f-sm">
							<div class="float-right ml-2">
								<span class="sr-only">등록자</span>
								<?php echo na_name_photo($list[$i]['mb_id'], $list[$i]['name']) ?>
							</div>
							<div class="float-left text-muted">
								<i class="fa fa-clock-o" aria-hidden="true"></i>
								<span class="sr-only">등록일</span>
								<?php echo na_date($list[$i]['wr_datetime'], 'orangered', 'H:i', 'm.d', 'm.d') ?>
							</div>
						</div>
					</li>
				<?php } ?>
				</ul>
				<?php if(!$n) { ?>
					<div class="f-de px-3 py-5 text-muted text-center">
						게시물이 없습니다.
					</div>
				<?php } ?>
			</div>
		</section>
		<!-- } 게시물 목록 끝 -->

		<!-- 페이징 시작 { -->
		<div class="font-weight-normal px-3 px-sm-0">
			<ul class="pagination justify-content-center en mb-0">
				<?php if($prev_part_href) { ?>
					<li class="page-item"><a class="page-link" href="<?php echo $prev_part_href;?>">Prev</a></li>
				<?php } ?>
				<?php echo na_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, get_pretty_url($bo_table, '', $qstr.'&amp;page='));?>
				<?php if($next_part_href) { ?>
					<li class="page-item"><a  class="page-link" href="<?php echo $next_part_href;?>">Next</a></li>
				<?php } ?>
			</ul>
		</div>
		<!-- } 페이징 끝 -->
	</form>

</div>

<?php if ($is_checkbox) { ?>
<noscript>
<p align="center">자바스크립트를 사용하지 않는 경우 별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>

<script>
function all_checked(sw) {
	var f = document.fboardlist;

	for (var i=0; i<f.length; i++) {
		if (f.elements[i].name == "chk_wr_id[]")
			f.elements[i].checked = sw;
	}
}
function fboardlist_submit(f) {
	var chk_count = 0;

	for (var i=0; i<f.length; i++) {
		if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
			chk_count++;
	}

	if (!chk_count) {
		alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
		return false;
	}

	if(document.pressed == "선택복사") {
		select_copy("copy");
		return;
	}

	if(document.pressed == "선택이동") {
		select_copy("move");
		return;
	}

	if(document.pressed == "선택삭제") {
		if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다.\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
			return false;

		f.removeAttribute("target");
        f.action = g5_bbs_url+"/board_list_update.php";
	}

	return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
	var f = document.fboardlist;

	if (sw == "copy")
		str = "복사";
	else
		str = "이동";

	var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

	f.sw.value = sw;
	f.target = "move";
    f.action = g5_bbs_url+"/move.php";
	f.submit();
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->
