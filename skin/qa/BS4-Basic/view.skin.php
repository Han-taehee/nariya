<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// 등록자 이름과 닉네임
$mb = get_member($view['mb_id'], 'mb_nick, mb_name');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);

// 스킨 설정값
$wset = na_skin_config('qa');

?>

<!-- 게시물 읽기 시작 { -->

<article id="bo_v" class="mb-4">
	<header class="font-weight-normal mb-2 px-3 px-sm-0">
		<?php if ($view['category']) { ?>
			<div class="f-sm text-muted">
				<span class="sr-only">분류</span>
				<?php echo $view['category'] ?>
			</div>
		<?php } ?>
		<h1 id="bo_v_title">
            <?php echo $view['subject'] ?>
        </h1>
    </header>

	<section id="bo_v_info" class="f-sm font-weight-normal mb-3">
		<div class="clearfix bg-light border-top border-bottom text-muted px-3 py-2">
			<span class="sr-only">작성자</span>
			<?php echo na_name_photo($view['mb_id'], get_sideview($view['mb_id'], get_text($mb['mb_nick']), $view['qa_email'], '')) ?>
			(<?php echo get_text($mb['mb_name']) ?>)
		</div>

		<?php if($view['email'] || $view['hp']) { ?>
			<div class="f-de border-bottom text-muted px-3 py-2">
				<div class="row">
				<?php if ($view['hp']) { ?>
					<div class="col-sm-6">
						<span class="sr-only">연락처</span>
						<i class="fa fa-phone fa-fw" aria-hidden="true"></i>
						<?php echo $view['hp'] ?>
					</div>
				<?php } ?>
				<?php if ($view['email']) { ?>
					<div class="col-sm-6">
						<span class="sr-only">이메일</span>
						<i class="fa fa-envelope-o fa-fw" aria-hidden="true"></i>
						<?php echo $view['email'] ?>
					</div>
				<?php } ?>
				</div>
			</div>
		<?php } ?>

		<div class="clearfix f-sm text-muted px-3 pt-2">
	        <h3 class="sr-only">작성자 정보</h3>
			<ul class="d-flex align-items-center">
				<li class="pr-3">
					<span class="sr-only">작성일</span>
					<i class="fa fa-clock-o" aria-hidden="true"></i>
					<time class="f-sm" datetime="<?php echo date('Y-m-d\TH:i:s+09:00', strtotime($view['qa_datetime'])) ?>"><?php echo date("Y.m.d H:i", strtotime($view['qa_datetime'])) ?></time>
				</li>
				<li id="bo_v_btn" class="d-none d-sm-block flex-sm-grow-1 text-right">
					<!-- 게시물 상단 버튼 시작 { -->
					
					<?php ob_start(); ?>

					<a href="<?php echo $list_href ?>" class="btn btn_b01 nofocus p-1 ml-2" role="button">
						<i class="fa fa-bars" aria-hidden="true"></i>
						목록
					</a>

					<?php if ($update_href || $delete_href) { ?>
						<div class="btn-group" role="group">
							<button type="button" class="btn btn_b01 nofocus dropdown-toggle dropdown-toggle-empty dropdown-toggle-split p-1 ml-2" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-cog" aria-hidden="true"></i>
								관리
							</button>
							<div class="dropdown-menu dropdown-menu-right p-0 border-0 bg-transparent text-right">
								<div class="btn-group-vertical">
								<?php if ($update_href) { ?>
									<a href="<?php echo $update_href ?>" class="btn btn-primary py-2" role="button">
										<i class="fa fa-pencil-square-o fa-fw" aria-hidden="true"></i>
										글수정
									</a>
								<?php } ?>
								<?php if ($delete_href) { ?>
									<a href="<?php echo $delete_href ?>" onclick="del(this.href); return false;" class="btn btn-primary py-2" role="button">
										<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
										글삭제
									</a>
								<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if ($write_href) { ?>
						<a href="<?php echo $write_href ?>" class="btn btn-primary nofocus py-1 ml-2" role="button">
							<i class="fa fa-pencil" aria-hidden="true"></i>
							쓰기
						</a>
					<?php } ?>
					<?php
					$link_buttons = ob_get_contents();
					ob_end_flush();
					?>
					<!-- } 게시물 상단 버튼 끝 -->
				</li>
			</ul>
		</div>
    </section>

    <section id="bo_v_atc">
        <h3 class="sr-only">본문</h3>
        <!-- 본문 내용 시작 { -->
        <div id="bo_v_con" class="mb-4 px-3">
			<?php
				// 파일 출력
				if($view['img_count']) {
					echo "<div id=\"bo_v_img\">\n";

					for ($i=0; $i<$view['img_count']; $i++) {
						echo get_view_thumbnail($view['img_file'][$i], $qaconfig['qa_image_width']);
					}

					echo "</div>\n";
				}

				echo na_content(get_view_thumbnail($view['content'], $qaconfig['qa_image_width']));
			?>
		</div>
		<?php if ($view['download_count']) { ?>
			<div class="clearfix f-de border-top text-muted px-3 py-2">
				<?php for ($i=0; $i<$view['download_count']; $i++) { // 가변 파일 ?>
					<i class="fa fa-download fa-fw" aria-hidden="true"></i>	
					<a href="<?php echo $view['download_href'][$i] ?>" class="view_file_download mr-3">
						<?php echo $view['download_source'][$i] ?>
					</a>	
				<?php } ?>
			</div>
		<?php } ?>

		<!-- 게시물 하단 버튼 시작 { -->
		<div class="clearfix d-block d-sm-none py-2 px-3 border-top text-right">
			<?php echo $link_buttons; // 버튼 출력 ?>
		</div>
		<!-- } 게시물 하단 버튼 끝 -->
	</section>
    
    <?php
    // 질문글에서 답변이 있으면 답변 출력, 답변이 없고 관리자이면 답변등록폼 출력
    if(!$view['qa_type']) {
        if($view['qa_status'] && $answer['qa_id'])
            include_once($qa_skin_path.'/view.answer.skin.php');
        else
            include_once($qa_skin_path.'/view.answerform.skin.php');
    }
    ?>

    <?php if($view['rel_count']) { ?>
		<section id="bo_v_rel" class="na-table f-de mb-4">
			<div class="bg-light px-3 py-2 py-md-2 border-top border-bottom">
				<b>연관질문</b>
			</div>
			<ul>
			<?php for($i=0; $i<$view['rel_count']; $i++) { ?>
				<li class="px-3 py-2 py-md-2 border-bottom">
					<a href="<?php echo $rel_list[$i]['view_href']; ?>" class="ellipsis">
						<span class="float-right text-muted f-sm ml-2">
							<?php echo $rel_list[$i]['date']; ?>
						</span>
						<span class="text-muted">
							<?php echo ($rel_list[$i]['qa_status']) ? '<span class="orangered">완료</span>' : '대기'; ?>
						</span>
						<span class="na-bar"></span>
						<?php echo $rel_list[$i]['subject']; ?>
					</a>
				</li>
			<?php } ?>
			</ul>
		</section>
    <?php } ?>

</article>
<!-- } 게시판 읽기 끝 -->

<script>
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });
});
</script>