<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 게시판 스킨
$bo_skin_demo = '';
if (isset($_REQUEST['bo_skin']) && $_REQUEST['bo_skin']) {
	$tmp_bo_skin = na_fid($_REQUEST['bo_skin']);
	if($tmp_bo_skin) {
		$tmp_board_skin_path = na_skin_path('board', $tmp_bo_skin);
		if(is_dir($tmp_board_skin_path)) {
			$board['bo_skin'] = $tmp_bo_skin;
			$board_skin_path = $tmp_board_skin_path;
			$board_skin_url = na_skin_url('board', $tmp_bo_skin);

			// 목록 스타일 초기화
			if(na_fid(get_session($bo_table.'_skin')) != $tmp_bo_skin) {
				set_session($bo_table.'_list', '');
			}
			set_session($bo_table.'_skin', $tmp_bo_skin);
			$bo_skin_demo = $tmp_bo_skin;
		}
	}
} else {
	$tmp_bo_skin = na_fid(get_session($bo_table.'_skin'));
	if($tmp_bo_skin) {
		$tmp_board_skin_path = na_skin_path('board', $tmp_bo_skin);
		if(is_dir($tmp_board_skin_path)) {
			$board['bo_skin'] = $tmp_bo_skin;
			$board_skin_path = $tmp_board_skin_path;
			$board_skin_url = na_skin_url('board', $tmp_bo_skin);
			$bo_skin_demo = $tmp_bo_skin;
		}
	}
}

// 게시판 공통 설정값
$boset['na_video_link'] = 1;
$boset['na_crows'] = 15;
$boset['na_cgood'] = 1;
$boset['na_cnogood'] = 1;
$boset['na_shingo'] = 1000;
$boset['na_tag'] = 1;
$boset['no_img'] = './nariya/img/no_image.gif';
$boset['popover'] = 1;