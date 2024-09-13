<?php
if (!defined('_GNUBOARD_')) exit;

$mb_only_page = basename($_SERVER['SCRIPT_FILENAME']);

if($mb_only_page == 'logout.php')
	return;

if($is_guest) {
	$mb_only_arr = array('login.php'
						,'login_check.php'
						,'password_lost.php'
						,'password_lost_certify.php'
						,'password_lost2.php'
						,'register.php'
						,'register_email.php'
						,'register_email_update.php'
						,'register_form.php'
						,'register_form_update.php'
						,'register_result.php'
						,'kcaptcha_result.php'
						,'kcaptcha_image.php'
						,'kcaptcha_session.php'
						,'kcaptcha_mp3.php'
						,'kcpcert_form.php'
						,'kcpcert_result.php'
						,'AuthOnlyReq.php'
						,'AuthOnlyRes.php'
						,'hpcert1.php'
						,'hpcert2.php'
						,'ipin1.php'
						,'ipin2.php'
						,'email_certify.php'
						,'ajax.mb_email.php'
						,'ajax.mb_hp.php'
						,'ajax.mb_id.php'
						,'ajax.mb_nick.php'
						,'ajax.mb_recommend.php'
						,'alert.php'
						,'alert_close.php'
						,'sns_send.php'
						,'write_token.php'
						,'ping.php'
						,'popup.php'
						,'register_member.php'
						,'register_member_update.php'
						,'unlink.php'
				);

	// 로그인 페이지로 이동
	if(!in_array($mb_only_page, $mb_only_arr)) {
		goto_url(G5_BBS_URL.'/login.php?url='.$urlencode);
	}
} else if($member['mb_level'] >= (int)$nariya['mb_only']) {
	;
} else {
	include_once(NA_PATH.'/bbs/alert.php');
	alert('현재 회원님의 가입 승인 여부를 심사 중 입니다.', G5_BBS_URL.'/logout.php');
}