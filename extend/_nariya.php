<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 나리야 테마가 아닐 경우 작동
if (!defined('NA_URL')) {
	if(defined('NA_SHOP_FILE') && NA_SHOP_FILE) {
		include_once(G5_PATH.'/nariya/nariya.php');

		// Extend 재실행
		foreach($extend_file as $mfile) {
			include_once(G5_EXTEND_PATH.'/'.$mfile);
		}		
		unset($mfile);
		unset($extend_file);

		ob_start();

		// 자바스크립트에서 go(-1) 함수를 쓰면 폼값이 사라질때 해당 폼의 상단에 사용하면
		// 캐쉬의 내용을 가져옴. 완전한지는 검증되지 않음
		header('Content-Type: text/html; charset=utf-8');
		$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
		header('Expires: 0'); // rfc2616 - Section 14.21
		header('Last-Modified: ' . $gmnow);
		header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
		header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
		header('Pragma: no-cache'); // HTTP/1.0

		run_event('common_header');

		$html_process = new html_process();
				
		include_once(NA_SHOP_FILE);

		exit;		
	}

	return;
}

// 비회원 접근 페이지 차단용
$phref = '';

// 모바일 스킨 경로 조정
if(G5_IS_MOBILE) {
	if(isset($board['bo_mobile_skin']) && $board['bo_mobile_skin'] === 'PC-Skin') {
		$board_skin_path    = na_skin_path('board', $board['bo_skin']);
		$board_skin_url     = na_skin_url('board', $board['bo_skin']);
	}
	if($config['cf_mobile_member_skin'] === 'PC-Skin') {
		$member_skin_path   = na_skin_path('member', $config['cf_member_skin']);
		$member_skin_url    = na_skin_url('member', $config['cf_member_skin']);
	}
	if($config['cf_mobile_new_skin'] === 'PC-Skin') {
		$new_skin_path      = na_skin_path('new', $config['cf_new_skin']);
		$new_skin_url       = na_skin_url('new', $config['cf_new_skin']);
	}
	if($config['cf_mobile_search_skin'] === 'PC-Skin') {
		$search_skin_path   = na_skin_path('search', $config['cf_search_skin']);
		$search_skin_url    = na_skin_url('search', $config['cf_search_skin']);
	}
	if($config['cf_mobile_connect_skin'] === 'PC-Skin') {
		$connect_skin_path  = na_skin_path('connect', $config['cf_connect_skin']);
		$connect_skin_url   = na_skin_url('connect', $config['cf_connect_skin']);
	}
	if($config['cf_mobile_faq_skin'] === 'PC-Skin') {
		$faq_skin_path      = na_skin_path('faq', $config['cf_faq_skin']);
		$faq_skin_url       = na_skin_url('faq', $config['cf_faq_skin']);
	}
}

// 회원 전용
if(isset($nariya['mb_only']) && $nariya['mb_only'])
	include_once(NA_PATH.'/bbs/member_only.php');

// 로그인 경험치
if(IS_NA_XP && isset($nariya['xp_login']) && $nariya['xp_login']) {
	if(isset($member['mb_today_login']) && substr($member['mb_today_login'], 0, 10) != G5_TIME_YMD) {
		na_insert_xp($member['mb_id'], $nariya['xp_login'], G5_TIME_YMD.' 로그인', '@login', $member['mb_id'], G5_TIME_YMD);
	}
}

// 게시판 설정
if(isset($board['bo_table']) && $board['bo_table']) {
	$boset = na_skin_config('board', $board['bo_table']);
	if($is_member && !$is_admin && isset($boset['bo_admin']) && $boset['bo_admin'])
		na_admin($boset['bo_admin'], 1);

	// 데모용
	if(IS_DEMO) {
		@include_once(NA_PATH.'/extend/demo/config_board.php');
	}

	// 게시판 자체 Hooks
	@include_once($board_skin_path.'/_hooks.php');

	// board.php 에서만 실행
	if(basename($_SERVER['SCRIPT_FILENAME']) == 'board.php')
		@include_once($board_skin_path.'/_extend.php');
}

// 게시판 플러그인
$na_extend_file = '';
$na_extend_url = G5_URL . str_replace(G5_PATH, '', $_SERVER['SCRIPT_FILENAME']);
if(IS_NA_BBS && strpos($na_extend_url, G5_BBS_URL) !== false) {
	// 원본 수정파일 체크
	$na_bbs_file = basename($_SERVER['SCRIPT_FILENAME']);
	$na_bbs_arr = array('board.php', 'write.php', 'move_update.php');
	if(in_array($na_bbs_file, $na_bbs_arr)) {
		$na_extend_file = NA_PATH.'/bbs/'.$na_bbs_file;
	}
} else if(IS_YC) {
	if(strpos($na_extend_url, G5_SHOP_URL) !== false || strpos($na_extend_url, G5_MSHOP_URL) !== false) {
		$na_shop_file = basename($_SERVER['SCRIPT_FILENAME']);
		$na_shop_arr = array('personalpayform.php', 'personalpayformupdate.php');
		if(in_array($na_shop_file, $na_shop_arr)) {
			$bo_table = (isset($_REQUEST['pp_bo_table']) && !is_array($_REQUEST['pp_bo_table'])) ? $_REQUEST['pp_bo_table'] : get_session('pp_bo_table');
			if($bo_table) {
				$bo_table = preg_replace('/[^a-z0-9_]/i', '', trim($bo_table));
				$bo_table = substr($bo_table, 0, 20);
				if($bo_table) {
					$na_extend_file = NA_PATH.'/extend/personalpay/'.$na_shop_file;
				}
			}
		}
	}
}

if($na_extend_file) {
	// Extend 재실행
	foreach($extend_file as $mfile) {
		include_once(G5_EXTEND_PATH.'/'.$mfile);
	}		
	unset($mfile);
	unset($extend_file);

	ob_start();

	// 자바스크립트에서 go(-1) 함수를 쓰면 폼값이 사라질때 해당 폼의 상단에 사용하면
	// 캐쉬의 내용을 가져옴. 완전한지는 검증되지 않음
	header('Content-Type: text/html; charset=utf-8');
	$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
	header('Expires: 0'); // rfc2616 - Section 14.21
	header('Last-Modified: ' . $gmnow);
	header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
	header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
	header('Pragma: no-cache'); // HTTP/1.0

	run_event('common_header');

	$html_process = new html_process();
			
	include_once($na_extend_file);

	exit;		
}