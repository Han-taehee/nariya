<?php
include_once('./_common.php');

check_demo();

if ($is_admin != 'super')
    alert_close('접근권한이 없습니다.');

$mode = isset($mode) ? $mode : '';
$fid = isset($fid) ? $fid : '';
$img = isset($img) ? $img : '';
$win = isset($win) ? $win : '';

$mode = na_fid($mode);
$fid = na_fid($fid);

if(!$mode || !$fid || !$img) {
    alert_close('값이 제대로 넘어오지 않았습니다.');
}

if (!preg_match("/(\.(jpg|jpeg|gif|png))$/i", $img))
	alert('삭제 가능한 파일이 아닙니다.');

// 이미지 주소
$img_file = G5_THEME_PATH.'/storage/image/'.$img;
$img_file = na_file_path_check($img_file);

if(!file_exists($img_file))
	alert('파일이 존재하지 않습니다.');

// 썸네일 삭제를 위해 파일명 체크
$name = basename($img_file);
$name = substr($name, 0, strrpos($name, '.'));

// 이미지 리스트 정리
$arr = array();
$list = array();

// 썸네일 체크
$arr = na_file_list(G5_THEME_PATH.'/storage/image');

$arr_cnt = is_array($arr) ? count($arr) : 0;

$i=0;
for($j=0; $j < $arr_cnt; $j++) {

	$tmp = isset($arr[$j]) ? $arr[$j] : '';

	if(!$tmp)
		continue;

	if(!preg_match("/(\.(jpg|jpeg|gif|png))$/i", $tmp))
		continue;

	list($head) = explode('-', $tmp);

	if($head != 'thumb')
		continue;

	if(strpos($tmp, $name) === false)
		continue;

	$list[$i] = $tmp;
	$i++;
}

// 썸네일 삭제
$list_cnt = is_array($list) ? count($list) : 0;
for($i=0; $i < $list_cnt; $i++) {

	if(!isset($list[$i]) || !$list[$i])
		continue;

	$del_file = G5_THEME_PATH.'/storage/image/'.$list[$i];

	@chmod($del_file, G5_FILE_PERMISSION);
	@unlink($del_file);
}

// 본 이미지 삭제
@chmod($img_file, G5_FILE_PERMISSION);
@unlink($img_file);

if($win) {
	goto_url('./image_form_win.php?mode='.urlencode($mode).'&amp;fid='.urlencode($fid));
} else {
	goto_url('./image_form.php?mode='.urlencode($mode).'&amp;fid='.urlencode($fid));
}