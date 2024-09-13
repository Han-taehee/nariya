<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<tr>
<td colspan="4" style="padding-left:0; padding-top:30px;">
	<span class="h2_frm">게시판 플러그인</span>
</td>
</tr>
<tr>
	<th scope="row">
		플러그인 사용
	</th>
	<td colspan="3">
		<?php echo help('태그 기능, 신고 기능 등 게시판 기능을 확장하며, DB에 관련 테이블 및 각 게시판 테이블에 필드를 추가합니다.') ?>
		<label>
			<?php $nariya['bbs'] = isset($nariya['bbs']) ? $nariya['bbs'] : ''; ?>
			<input type="checkbox" name="na[bbs]" value="1"<?php echo get_checked('1', $nariya['bbs'])?>> 사용
		</label>
	</td>
</tr>
<?php if(IS_NA_BBS) { //사용시에만 설정 출력 ?>
<tr>
	<th scope="row">
		DB 업그레이드
	</th>
	<td colspan="3">
		<button type="button" class="btn btn_03" onclick="na_upgrade('<?php echo NA_URL ?>/extend/bbs/db.php');">DB 업그레이드</button>
	</td>
</tr>
<?php } ?>
<tr>
	<th scope="row">
		태그모음 스킨
	</th>
	<td colspan="3">
		<?php echo help('/'.NA_DIR.'/skin/tag 폴더') ?>
		<select name="na[tag_skin]">
			<?php 
			$nariya['tag_skin'] = isset($nariya['tag_skin']) ? $nariya['tag_skin'] : '';
			$skins = na_dir_list(NA_PATH.'/skin/tag');
			for ($i=0; $i<count($skins); $i++) { 
			?>
				<option value="<?php echo $skins[$i] ?>"<?php echo get_selected($nariya['tag_skin'], $skins[$i]) ?>><?php echo $skins[$i] ?></option>
			<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<th scope="row">
		신고모음 스킨
	</th>
	<td colspan="3">
		<?php echo help('/'.NA_DIR.'/skin/shingo 폴더') ?>
		<select name="na[shingo_skin]">
			<?php 
			$nariya['shingo_skin'] = isset($nariya['shingo_skin']) ? $nariya['shingo_skin'] : '';
			$skins = na_dir_list(NA_PATH.'/skin/shingo');
			for ($i=0; $i<count($skins); $i++) { 
			?>
				<option value="<?php echo $skins[$i] ?>"<?php echo get_selected($nariya['shingo_skin'], $skins[$i]) ?>><?php echo $skins[$i] ?></option>
			<?php } ?>
		</select>
	</td>
</tr>
