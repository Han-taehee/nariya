<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$board = get_board_db($bo_table, true);
if (isset($board['bo_table']) && $board['bo_table']) {
	set_cookie("ck_bo_table", $board['bo_table'], 86400 * 1);
	$gr_id = $board['gr_id'];
	$write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름

    // 게시판에서 
    if (isset($board['bo_select_editor']) && $board['bo_select_editor']){
        $config['cf_editor'] = $board['bo_select_editor'];
    }
	set_session('pp_bo_table', $board['bo_table']);
} else {
	set_session('pp_bo_table', '');
	alert('존재하지 않는 게시판입니다.');
}

if (G5_IS_MOBILE) {
	if(isset($board['bo_mobile_skin']) && $board['bo_mobile_skin'] === 'PC-Skin') {
		$board_skin_path    = na_skin_path('board', $board['bo_skin']);
		$board_skin_url     = na_skin_url('board', $board['bo_skin']);
	} else {
	    $board_skin_path    = get_skin_path('board', $board['bo_mobile_skin']);
		$board_skin_url     = get_skin_url('board', $board['bo_mobile_skin']);
	}
} else {
    $board_skin_path    = get_skin_path('board', $board['bo_skin']);
    $board_skin_url     = get_skin_url('board', $board['bo_skin']);
}

$skin_file = $board_skin_path.'/pp_form.skin.php';
if(!is_file($skin_file))
	alert('잘못된 접속입니다.');

$boset = na_skin_config('board', $board['bo_table']);

$g5['title'] = ((G5_IS_MOBILE && $board['bo_mobile_subject']) ? $board['bo_mobile_subject'] : $board['bo_subject']);

include_once(G5_PATH.'/head.php');

include_once($skin_file);

include_once(G5_PATH.'/tail.php');