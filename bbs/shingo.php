<?php
include_once('./_common.php');

if(!$is_member) {
	alert('로그인한 회원만 이용 가능합니다.', G5_BBS_URL.'/login.php?url='.urlencode('./shingo.php'));
}

// 스킨경로
$nariya['shingo_skin'] = isset($nariya['shingo_skin']) ? $nariya['shingo_skin'] : '';
$shingo_skin = na_fid($nariya['shingo_skin']);
$shingo_skin_path = NA_PATH.'/skin/shingo/'.$shingo_skin;
$shingo_skin_url = NA_URL.'/skin/shingo/'.$shingo_skin;

$g5['title'] = '신고모음';
include_once('./_head.php');

// 스킨 설정값
$wset = na_skin_config('shingo');

if($is_admin) {
	$sql_where = "";
	$sql_orderby = "a.flag desc,";
} else {
	$sql_where = "and a.flag = '0'";
	$sql_orderby = "";
}
$sql_common = " from {$g5['na_shingo']} a, {$g5['board_table']} b where a.bo_table = b.bo_table $sql_where ";

$row = sql_fetch(" select count(*) as cnt {$sql_common} ");
$total_count = $row['cnt'];

$rows = G5_IS_MOBILE ? $config['cf_mobile_page_rows'] : $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$list = array();
$sql = " select a.*, b.bo_subject {$sql_common} order by $sql_orderby a.id desc limit {$from_record}, {$rows} ";
$result = sql_query($sql);
if($result) {
	for ($i=0; $row=sql_fetch_array($result); $i++) {
		$tmp_write_table = $g5['write_prefix'].$row['bo_table'];

		if (!$row['wr_parent']) {

			// 원글
			$comment = "";
			$comment_link = "";
			$row2 = sql_fetch(" select * from {$tmp_write_table} where wr_id = '{$row['wr_id']}' ");
			$list[$i] = $row2;

			$onclick = "na_shingo('".$row['bo_table']."', '".$row['wr_id']."');";
			$name = get_sideview($row2['mb_id'], get_text(cut_str($row2['wr_name'], $config['cf_cut_name'])), $row2['wr_email'], $row2['wr_homepage']);

		} else {

			// 코멘트
			$comment = '[코] ';
			$comment_link = '#c_'.$row['wr_id'];
			$row2 = sql_fetch(" select * from {$tmp_write_table} where wr_id = '{$row['wr_parent']}' ");
			$row3 = sql_fetch(" select mb_id, wr_name, wr_email, wr_homepage, wr_datetime from {$tmp_write_table} where wr_id = '{$row['wr_id']}' ");
			$list[$i] = $row2;
			$list[$i]['wr_id'] = $row['wr_id'];
			$list[$i]['mb_id'] = $row3['mb_id'];
			$list[$i]['wr_name'] = $row3['wr_name'];
			$list[$i]['wr_email'] = $row3['wr_email'];
			$list[$i]['wr_homepage'] = $row3['wr_homepage'];

			$onclick = "na_shingo('".$row['bo_table']."', '".$row['wr_id']."', '".$row['wr_parent']."');";
			$name = get_sideview($row3['mb_id'], get_text(cut_str($row3['wr_name'], $config['cf_cut_name'])), $row3['wr_email'], $row3['wr_homepage']);
		}

		$list[$i]['num'] = $total_count - ($page - 1) * $rows - $i;
		$list[$i]['flag'] = $row['flag'];
		$list[$i]['mb_ids'] = $row['mb_id'];
		$list[$i]['onclick'] = $onclick;
		$list[$i]['bo_table'] = $row['bo_table'];
		$list[$i]['name'] = $name;
		$list[$i]['comment'] = $comment;
		$list[$i]['href'] = get_pretty_url($row['bo_table'], $row2['wr_id'], $comment_link);
		$list[$i]['bo_subject'] = get_text($row['bo_subject']);
		$list[$i]['wr_subject'] = get_text($row2['wr_subject']);
		$list[$i]['regdate'] = $row['regdate'];
	}
}

$admin_href = '';
if($is_admin || IS_DEMO) {
	if(is_file($shingo_skin_path.'/setup.skin.php')) {
		$admin_href = na_setup_href('shingo');
	}
}

$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "?page=");

include_once($shingo_skin_path.'/shingo.skin.php');

include_once('./_tail.php');