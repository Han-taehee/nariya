<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 데모 메시지
na_demo_msg('skin');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$tag_skin_url.'/style.css" media="screen">', 0);

// 초기값
$wset['search_open'] = isset($wset['search_open']) ? $wset['search_open'] : '';
$wset['head_color'] = isset($wset['head_color']) ? $wset['head_color'] : '';

$head_color = ($wset['head_color']) ? $wset['head_color'] : 'primary';

?>

<div id="tag_box" class="mb-4">

	<!-- 검색창 시작 { -->
	<div id="tag_search" class="collapse<?php echo ($wset['search_open'] || $q) ? ' show' : ''; ?>">
		<div class="alert bg-light border p-2 p-sm-3 mb-3 mx-3 mx-sm-0">
			<form id="fsearch" name="fsearch" method="get" class="m-auto" style="max-width:400px;">
				<div class="form-row mx-n1">
					<div class="col-4 px-1">
						<select name="eq" class="custom-select">
							<option value=""<?php echo get_selected($eq, "") ?>>포함</option>
							<option value="1"<?php echo get_selected($eq, "1") ?>>일치</option>
						</select>	
					</div>
					<div class="col-8 px-1">
						<label for="stx" class="sr-only">태그</label>
						<div class="input-group">
							<input type="text" id="tag_stx" name="q" value="<?php echo stripslashes($q) ?>" required class="form-control" placeholder="태그를 입력해 주세요.">
							<div class="input-group-append">
								<button type="submit" class="btn btn-primary" title="검색하기">
									<i class="fa fa-search" aria-hidden="true"></i>
									<span class="sr-only">검색하기</span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- } 검색창 끝 -->

	<nav id="tag_cate" class="sly-tab font-weight-normal mb-2">
		<div id="noti_cate_list" class="sly-wrap px-3 px-sm-0">
			<ul id="noti_cate_ul" class="clearfix sly-list text-nowrap border-left">
				<?php if($q) { ?>
					<li class="float-left active"><a href="#javascript:;" class="py-2 px-3">검색</a></li>
				<?php } ?>
				<li class="float-left<?php echo (!$q && $sort == "") ? ' active' : '';?>"><a href="./tag.php" class="py-2 px-3">최신</a></li>
				<li class="float-left<?php echo ($sort == "popular") ? ' active' : '';?>"><a href="./tag.php?sort=popular" class="py-2 px-3">인기</a></li>
				<li class="float-left<?php echo ($sort == "index") ? ' active' : '';?>"><a href="./tag.php?sort=index" class="py-2 px-3">색인</a></li>
			</ul>
		</div>
		<hr/>
	</nav>

	<?php if(!$q && $is_admin == 'super') { ?>
		<form id="tagform" name="tagform" method="post" onsubmit="return ftag_submit(this);">
		<input type="hidden" name="sort" value="<?php echo $sort;?>">
		<input type="hidden" name="opt" value="del">
	<?php } ?>

	<!-- 페이지 정보 및 버튼 시작 { -->
	<div id="tag_btn_top" class="clearfix f-de font-weight-normal mb-2 pl-3 pr-2 px-sm-0">
		<div class="d-flex align-items-center">
			<div id="bo_list_total" class="flex-grow-1">
				전체 <b><?php echo number_format((int)$total_count) ?></b> / <?php echo $page ?> 페이지
			</div>
			<div class="btn-group" role="group">
				<?php if($admin_href) { ?>
					<a href="<?php echo $admin_href ?>" title="스킨 설정" class="btn btn_b01 btn-setup nofocus p-1">
						<i class="fa fa-cogs fa-fw fa-md" aria-hidden="true"></i></a>
						<span class="sr-only">스킨 설정</span>
					</a>
				<?php } ?>
				<?php if(!$q && $is_admin) { ?>
					<button type="submit" onclick="document.pressed=this.value" value="선택삭제" title="선택 삭제" class="btn btn_b01 nofocus p-1">
						<i class="fa fa-trash-o fa-fw fa-md" aria-hidden="true"></i>				
						<span class="sr-only">선택 삭제</span>
					</button>
				<?php } ?>
				<button type="button" class="btn btn_b01 nofocus p-1" title="태그 검색" data-toggle="collapse" data-target="#tag_search" aria-expanded="false" aria-controls="tag_search">
					<i class="fa fa-search fa-fw fa-md" aria-hidden="true"></i>
					<span class="sr-only">태그 검색</span>
				</button>
			</div>
		</div>
	</div>
	<!-- } 페이지 정보 및 버튼 끝 -->

	<div class="w-100 mb-0 bg-<?php echo $head_color ?>" style="height:4px;"></div>
	<?php if($q && $list_cnt) { //검색결과 ?>
		<ul class="list-group">
		<?php for($i=0; $i < $list_cnt; $i++) { ?>
			<li class="list-group-item border-left-0 border-right-0 px-3 py-2 py-md-2">
				<div class="clearfix">
					<a href="<?php echo $list[$i]['href'] ?>" class="float-left">
						<strong><?php echo $list[$i]['subject'] ?></strong>

						<?php if($list[$i]['comment']) { ?>
							<span class="sr-only">댓글</span>
							<span class="count-plus orangered">
								<?php echo $list[$i]['comment'] ?>
							</span>
						<?php } ?>
					</a>
					<a href="<?php echo $list[$i]['href'] ?>" target="_blank" class="float-left text-black-50 ml-2" title="새창으로 보기">
						<i class="fa fa-window-restore" aria-hidden="true"></i>
						<span class="sr-only">새창으로 보기</span>
					</a>
				</div>

				<div class="clearfix f-de text-muted">
					<?php echo $list[$i]['content'] ?>
				</div>

				<div class="clearfix f-sm text-muted">
					<div class="float-right">
						<span class="sr-only">등록자</span>
						<?php echo na_name_photo($list[$i]['mb_id'], $list[$i]['name']) ?>
					</div>
					<div class="float-left mr-3">
						<span class="sr-only">등록일</span>
						<?php echo na_date($list[$i]['wr_datetime'], 'orangered', 'H:i', 'm.d', 'Y.m.d') ?> 
					</div>
					<div class="float-left">
						<i class="fa fa-eye" aria-hidden="true"></i>
						<span class="sr-only">조회</span>
						<?php echo $list[$i]['wr_hit'] ?>
					</div>
				</div>
			</li>
		<?php } ?>
		</ul>

	<?php } else if($list_cnt) { ?>

		<div id="tag_list" class="px-3">
			<div class="row py-3 border-bottom">
				<div class="col-sm-4">
				<?php for($i=0; $i < $list_cnt; $i++) { ?>

					<?php if($i > 0 && $list[$i]['is_sp']) { ?>
						</div>
					</div>
					<div class="row py-3 border-bottom">
						<div class="col-sm-4">
					<?php } ?>

					<?php if($list[$i]['is_sp']) { 
						switch($sort) {
							case 'index'	: $tagbox_title = $list[$i]['idx']; break;	
							case 'popular'	: $tagbox_title = 'TOP '.$list[$i]['last']; break;
							default			: $tagbox_title = date('M', $list[$i]['date']).' '.date('Y', $list[$i]['date']); break;
						}
					?>

								<h5 class="mb-2"><b><?php echo $tagbox_title;?></b></h5>
							</div>
							<div class="col-sm-8">
					<?php } ?>
							<div class="na-title float-left mb-2">
								<div class="na-item">
									<?php if($is_admin == 'super') { ?>
										<input type="checkbox" class="mb-0 mr-2" name="chk_id[]" value="<?php echo $i;?>">
										<input type="hidden" name="id[<?php echo $i;?>]" value="<?php echo $list[$i]['id'];?>">
									<?php } ?>
									<a href="<?php echo $list[$i]['href'] ?>" class="na-subject">
										<?php echo ($sort == 'popular') ? $list[$i]['rank'].'.' : '';?>
										#<?php echo $list[$i]['tag'];?>
									</a>
									<div class="na-info">
										<span class="sr-only">등록수</span>
										<span class="count-plus orangered">
											<?php echo $list[$i]['cnt'];?>
										</span>
									</div>
								</div>
							</div>
				<?php } ?>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<div class="f-de px-3 py-5 text-muted text-center border-bottom">
			자료가 없습니다.
		</div>
	<?php } ?>

	<div class="font-weight-normal px-3 mt-4">
		<ul class="pagination justify-content-center en mb-0">
			<?php echo na_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $list_page) ?>
		</ul>
	</div>


	<?php if(!$q && $is_admin == 'super') { ?>
		</form>
		<script>
			function ftag_submit(f) {

				var cnt = 0;
				for (var i=0; i<f.length; i++) {
					if (f.elements[i].name == "chk_id[]" && f.elements[i].checked)
						cnt++;
				}

				if (!cnt) {
					alert("삭제할 태그를 하나 이상 선택하세요.");
					return false;
				}

				if (!confirm("태그 삭제시 색인과 로그 기록만 삭제되고, 글에 등록된 태그는 삭제되지 않습니다.\n\n선택한 태그를 정말 삭제 하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다")) {
					return false;
				}

				f.action = "./tag.php";

				return true;
			}
		</script>
	<?php } ?>
</div>