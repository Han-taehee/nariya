<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<tr>
<td colspan="4" style="padding-left:0; padding-top:30px;">
	<span class="h2_frm">멤버십 플러그인</span>
</td>
</tr>
<tr>
	<th scope="row">
		플러그인 사용
	</th>
	<td colspan="3">
		<?php echo help('회원레벨, 경험치, 자동등업 등 회원 기능을 확장하며, DB에 관련 테이블 및 회원 테이블에 필드를 추가합니다.') ?>
		<label>
			<?php $nariya['xp'] = isset($nariya['xp']) ? $nariya['xp'] : ''; ?>
			<input type="checkbox" name="na[xp]" value="1"<?php echo get_checked('1', $nariya['xp'])?>> 사용
		</label>
	</td>
</tr>
<?php if(IS_NA_XP) { //사용시에만 설정 출력 ?>
<tr>
	<th scope="row">
		DB 업그레이드
	</th>
	<td colspan="3">
		<button type="button" class="btn btn_03" onclick="na_upgrade('<?php echo NA_URL ?>/extend/membership/db.php');">DB 업그레이드</button>
	</td>
</tr>
<?php } ?>
<tr>
	<th scope="row">
		경험치 스킨
	</th>
	<td colspan="3">
		<?php echo help('/'.NA_DIR.'/skin/exp 폴더') ?>
		<select name="na[exp_skin]">
			<?php 
			$nariya['exp_skin'] = isset($nariya['exp_skin']) ? $nariya['exp_skin'] : '';
			$skins = na_dir_list(NA_PATH.'/skin/exp');
			for ($i=0; $i<count($skins); $i++) { 
			?>
				<option value="<?php echo $skins[$i] ?>"<?php echo get_selected($nariya['exp_skin'], $skins[$i]) ?>><?php echo $skins[$i] ?></option>
			<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<th scope="row">
		레벨 아이콘
	</th>
	<td colspan="3">
		<?php echo help('/'.NA_DIR.'/skin/level 폴더. gif, png, jpg 아이콘 모두 사용 가능. ※ 주의 : 회원수에 따라 서버 부하가 많이 발생할 수도 있음') ?>
		<select name="na[lvl_skin]">
			<option value="">사용안함</option>
			<?php 
			$nariya['lvl_skin'] = isset($nariya['lvl_skin']) ? $nariya['lvl_skin'] : '';
			$skins = na_dir_list(NA_PATH.'/skin/level');
			for ($i=0; $i<count($skins); $i++) { 
			?>
				<option value="<?php echo $skins[$i] ?>"<?php echo get_selected($nariya['lvl_skin'], $skins[$i]) ?>><?php echo $skins[$i] ?></option>
			<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<th scope="row">
		관리자 아이콘
	</th>
	<td colspan="3">
		<?php echo help('회원아이디를 콤마(,)로 구분하여 복수 회원 등록이 가능합니다.') ?>
		<input type="text" name="na[lvl_admin]" value="<?php echo $nariya['lvl_admin'] ?>" class="frm_input" size="80">
	</td>
</tr>
<tr>
	<th scope="row">
		스페셜 아이콘
	</th>
	<td colspan="3">
		<?php echo help('회원아이디를 콤마(,)로 구분하여 복수 회원 등록이 가능합니다.') ?>
		<input type="text" name="na[lvl_special]" value="<?php echo $nariya['lvl_special'] ?>" class="frm_input" size="80">
	</td>
</tr>
<tr>
	<th scope="row">
		레벨업 경험치
	</th>
	<td colspan="3">
		<?php echo help('레벨당 레벨업을 위한 최소 필요 경험치') ?>
		<?php $nariya['xp_point'] = (isset($nariya['xp_point']) && $nariya['xp_point']) ? $nariya['xp_point'] : 1000; ?>
		<input type="text" name="na[xp_point]" size="6" value="<?php echo $nariya['xp_point'] ?>" class="frm_input"> 경험치
		&nbsp;
		<a href="<?php echo NA_URL ?>/extend/membership/exp.php" class="btn btn_03 win_point">레벨업 경험치 시뮬레이터</a>
	</td>
</tr>
<tr>
	<th scope="row">
		경험치 증가율
	</th>
	<td colspan="3">
		<?php echo help('레벨업 필요 경험치 = 기준 경험치 + 기준 경험치 * 직전 레벨 * 경험치 증가율') ?>
		<?php $nariya['xp_rate'] = (isset($nariya['xp_rate']) && $nariya['xp_rate']) ? $nariya['xp_rate'] : 0.1; ?>
		<input type="text" name="na[xp_rate]" size="6" value="<?php echo $nariya['xp_rate'] ?>" class="frm_input"> 배 * 직전 레벨 * 기준 경험치
	</td>
</tr>
<tr>
	<th scope="row">
		최고 레벨
	</th>
	<td colspan="3">
		<?php $nariya['xp_max'] = (isset($nariya['xp_max']) && $nariya['xp_max']) ? $nariya['xp_max'] : 99; ?>
		<input type="text" name="na[xp_max]" size="6" value="<?php echo $nariya['xp_max'] ?>" class="frm_input"> 레벨
	</td>
</tr>
<tr>
	<th scope="row">
		자동등업
	</th>
	<td colspan="3">
		<?php echo help('자동등업 시작등급:각 등급별 최고레벨 방식으로 설정하며, 각 등급별 최고 레벨은 낮은 레벨부터 콤마(,)를 이용하여 구분합니다.') ?>
		<?php echo help('ex) 2:19,39,59,79 설정시 2등급은 1~19레벨까지, 3등급은 20~39레벨까지, 4등급은 40~59레벨까지 5등급은 60~79레벨까지, 6등급은 80~최고 레벨까지 자동부여 됩니다.') ?>
		<?php echo help('ex) 3:24,49,74 설정시 자동등업이 3등급부터 적용되므로 2등급을 3등급으로 등업하는 것은 수동으로 해주셔야 합니다. 즉, 3등급부터 6등급까지만 레벨에 따른 자동등업이 적용됩니다.') ?>
		<?php $nariya['xp_auto'] = isset($nariya['xp_auto']) ? $nariya['xp_auto'] : ''; ?>
		<input type="text" name="na[xp_auto]" size="80" value="<?php echo $nariya['xp_auto'] ?>" class="frm_input">
	</td>
</tr>
<tr>
	<th scope="row">
		로그인 경험치
	</th>
	<td colspan="3">
		<?php echo help('로그인시 적립되는 경험치로 1일 1회만 적용됩니다.') ?>
		<?php $nariya['xp_login'] = isset($nariya['xp_login']) ? $nariya['xp_login'] : ''; ?>
		<input type="text" name="na[xp_login]" size="6" value="<?php echo $nariya['xp_login'];?>" class="frm_input"> 경험치
	</td>
</tr>