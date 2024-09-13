<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if( ! $config['cf_social_login_use']) {     //소셜 로그인을 사용하지 않으면
    return;
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_JS_URL.'/remodal/remodal.css">', 11);
//add_stylesheet('<link rel="stylesheet" href="'.G5_JS_URL.'/remodal/remodal-default-theme.css">', 12);
//add_stylesheet('<link rel="stylesheet" href="'.get_social_skin_url().'/style.css?ver='.G5_CSS_VER.'">', 13);
//add_javascript('<script src="'.G5_JS_URL.'/remodal/remodal.js"></script>', 10);

$email_msg = $is_exists_email ? '등록할 이메일이 중복되었습니다.다른 이메일을 입력해 주세요.' : '';
?>

<style>
.social-register .list-group-item {
	border-left:0;
	border-right:0;
}
</style>

<!-- 회원정보 입력/수정 시작 { -->
<div class="social-register m-auto f-de px-3 px-sm-0" id="register_member">

    <script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
    
    <!-- 새로가입 시작 -->
    <form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" class="mb-4">
		<input type="hidden" name="w" value="<?php echo $w ?>">
		<input type="hidden" name="url" value="<?php echo $urlencode ?>">
		<input type="hidden" name="mb_name" value="<?php echo $user_name ? $user_name : $user_nick ?>" >
		<input type="hidden" name="provider" value="<?php echo $provider_name ?>" >
		<input type="hidden" name="action" value="register">

		<input type="hidden" name="mb_id" value="<?php echo $user_id  ?>" id="reg_mb_id">
		<input type="hidden" name="mb_nick_default" value="<?php echo isset($user_nick) ? get_text($user_nick) : '' ?>">
		<input type="hidden" name="mb_nick" value="<?php echo isset($user_nick) ? get_text($user_nick) : '' ?>" id="reg_mb_nick">

		<ul class="list-group mb-4">
			<li class="list-group-item">
				<div class="clearfix">
					<div class="custom-control custom-checkbox float-left">
						<input type="checkbox" name="agree" value="1" class="custom-control-input" id="agree11">
						<label class="custom-control-label" for="agree11"><span>회원가입약관</span></label>
					</div>

					<button class="btn btn-primary btn-sm float-right" type="button" data-toggle="collapse" data-target="#SocialProvision" aria-expanded="false" aria-controls="SocialProvision">자세히보기</button>
				</div>

				<div id="SocialProvision" class="collapse">
					<div class="pt-3 mt-3 border-top">
						<?php if(is_file(G5_THEME_PATH.'/page/provision.php')) { ?>
							<div class="f-sm" style="overflow:auto; height:12.0rem;">
								<?php @include_once (G5_THEME_PATH.'/page/provision.php') ?>
							</div>
						<?php } else { ?>
							<textarea class="form-control border-0 bg-white" rows="8" readonly><?php echo get_text($config['cf_stipulation']) ?></textarea>
						<?php } ?>
					</div>
				</div>

			</li>
			<li class="list-group-item">
				<div class="clearfix">
					<div class="custom-control custom-checkbox float-left">
						<input type="checkbox" name="agree2" value="1" class="custom-control-input" id="agree21">
						<label class="custom-control-label" for="agree21"><span>개인정보처리방침안내</span></label>
					</div>		

					<button class="btn btn-primary btn-sm float-right" type="button" data-toggle="collapse" data-target="#SocialPrivacy" aria-expanded="false" aria-controls="SocialPrivacy">자세히보기</button>
				</div>

				<div id="SocialPrivacy" class="collapse">
					<div class="table-responsive pt-3">
						<table class="table table-bordered mb-2" style="min-width:500px;">
						<tbody>
						<tr class="bg-light">
							<th class="text-center">목적</th>
							<th class="w-25 text-center">항목</th>
							<th class="w-25 text-center">보유기간</th>
						</tr>
						<tr>
							<td>이용자 식별 및 본인여부 확인</td>
							<td>아이디, 이름, 비밀번호</td>
							<td>회원 탈퇴 시까지</td>
						</tr>
						<tr>
							<td>서비스 이용에 관한 통지, CS대응을 위한 이용자 식별</td>
							<td>연락처 (이메일, 휴대전화번호)</td>
							<td>회원 탈퇴 시까지</td>
						</tr>
						</tbody>
						</table>
					</div>
				</div>

			</li>
			<li class="list-group-item bg-light">
				<div class="custom-control custom-checkbox">
					<input type="checkbox" name="chk_all" value="1" class="custom-control-input" id="chk_all">
					<label class="custom-control-label" for="chk_all"><b><span>전체약관에 동의합니다.</span></b></label>
				</div>		
			</li>
		</ul>

		<ul class="list-group mb-4">
			<li class="list-group-item bg-light">
				<b>개인정보입력</b>
			</li>
			<li class="list-group-item">

				<div class="form-group row mb-0">
					<label class="col-sm-2 col-form-label" for="reg_mb_email">E-mail<strong class="sr-only">필수</strong></label>
					<div class="col-sm-4">
						<input type="text" name="mb_email" value="<?php echo (isset($user_email)) ? $user_email : '' ?>" id="reg_mb_email" required class="form-control email required" maxlength="100" placeholder="이메일을 입력해주세요." >
					</div>
					<div class="col-sm-6">
						<p class="form-control-plaintext f-de text-muted pb-0">
							<?php echo $email_msg ?>
						</p>
					</div>
				</div>

			</li>
		</ul>

		<div class="mb-4">
			<div class="row mx-n2">
				<div class="col-6 order-2 px-2">
					<button type="submit" id="btn_submit" accesskey="s" class="btn btn-primary btn-lg btn-block en">회원가입</button>
				</div>
				<div class="col-6 order-1 px-2">
					<a href="<?php echo G5_URL ?>" class="btn btn-basic btn-lg btn-block en">취소</a>
				</div>
			</div>
		</div>

	</form>
    <!-- 새로가입 끝 -->

    <!-- 기존 계정 연결 -->

	<ul class="list-group pt-4 pb-5">
		<li class="list-group-item bg-light">
			<b>기존계정연결</b>
		</li>
		<li class="list-group-item">

			<div class="form-group row mb-0">
				<label class="col-sm-8 col-form-label font-weight-normal">혹시 기존 회원이신가요?</label>
				<div class="col-sm-4">
					<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#social-modal-sm">기존 계정에 연결하기</button>
				</div>
			</div>
		</li>
	</ul>

	<div id="social-modal-sm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="socalModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">

				<form method="post" action="<?php echo $login_action_url ?>" onsubmit="return social_obj.flogin_submit(this);">
				<input type="hidden" id="url" name="url" value="<?php echo $login_url ?>">
				<input type="hidden" id="provider" name="provider" value="<?php echo $provider_name ?>">
				<input type="hidden" id="action" name="action" value="social_account_linking">

				  <div class="modal-header">
					<h5 class="modal-title">기존 계정에 연결하기</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div class="modal-body">
					기존 아이디에 SNS 아이디를 연결합니다.<br>
					이 후 SNS 아이디로 로그인 하시면 기존 아이디로 로그인 할 수 있습니다.

					<div class="form-group mt-3">
						<label for="login_id" class="sr-only">아이디<strong class="sr-only"> 필수</strong></label>			
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-user text-muted"></i></span>
							</div>
							<input type="text" name="mb_id" id="login_id" class="form-control required" placeholder="기존 아이디">
						</div>
					</div>
					<div class="form-group">	
						<label for="login_pw" class="sr-only">비밀번호<strong class="sr-only"> 필수</strong></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-lock text-muted"></i></span>
							</div>
							<input type="password" name="mb_password" id="login_pw" class="form-control required" placeholder="기존 비밀번호">
						</div>
					</div>

					<div class="form-group mb-0">
						<button type="submit" class="btn btn-primary btn-block p-3 en">
							<h5>연결하기</h5>
						</button>    
					</div>	

				  </div>
				</form>
			</div>
		</div>
	</div>

    <script>

    // submit 최종 폼체크
    function fregisterform_submit(f)
    {

        if (!f.agree.checked) {
            alert("회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
            f.agree.focus();
            return false;
        }

        if (!f.agree2.checked) {
            alert("개인정보처리방침안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
            f.agree2.focus();
            return false;
        }

        // E-mail 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
            var msg = reg_mb_email_check();
            if (msg) {
                alert(msg);
                jQuery(".email_msg").html(msg);
                f.reg_mb_email.select();
                return false;
            }
        }

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }

    function flogin_submit(f)
    {
        var mb_id = $.trim($(f).find("input[name=mb_id]").val()),
            mb_password = $.trim($(f).find("input[name=mb_password]").val());

        if(!mb_id || !mb_password){
            return false;
        }

        return true;
    }

    $(function($){
        // 모두선택
        $("input[name=chk_all]").click(function() {
            if ($(this).prop('checked')) {
                $("input[name^=agree]").prop('checked', true);
            } else {
                $("input[name^=agree]").prop("checked", false);
            }
        });
    });
    </script>

</div>
<!-- } 회원정보 입력/수정 끝 -->