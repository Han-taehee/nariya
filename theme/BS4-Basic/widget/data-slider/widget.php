<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

/* 슬라이드 배너 이미지 위젯 - Owl Carousel */

na_script('owl');

//add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$widget_url.'/widget.css">', 0);

// 이미지 영역 크기 설정
$wset['thumb_w'] = (!isset($wset['thumb_w']) || !$wset['thumb_w']) ? 400 : (int)$wset['thumb_w'];
$wset['thumb_h'] = (!isset($wset['thumb_h']) || !$wset['thumb_h']) ? 225 : (int)$wset['thumb_h'];

// 간격
if(!isset($wset['margin']) || $wset['margin'] == "") {
	$wset['margin'] = (G5_IS_MOBILE) ? 16 : 12;
}

// 높이
$img_height = ($wset['thumb_w'] && $wset['thumb_h']) ? ($wset['thumb_h'] / $wset['thumb_w']) * 100 : '56.25';

// 랜덤아이디
$id = 'banner_'.na_rid(); 

?>
<ul id="<?php echo $id;?>" class="owl-carousel basic-banner">
	<?php
	$list = array();

	$n = 0;
	if(isset($wset['d']['img']) && is_array($wset['d']['img'])) {
		$data_cnt = count($wset['d']['img']);
		for($i=0; $i < $data_cnt; $i++) {
			if(isset($wset['d']['img'][$i]) && $wset['d']['img'][$i]) {
				$list[$n]['img'] = na_thumb(na_url($wset['d']['img'][$i]), $wset['thumb_w'], $wset['thumb_h']);
				$list[$n]['link'] = isset($wset['d']['link'][$i]) ? na_url($wset['d']['link'][$i]) : '';
				$list[$n]['alt'] = isset($wset['d']['alt'][$i]) ? get_text($wset['d']['alt'][$i]) : '';
				$list[$n]['target'] = isset($wset['d']['target'][$i]) ? $wset['d']['target'][$i] : '_self';
				$n++;
			}
		}
	}

	$list_cnt = $n;

	// 샘플
	if(!$list_cnt) {
		$sample = array();
		@include(NA_THEME_ADMIN_PATH.'/sample.php');
		$data_cnt = count($sample);
		for($i=0; $i < $data_cnt; $i++) {
			$list[$i]['img'] = 'https://img.youtube.com/vi/'.$sample[$i][0].'/maxresdefault.jpg';
			$list[$i]['link'] = 'https://youtu.be/'.$sample[$i][0];
			$list[$i]['alt'] = $sample[$i][1];
			$list[$i]['target'] = '_blank';
		}
		$list_cnt = $i;
	}

	// 랜덤
	if(isset($wset['rand']) && $wset['rand'] && $list_cnt) 
		shuffle($list);

	for ($i=0; $i < $list_cnt; $i++) { 
	?>
		<li class="item">
			<div class="img-wrap" style="padding-bottom:<?php echo $img_height; ?>%;">
				<div class="img-item">
					<?php if($list[$i]['link']) { ?>
						<a href="<?php echo $list[$i]['link'] ?>" target="<?php echo $list[$i]['target'] ?>">
							<img src="<?php echo $list[$i]['img'] ?>" alt="<?php echo $list[$i]['alt'] ?>" class="na-round">
						</a>
					<?php } else { ?>
						<img src="<?php echo $list[$i]['img'] ?>" alt="<?php echo $list[$i]['alt'] ?>" class="na-round">
					<?php } ?>
				</div>
			</div>
		</li>
	<?php } ?>

	<?php if(!$list_cnt) { ?>
		<li class="item">
			<div class="img-wrap bg-primary">
				<div class="img-item">
					<div class="position-absolute text-white text-center mt-n3 w-100 f-de" style="top:50%; left:0;">
						위젯설정에서 배너 등록				
					</div>
				</div>
			</div>
		</li>
	<?php } ?>
</ul>

<script>
$('#<?php echo $id;?>').owlCarousel({
	autoplay:<?php echo (isset($wset['auto']) && $wset['auto']) ? 'false' : 'true'; ?>,
	autoplayHoverPause:true,
	loop:true,
	item:<?php echo (isset($wset['xl']) && $wset['xl']) ? $wset['xl'] : 4; ?>,
	margin:<?php echo $wset['margin'] ?>,
	stagePadding: <?php echo (isset($wset['padding']) && $wset['padding'] != "") ? $wset['padding'] : 0; ?>,
	nav:<?php echo (isset($wset['nav']) && $wset['nav']) ? 'false' : 'true'; ?>,
	dots:false,
	navText:["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
	responsive:{
		0:{ items:<?php echo (isset($wset['xs']) && $wset['xs'] != "") ? $wset['xs'] : 2; ?> },
		575:{ items:<?php echo (isset($wset['sm']) && $wset['sm'] != "") ? $wset['sm'] : 3; ?> },
		767:{ items:<?php echo (isset($wset['md']) && $wset['md'] != "") ? $wset['md'] : 4; ?> },
		991:{ items:<?php echo (isset($wset['lg']) && $wset['lg'] != "") ? $wset['lg'] : 4; ?> },
		1199:{ items:<?php echo (isset($wset['xl']) && $wset['xl'] != "") ? $wset['xl'] : 4; ?> }
	}
});
</script>

<?php if($setup_href) { ?>
	<div class="btn-wset">
		<a href="<?php echo $setup_href;?>" class="btn-setup">
			<span class="f-sm text-muted"><i class="fa fa-cog"></i> 위젯설정</span>
		</a>
	</div>
<?php } ?>