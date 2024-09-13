<?php
include_once('./_common.php');

check_demo();

if ($is_admin != 'super')
    alert("최고관리자만 접근이 가능합니다.");

$board = array();
$save_bo_table = array();
$save_wr_id = array();
$list_cnt = (isset($_POST['chk_bn_id']) && is_array($_POST['chk_bn_id'])) ? count($_POST['chk_bn_id']) : 0;
$btn_submit = isset($_POST['btn_submit']) ? $_POST['btn_submit'] : '';
if($btn_submit == '선택삭제') {

	for($i=0;$i < $list_cnt;$i++) {
		// 실제 번호를 넘김
		$k = isset($_POST['chk_bn_id'][$i]) ? $_POST['chk_bn_id'][$i] : '';

		$bo_table = isset($_POST['bo_table'][$k]) ? preg_replace('/[^a-z0-9_]/i', '', $_POST['bo_table'][$k]) : '';
		$wr_id    = isset($_POST['wr_id'][$k]) ? preg_replace('/[^0-9]/i', '', $_POST['wr_id'][$k]) : 0;

		$save_bo_table[$i] = $bo_table;
		$save_wr_id[$i] = $wr_id;

		$write_table = $g5['write_prefix'].$bo_table;

		if (isset($board['bo_table']) && $board['bo_table'] != $bo_table)
			$board = sql_fetch(" select bo_subject, bo_write_point, bo_comment_point, bo_notice from {$g5['board_table']} where bo_table = '$bo_table' ");

		$write = get_write($write_table, $wr_id);

		if(!$write) 
			continue;

		// 원글 삭제
		if ($write['wr_is_comment']==0)
		{
			$len = strlen($write['wr_reply']);
			if ($len < 0) $len = 0;
			$reply = substr($write['wr_reply'], 0, $len);

			// 나라오름님 수정 : 원글과 코멘트수가 정상적으로 업데이트 되지 않는 오류를 잡아 주셨습니다.
			$sql = " select wr_id, mb_id, wr_is_comment from $write_table where wr_parent = '{$write['wr_id']}' order by wr_id ";
			$result = sql_query($sql);
			if($result) {
				while ($row = sql_fetch_array($result)) {

					// 원글이라면
					if (!$row['wr_is_comment'])
					{
						if (!delete_point($row['mb_id'], $bo_table, $row['wr_id'], '쓰기'))
							insert_point($row['mb_id'], $board['bo_write_point'] * (-1), "{$board['bo_subject']} {$row['wr_id']} 글삭제");

						// 업로드된 파일이 있다면 파일삭제
						$sql2 = " select * from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['wr_id']}' ";
						$result2 = sql_query($sql2);
						if($result2) {
							while ($row2 = sql_fetch_array($result2))
								@unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row2['bf_file']);

						}
						// 파일테이블 행 삭제
						sql_query(" delete from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['wr_id']}' ");

						$count_write++;
					}
					else
					{
						// 코멘트 포인트 삭제
						if (!delete_point($row['mb_id'], $bo_table, $row['wr_id'], '코멘트'))
							insert_point($row['mb_id'], $board['bo_comment_point'] * (-1), "{$board['bo_subject']} {$write['wr_id']}-{$row['wr_id']} 코멘트삭제");

						$count_comment++;
					}
				}
			}

			// 게시글 삭제
			sql_query(" delete from $write_table where wr_parent = '{$write['wr_id']}' ");

			// 최근게시물 삭제
			sql_query(" delete from {$g5['board_new_table']} where bo_table = '$bo_table' and wr_parent = '{$write['wr_id']}' ");

			// 스크랩 삭제
			sql_query(" delete from {$g5['scrap_table']} where bo_table = '$bo_table' and wr_id = '{$write['wr_id']}' ");

			// 공지사항 삭제
			$notice_array = explode(",", trim($board['bo_notice']));
			$bo_notice = "";
			$lf = '';
			for ($k=0; $k<count($notice_array); $k++) {
				if ((int)$write['wr_id'] != (int)$notice_array[$k])
					$bo_notice .= $lf.$notice_array[$k];

				if($bo_notice)
					$lf = ',';
			}
			$bo_notice = trim($bo_notice);
			sql_query(" update {$g5['board_table']} set bo_notice = '$bo_notice' where bo_table = '$bo_table' ");

			if ($pressed == '선택삭제') {
				// 글숫자 감소
				if ($count_write > 0 || $count_comment > 0) {
					sql_query(" update {$g5['board_table']} set bo_count_write = bo_count_write - '$count_write', bo_count_comment = bo_count_comment - '$count_comment' where bo_table = '$bo_table' ");
				}
			}
		}
		else // 코멘트 삭제
		{
			//--------------------------------------------------------------------
			// 코멘트 삭제시 답변 코멘트 까지 삭제되지는 않음
			//--------------------------------------------------------------------
			//print_r2($write);

			$comment_id = $wr_id;

			$len = strlen($write['wr_comment_reply']);
			if ($len < 0) $len = 0;
			$comment_reply = substr($write['wr_comment_reply'], 0, $len);

			// 코멘트 삭제
			if (!delete_point($write['mb_id'], $bo_table, $comment_id, '코멘트')) {
				insert_point($write['mb_id'], $board['bo_comment_point'] * (-1), "{$board['bo_subject']} {$write['wr_parent']}-{$comment_id} 코멘트삭제");
			}

			// 코멘트 삭제
			sql_query(" delete from $write_table where wr_id = '$comment_id' ");

			// 코멘트가 삭제되므로 해당 게시물에 대한 최근 시간을 다시 얻는다.
			$sql = " select max(wr_datetime) as wr_last from $write_table where wr_parent = '{$write['wr_parent']}' ";
			$row = sql_fetch($sql);

			// 원글의 코멘트 숫자를 감소
			sql_query(" update $write_table set wr_comment = wr_comment - 1, wr_last = '{$row['wr_last']}' where wr_id = '{$write['wr_parent']}' ");

			// 코멘트 숫자 감소
			sql_query(" update {$g5['board_table']} set bo_count_comment = bo_count_comment - 1 where bo_table = '$bo_table' ");

			// 새글 삭제
			sql_query(" delete from {$g5['board_new_table']} where bo_table = '$bo_table' and wr_id = '$comment_id' ");
		}
	}

	foreach ($save_bo_table as $key=>$value) {
		delete_cache_latest($value);
	}

	run_event('bbs_new_delete', $chk_bn_id, $save_bo_table, $save_wr_id);

} else if($btn_submit == '잠금해제') {

	for($i=0;$i < $list_cnt;$i++) {
		// 실제 번호를 넘김
		$k = isset($_POST['chk_bn_id'][$i]) ? $_POST['chk_bn_id'][$i] : '';

		$bo_table = isset($_POST['bo_table'][$k]) ? preg_replace('/[^a-z0-9_]/i', '', $_POST['bo_table'][$k]) : '';
		$wr_id    = isset($_POST['wr_id'][$k]) ? preg_replace('/[^0-9]/i', '', $_POST['wr_id'][$k]) : 0;

		$write_table = $g5['write_prefix'].$bo_table;

		$write = get_write($write_table, $wr_id);

		if(!$write)
			continue;

		$arr = explode(",", $write['wr_option']);

		if(in_array("html1", $arr)) $html = 'html1';
		else if(in_array("html2", $arr)) $html = 'html2';
		else $html = '';

		$secret = (in_array("secret", $arr)) ? 'secret' : '';
		$mail = (in_array("mail", $arr)) ? 'mail' : '';

		$flag = ($write['wr_is_comment']==0) ? $html.',,'.$mail : '';

		// 신고 내역 삭제
		sql_query(" delete from {$g5['na_shingo']} where bo_table = '$bo_table' and wr_id = '$wr_id' ");

		// 잠금 해제
		sql_query(" update $write_table set wr_option = '$flag', as_type = '0' where wr_id = '$wr_id' ", false);
	}

} else if($btn_submit == '잠금처리') {

	for($i=0;$i < $list_cnt;$i++) {
		// 실제 번호를 넘김
		$k = isset($_POST['chk_bn_id'][$i]) ? $_POST['chk_bn_id'][$i] : '';

		$bo_table = isset($_POST['bo_table'][$k]) ? preg_replace('/[^a-z0-9_]/i', '', $_POST['bo_table'][$k]) : '';
		$wr_id    = isset($_POST['wr_id'][$k]) ? preg_replace('/[^0-9]/i', '', $_POST['wr_id'][$k]) : 0;

		$write_table = $g5['write_prefix'].$bo_table;

		$write = get_write($write_table, $wr_id);

		if(!$write) 
			continue;

		$arr = explode(",", $write['wr_option']);

		if(in_array("html1", $arr)) $html = 'html1';
		else if(in_array("html2", $arr)) $html = 'html2';
		else $html = '';

		$secret = (in_array("secret", $arr)) ? 'secret' : '';
		$mail = (in_array("mail", $arr)) ? 'mail' : '';

		$flag = ($write['wr_is_comment']==0) ? $html.',secret,'.$mail : 'secret';

		$row = sql_fetch(" select id, mb_id from {$g5['na_shingo']} where bo_table = '$bo_table' and wr_id = '$wr_id' ");

		// 신고자
		$mbs = array();
		$tmp = explode(",", trim($row['mb_id']));
		for($i=0; $i < count($tmp); $i++) {
			if(!trim($tmp[$i]))
				continue;

			$mbs[] = $tmp[$i];
		}

		if(count($mbs) > 0) {
			array_push($mbs, $member['mb_id']);
			array_unique($mbs);
			$mb_ids = implode(",", $mbs);
		} else {
			$mb_ids = $member['mb_id'];
		}

		// 내역 잠금 처리
		sql_query(" update {$g5['na_shingo']} set flag = '1', mb_id = '$mb_ids' where id = '{$row['id']}' ", false);

		// 게시물 잠금 처리
		sql_query(" update $write_table set wr_option = '$flag', as_type = '-1' where wr_id = '$wr_id' ", false);
	}

} else {
    alert('올바른 방법으로 이용해 주세요.');
}

goto_url("./shingo.php?page=$page");