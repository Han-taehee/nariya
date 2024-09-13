<?php
$sub_menu = "800100";
include_once('./_common.php');

check_demo();

auth_check_menu($auth, $sub_menu, 'w');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

check_admin_token();

// 기본 폴더 체크
$save_path = G5_DATA_PATH.'/'.NA_DIR;
if(is_dir($save_path)) {
	; // 통과
} else {
	@mkdir($save_path, G5_DIR_PERMISSION);
	@chmod($save_path, G5_DIR_PERMISSION);
}

// 영상폴더 체크
$video_path = $save_path.'/video';
if(is_dir($video_path)) {
	; //통과
} else {
	@mkdir($video_path, G5_DIR_PERMISSION);
	@chmod($video_path, G5_DIR_PERMISSION);
}

// 알림(내글반응)
if(isset($_POST['na']['noti']) && $_POST['na']['noti'] && !isset($member['as_noti'])) {

	// 회원정보 테이블에 필드 추가
	sql_query(" ALTER TABLE `{$g5['member_table']}`
					ADD `as_noti` int(11) NOT NULL DEFAULT '0' AFTER `mb_10` ", false);

	// 알림(내글반응) 테이블 추가
	$na_db_set = na_db_set();

	if(!sql_query(" DESC {$g5['na_noti']} ", false)) {
		sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['na_noti']}` (
					  `ph_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					  `ph_to_case` varchar(50) NOT NULL DEFAULT '',
					  `ph_from_case` varchar(50) NOT NULL DEFAULT '',
					  `bo_table` varchar(20) NOT NULL DEFAULT '',
					  `rel_bo_table` varchar(20) NOT NULL DEFAULT '',
					  `wr_id` int(11) NOT NULL DEFAULT 0,
					  `rel_wr_id` int(11) NOT NULL DEFAULT 0,
					  `mb_id` varchar(255) NOT NULL DEFAULT '',
					  `rel_mb_id` varchar(255) NOT NULL DEFAULT '',
					  `rel_mb_nick` varchar(255) DEFAULT NULL,
					  `rel_msg` varchar(255) NOT NULL DEFAULT '',
					  `rel_url` varchar(200) NOT NULL DEFAULT '',
					  `ph_readed` char(1) NOT NULL DEFAULT 'N',
					  `ph_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					  `parent_subject` varchar(255) NOT NULL,
					  `wr_parent` int(11) DEFAULT 0,
					  PRIMARY KEY (`ph_id`)
				) ".$na_db_set."; ", false);
	}
}

// 게시판 플러그인 관련 DB 테이블 및 필드 추가
$is_bbs_db = true;
if(!$nariya['bbs'] && isset($_POST['na']['bbs']) && $_POST['na']['bbs']) {
	include_once(NA_PATH.'/extend/bbs/db.php');
}

// 멤버십 플러그인 관련 DB 테이블 및 필드 추가
$is_xp_db = true;
if(!$nariya['xp'] && isset($_POST['na']['xp']) && $_POST['na']['xp']) {
	include_once(NA_PATH.'/extend/membership/db.php');
}			

// 레벨 아이콘 확장자
if(isset($_POST['na']['lvl_skin']) && $_POST['na']['lvl_skin']) {
	$lvl_skin_path = NA_PATH.'/skin/level/'.$_POST['na']['lvl_skin'];
	if(is_file($lvl_skin_path.'/1.png')) {
		$_POST['na']['lvl_ext'] = 'png';
	} else if(is_file($lvl_skin_path.'/1.jpg')) {
		$_POST['na']['lvl_ext'] = 'jpg';
	}
}

// 설정값
$na = array();
$na = $_POST['na'];
na_file_var_save($save_path.'/nariya.php', $na, 'nariya'); //data 폴더 체크

goto_url('./config_form.php', false);