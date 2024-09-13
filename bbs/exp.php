<?php
include_once('./_common.php');

if ($is_guest)
    alert_close('회원만 조회하실 수 있습니다.');

$g5['title'] = get_text($member['mb_nick']).' 님의 경험치 내역';
include_once(G5_PATH.'/head.sub.php');

// 스킨경로
$nariya['exp_skin'] = isset($nariya['exp_skin']) ? $nariya['exp_skin'] : '';
$exp_skin = na_fid($nariya['exp_skin']);
if(defined('NA_URL')) {
	$exp_skin_path = NA_PATH.'/skin/exp/'.$exp_skin;
	$exp_skin_url = NA_URL.'/skin/exp/'.$exp_skin;
} else if(defined('NA_PLUGIN_URL')) {
	$exp_skin_path = NA_PLUGIN_PATH.'/skin/exp/'.$exp_skin;
	$exp_skin_url = NA_PLUGIN_URL.'/skin/exp/'.$exp_skin;
} else {
	alert('잘못된 접근입니다.', G5_URL);
}

$list = array();

$sql_common = " from {$g5['na_xp']} where mb_id = '".escape_trim($member['mb_id'])."' ";
$sql_order = " order by xp_id desc ";

$sql = " select count(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = G5_IS_MOBILE ? $config['cf_mobile_page_rows'] : $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

@include_once($exp_skin_path.'/exp.skin.php');

include_once(G5_PATH.'/tail.sub.php');