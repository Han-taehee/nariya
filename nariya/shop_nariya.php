<?php
if (!defined('_GNUBOARD_')) exit;

// 기본환경설정 여분필드 1번에 쇼핑몰 테마 지정

if(!$config['cf_1'])
	return;

$config['tmp_theme'] = $config['cf_theme'];
$config['cf_theme'] = $config['cf_1'];

if(defined('G5_USE_SHOP') && G5_USE_SHOP) {
	$na_shop_file = basename($_SERVER['SCRIPT_FILENAME']);
	$na_shop_arr = array('personalpayform.php', 'personalpayformupdate.php');
	if(in_array($na_shop_file, $na_shop_arr)) {
		$bo_table = (isset($_REQUEST['pp_bo_table']) && !is_array($_REQUEST['pp_bo_table'])) ? $_REQUEST['pp_bo_table'] : get_session('pp_bo_table');
		if($bo_table) {
			$bo_table = preg_replace('/[^a-z0-9_]/i', '', trim($bo_table));
			$bo_table = substr($bo_table, 0, 20);
			if($bo_table) {
				define('NA_SHOP_FILE', G5_PATH.'/nariya/extend/personalpay/'.$na_shop_file);
				$config['cf_theme'] = $config['tmp_theme'];
			}
		}
	}
}