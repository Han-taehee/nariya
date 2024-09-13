<?php
include_once('./_common.php');

if ($is_admin == 'super') {
	;
} else {
	alert_close('접근권한이 없습니다.');
}

// 처리하기
if(isset($_POST['act']) && $_POST['act'] == 'ok') {

	$_POST['chk_bo_table'] = (isset($_POST['chk_bo_table']) && is_array($_POST['chk_bo_table'])) ? $_POST['chk_bo_table'] : array();

	if(!count($_POST['chk_bo_table']))
		alert('게시판을 한개 이상 선택해 주십시오.');

	// 자료가 많을 경우 대비 설정변경
	@ini_set('memory_limit', '-1');

	$z = 0;
	for ($i=0; $i<count($_POST['chk_bo_table']); $i++) {
		$tmp_bo_table = preg_replace('/[^a-z0-9_]/i', '', $_POST['chk_bo_table'][$i]);

		if(!$tmp_bo_table)
			continue;

        $tmp_write_table = $g5['write_prefix'] . $tmp_bo_table; // 게시판 테이블 전체이름		
		$result = sql_query(" select * from $tmp_write_table where wr_is_comment = '0' ");
		while($row = sql_fetch_array($result)) {

			if(!isset($row['as_thumb']))
				break;

			$as_thumb = $row['as_thumb'];

			$row['as_thumb'] = '';
			$img = na_wr_img($tmp_bo_table, $row);

			// 상대경로로 변경
			$img = str_replace(G5_URL, "./", $img);

			// 업데이트
			if($as_thumb || $img) {
				sql_query(" update {$tmp_write_table} set as_thumb = '".addslashes($img)."' where wr_id = '{$row['wr_id']}' ");
			}

			$z++;
		}
	}

	alert('총 '.$z.'건의 대표이미지 복구 완료', './restore_img.php');
}

include_once(G5_PATH.'/head.sub.php');

?>

	<script src="<?php echo G5_ADMIN_URL ?>/admin.js"></script>
	<form id="defaultform" name="defaultform" method="post" onsubmit="return update_submit(this);">
	<input type="hidden" name="act" value="ok">
	<div style="padding:10px">
		<div style="border:1px solid #ddd; background:#f5f5f5; color:#000; padding:10px; line-height:20px;">
			<b><i class="fa fa-picture-o"></i> 대표이미지 복구하기</b>
		</div>
		<div style="border:1px solid #ddd; border-top:0px; padding:10px;line-height:22px;">
			<ul>
				<?php
					// 아이디 넘버링용
					$idn = 1;
					$result = sql_query(" select gr_id, gr_subject from {$g5['group_table']} order by gr_id ");
					if($result) {
						for ($i=0; $row=sql_fetch_array($result); $i++) {
				?>
						<li class="list-group-item bg-light">
							<b><?php echo get_text($row['gr_subject']) ?></b>
						</li>
						<li class="list-group-item">
							<div class="form-group mb-0">
								<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4">
									<?php
										$result1 = sql_query("select bo_table, bo_subject from {$g5['board_table']} where gr_id = '{$row['gr_id']}' order by bo_table ");
										for ($j=0; $row1=sql_fetch_array($result1); $j++) {
									?>
										<div class="col">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" name="chk_bo_table[]" value="<?php echo $row1['bo_table'] ?>" class="custom-control-input" id="idCheck<?php echo $idn ?>">
												<label class="custom-control-label py-1" for="idCheck<?php echo $idn; ?>"><span><?php echo get_text($row1['bo_subject']) ?></span></label>
											</div>
										</div>
									<?php $idn++; } ?>
								</div>
							</div>
						</li>
				<?php 
						}
					} 
				?>
			</ul>
		</div>
		
		<div class="text-center my-3">
			<button type="submit" class="btn btn-primary btn-lg" accesskey="s">실행하기</button>
		</div>	
	</div>
	</form>
	<script>
		function update_submit(f) {
			var check = false;

			if (typeof(f.elements['chk_bo_table[]']) == 'undefined')
				;
			else {
				if (typeof(f.elements['chk_bo_table[]'].length) == 'undefined') {
					if (f.elements['chk_bo_table[]'].checked)
						check = true;
				} else {
					for (i=0; i<f.elements['chk_bo_table[]'].length; i++) {
						if (f.elements['chk_bo_table[]'][i].checked) {
							check = true;
							break;
						}
					}
				}
			}

			if (!check) {
				alert('게시판을 한개 이상 선택해 주십시오.');
				return false;
			}

			if(!confirm("실행후 완료메시지가 나올 때까지 기다려 주세요.\n\n정말 실행하시겠습니까?")) {
				return false;	
			} 
			return true;
		}
	</script>

<?php 
include_once(G5_PATH.'/tail.sub.php');