<?php
if (!defined('_GNUBOARD_')) exit;

function na_options($opt, $value) {

	$opt_cnt = count($opt);
	$options = '';
	for($i=0; $i < $opt_cnt; $i++) {
		if(isset($opt[$i][2]) && $opt[$i][2])
			$options .= '<optgroup label="'.$opt[$i][2].'">'.PHP_EOL;

		$options .= '<option value="'.$opt[$i][0].'"'.get_selected($opt[$i][0], $value).'>'.$opt[$i][1].'</option>'.PHP_EOL;

		if(isset($opt[$i][3]) && $opt[$i][3])
			$options .= '</optgroup>'.PHP_EOL;
	}

	return $options;
}

function na_options_color($opt, $value) {

	$opt_cnt = count($opt);
	$options = '';
	for($i=0; $i < $opt_cnt; $i++) {
		if(isset($opt[$i][2]) && $opt[$i][2])
			$options .= '<optgroup label="'.$opt[$i][2].'">'.PHP_EOL;

		$options .= '<option class="bg-'.$opt[$i][0].'" value="'.$opt[$i][0].'"'.get_selected($opt[$i][0], $value).'>'.$opt[$i][1].'</option>'.PHP_EOL;

		if(isset($opt[$i][3]) && $opt[$i][3])
			$options .= '</optgroup>'.PHP_EOL;
	}

	return $options;
}

function na_color_options($value) {
		
	$opt = array();
	$opt[] = array('blue', 'Blue');
	$opt[] = array('indigo', 'Indigo');
	$opt[] = array('purple', 'Purple');
	$opt[] = array('pink', 'Pink');
	$opt[] = array('red', 'Red');
	$opt[] = array('orangered', 'OrangeRed');
	$opt[] = array('orange', 'Orange');
	$opt[] = array('yellow', 'Yellow');
	$opt[] = array('green', 'Green');
	$opt[] = array('teal', 'Teal');
	$opt[] = array('cyan', 'Cyan');
	$opt[] = array('navy', 'Navy');
	$opt[] = array('aqua', 'Aqua');
	$opt[] = array('bittersweet', 'Bittersweet');	
	$opt[] = array('blue-jeans', 'Blue-Jeans');
	$opt[] = array('darkgreen', 'Darkgreen');
	$opt[] = array('grapefruit', 'Grapefruit');
	$opt[] = array('grass', 'Grass');
	$opt[] = array('lavender', 'Lavender');
	$opt[] = array('mint', 'Mint');
	$opt[] = array('pink-rose', 'Pink-Rose');
	$opt[] = array('sunflower', 'Sunflower');
	$opt[] = array('dark', 'Dark');
	$opt[] = array('white', 'White');
	$opt[] = array('primary', 'Primary');
	$opt[] = array('secondary', 'Secondary');
	$opt[] = array('success', 'Success');
	$opt[] = array('info', 'Info');
	$opt[] = array('warning', 'Warning');
	$opt[] = array('danger', 'Danger');

	return na_options_color($opt, $value);
}

function na_target_options($value) {

	$opt = array();
	$opt[] = array('', '내용-현재');
	$opt[] = array('1', '내용-새창');
	$opt[] = array('2', '링크-현재');
	$opt[] = array('3', '링크-새창');

	return na_options($opt, $value);
}

function na_sort_options($value) {

	$opt = array();
	$opt[] = array('', '최근순');
	$opt[] = array('asc', '등록순');
	$opt[] = array('date', '날짜순');
	$opt[] = array('hit', '조회순');
	$opt[] = array('comment', '댓글순');
	$opt[] = array('good', '추천순');
	$opt[] = array('nogood', '비추천순');
	$opt[] = array('like', '추천-비추천순');
	$opt[] = array('rand', '랜덤');

	return na_options($opt, $value);
}

function na_member_options($value) {

	$opt = array();
	$opt[] = array('point', '포인트');
	if(IS_NA_XP) {
		$opt[] = array('exp', '경험치');
	}
	$opt[] = array('post', '글등록');
	$opt[] = array('comment', '댓글등록');
	$opt[] = array('new', '신규가입');
	$opt[] = array('recent', '최근접속');
	$opt[] = array('connect', '현재접속');

	return na_options($opt, $value);
}

function na_shadow_options($value) {

	$opt = array();
	$opt[] = array('', '그림자 없음');
	$opt[] = array('1', '그림자1');
	$opt[] = array('2', '그림자2');
	$opt[] = array('3', '그림자3');
	$opt[] = array('4', '그림자4');

	return na_options($opt, $value);
}

function na_grade_options($value) {

	$options = '';
	for($i=10; $i > 0; $i--) {
		$options .= '<option value="'.$i.'"'.get_selected($i, $value).'>'.$i.'</option>'.PHP_EOL;
	}

	return $options;
}

function na_term_options($value) {

	$opt = array();
	$opt[] = array('', '사용안함');
	$opt[] = array('day', '일자 지정');
	$opt[] = array('today', '오늘');
	$opt[] = array('yesterday', '어제');
	$opt[] = array('week', '주간');
	$opt[] = array('month', '이번달');
	$opt[] = array('prev', '지난달');

	return na_options($opt, $value);
}

function na_skin_options($path, $dir, $value, $opt) {

	$path = $path.'/'.$dir;
	$skin = ($opt) ? na_skin_file_list($path, $opt) : na_skin_dir_list($path);
	$options = '';
	for ($i=0; $i<count($skin); $i++) {
		$options .= "<option value=\"".$skin[$i]."\"".get_selected($value, $skin[$i]).">".$skin[$i]."</option>\n";
	} 

	return $options;
}

function na_owl_in_options($value) {

	$opt = array();
	$opt[] = array('bounce', 'bounce', 'Attention Seekers',0);
	$opt[] = array('flash', 'flash');
	$opt[] = array('pulse', 'pulse');
	$opt[] = array('flash', 'flash');
	$opt[] = array('rubberBand', 'rubberBand');
	$opt[] = array('shake', 'shake');
	$opt[] = array('swing', 'swing');
	$opt[] = array('tada', 'tada');
	$opt[] = array('wobble', 'wobble');
	$opt[] = array('jello', 'jello');
	$opt[] = array('heartBeat', 'heartBeat',0,1);

	$opt[] = array('bounceIn', 'bounceIn', 'Bouncing Entrances',0);
	$opt[] = array('bounceInDown', 'bounceInDown');
	$opt[] = array('bounceInLeft', 'bounceInLeft');
	$opt[] = array('bounceInRight', 'bounceInRight');
	$opt[] = array('bounceInUp', 'bounceInUp',0,1);

	$opt[] = array('fadeIn', 'fadeIn', 'Fading Entrances',0);
	$opt[] = array('fadeInDown', 'fadeInDown');
	$opt[] = array('fadeInDownBig', 'fadeInDownBig');
	$opt[] = array('fadeInLeft', 'fadeInLeft');
	$opt[] = array('fadeInLeftBig', 'fadeInLeftBig');
	$opt[] = array('fadeInRight', 'fadeInRight');
	$opt[] = array('fadeInRightBig', 'fadeInRightBig');
	$opt[] = array('fadeInUp', 'fadeInUp');
	$opt[] = array('fadeInUpBig', 'fadeInUpBig',0,1);

	$opt[] = array('flip', 'flip', 'Flippers',0);
	$opt[] = array('flipInX', 'flipInX');
	$opt[] = array('flipInY', 'flipInY',0,1);

	$opt[] = array('lightSpeedIn', 'lightSpeedIn', 'Lightspeed',1);

	$opt[] = array('rotateIn', 'rotateIn', 'Rotating Entrances',0);
	$opt[] = array('rotateInDownLeft', 'rotateInDownLeft');
	$opt[] = array('rotateInDownRight', 'rotateInDownRight');
	$opt[] = array('rotateInUpLeft', 'rotateInUpLeft');
	$opt[] = array('rotateInUpRight', 'rotateInUpRight',0,1);

	$opt[] = array('slideInUp', 'slideInUp', 'Sliding Entrances',0);
	$opt[] = array('slideInDown', 'slideInDown');
	$opt[] = array('slideInLeft', 'slideInLeft');
	$opt[] = array('slideInRight', 'slideInRight',0,1);

	$opt[] = array('zoomIn', 'zoomIn', 'Zoom Entrances',0);
	$opt[] = array('zoomInDown', 'zoomInDown');
	$opt[] = array('zoomInLeft', 'zoomInLeft');
	$opt[] = array('zoomInRight', 'zoomInRight');
	$opt[] = array('zoomInUp', 'zoomInUp',0,1);

	$opt[] = array('hinge', 'hinge', 'Specials',0);
	$opt[] = array('jackInTheBox', 'jackInTheBox');
	$opt[] = array('rollIn', 'rollIn',0,1);

	return na_options($opt, $value);
}

function na_owl_out_options($value) {

	$opt = array();
	$opt[] = array('bounce', 'bounce', 'Attention Seekers',0);
	$opt[] = array('flash', 'flash');
	$opt[] = array('pulse', 'pulse');
	$opt[] = array('flash', 'flash');
	$opt[] = array('rubberBand', 'rubberBand');
	$opt[] = array('shake', 'shake');
	$opt[] = array('swing', 'swing');
	$opt[] = array('tada', 'tada');
	$opt[] = array('wobble', 'wobble');
	$opt[] = array('jello', 'jello');
	$opt[] = array('heartBeat', 'heartBeat',0,1);

	$opt[] = array('bounceOut', 'bounceOut', 'Bouncing Exits',0);
	$opt[] = array('bounceOutDown', 'bounceOutDown');
	$opt[] = array('bounceOutLeft', 'bounceOutLeft');
	$opt[] = array('bounceOutRight', 'bounceOutRight');
	$opt[] = array('bounceOutUp', 'bounceOutUp',0,1);

	$opt[] = array('fadeOut', 'fadeOut', 'Fading Exits',0);
	$opt[] = array('fadeOutDown', 'fadeOutDown');
	$opt[] = array('fadeOutDownBig', 'fadeOutDownBig');
	$opt[] = array('fadeOutLeft', 'fadeOutLeft');
	$opt[] = array('fadeOutLeftBig', 'fadeOutLeftBig');
	$opt[] = array('fadeOutRight', 'fadeOutRight');
	$opt[] = array('fadeOutRightBig', 'fadeOutRightBig');
	$opt[] = array('fadeOutUp', 'fadeOutUp');
	$opt[] = array('fadeOutUpBig', 'fadeOutUpBig',0,1);

	$opt[] = array('flip', 'flip', 'Flippers',0);
	$opt[] = array('flipOutX', 'flipOutX');
	$opt[] = array('flipOutY', 'flipOutY',0,1);

	$opt[] = array('lightSpeedOut', 'lightSpeedOut', 'Lightspeed',1);

	$opt[] = array('rotateOut', 'rotateOut', 'Rotating Exits',0);
	$opt[] = array('rotateOutDownLeft', 'rotateOutDownLeft');
	$opt[] = array('rotateOutDownRight', 'rotateOutDownRight');
	$opt[] = array('rotateOutUpLeft', 'rotateOutUpLeft');
	$opt[] = array('rotateOutUpRight', 'rotateOutUpRight',0,1);

	$opt[] = array('slideOutUp', 'slideOutUp', 'Sliding Exits',0);
	$opt[] = array('slideOutDown', 'slideOutDown');
	$opt[] = array('slideOutLeft', 'slideOutLeft');
	$opt[] = array('slideOutRight', 'slideOutRight',0,1);

	$opt[] = array('zoomOut', 'zoomOut', 'Zoom Exits',0);
	$opt[] = array('zoomOutDown', 'zoomOutDown');
	$opt[] = array('zoomOutLeft', 'zoomOutLeft');
	$opt[] = array('zoomOutRight', 'zoomOutRight');
	$opt[] = array('zoomOutUp', 'zoomOutUp',0,1);

	$opt[] = array('hinge', 'hinge', 'Specials',0);
	$opt[] = array('jackInTheBox', 'jackInTheBox');
	$opt[] = array('rollOut', 'rollOut',0,1);

	return na_options($opt, $value);
}

function na_aos_options($value) {

	$opt = array();
	$opt[] = array('fade', 'fade', 'Fade animations',0);
	$opt[] = array('fade-up', 'fade-up');
	$opt[] = array('fade-down', 'fade-down');
	$opt[] = array('fade-left', 'fade-left');
	$opt[] = array('fade-right', 'fade-right');
	$opt[] = array('fade-up-right', 'fade-up-right');
	$opt[] = array('fade-up-left', 'fade-up-left');
	$opt[] = array('fade-down-right', 'fade-down-right');
	$opt[] = array('fade-down-left', 'fade-down-left',0,1);

	$opt[] = array('flip-up', 'flip-up', 'Flip animations',0);
	$opt[] = array('flip-down', 'flip-down');
	$opt[] = array('flip-left', 'flip-left');
	$opt[] = array('flip-right', 'flip-right',0,1);

	$opt[] = array('slide-up', 'slide-up', 'Slide animations',0);
	$opt[] = array('slide-down', 'slide-down');
	$opt[] = array('slide-left', 'slide-left');
	$opt[] = array('slide-right', 'slide-right',0,1);

	$opt[] = array('zoom-in', 'zoom-in', 'Zoom animations',0);
	$opt[] = array('zoom-in-up', 'zoom-in-up');
	$opt[] = array('zoom-in-down', 'zoom-in-down');
	$opt[] = array('zoom-in-left', 'zoom-in-left');
	$opt[] = array('zoom-in-right', 'zoom-in-right');
	$opt[] = array('zoom-out-up', 'zoom-out-up');
	$opt[] = array('zoom-out-down', 'zoom-out-down');
	$opt[] = array('zoom-out-left', 'zoom-out-left');
	$opt[] = array('zoom-out-right', 'zoom-out-right',0,1);

	return na_options($opt, $value);
}

// Background Options
function na_bg_options($value) {
		
	$opt = array();
	$opt[] = array('top', '상단 맞춤');
	$opt[] = array('center', '중앙 맞춤');
	$opt[] = array('bottom', '하단 맞춤');
	$opt[] = array('pattern', '패턴 반복');

	return na_options($opt, $value);
}

// Mask Options
function na_mask_options($value) {
		
	$opt = array();
	$opt[] = array('none', '사용안함');
	$opt[] = array('border', 'Border');
	$opt[] = array('brush1', 'Brush 1');
	$opt[] = array('brush2', 'Brush 2');
	$opt[] = array('bubble1', 'Bubble 1');
	$opt[] = array('bubble2', 'Bubble 2');
	$opt[] = array('bubble3', 'Bubble 3');
	$opt[] = array('bubble4', 'Bubble 4');
	$opt[] = array('bubble5', 'Bubble 5');
	$opt[] = array('bubble6', 'Bubble 6');
	$opt[] = array('line1', 'Line 1');
	$opt[] = array('line2', 'Line 2');
	$opt[] = array('round1', 'Round 1');
	$opt[] = array('round2', 'Round 2');
	$opt[] = array('round3', 'Round 3');
	$opt[] = array('round4', 'Round 4');
	$opt[] = array('round5', 'Round 5');
	$opt[] = array('wave1', 'Wave 1');
	$opt[] = array('wave2', 'Wave 2');
	$opt[] = array('wave3', 'Wave 3');
	$opt[] = array('wave4', 'Wave 4');
	$opt[] = array('wave5', 'Wave 5');
	$opt[] = array('special1', 'Special 1');
	$opt[] = array('special2', 'Special 2');
	$opt[] = array('special3', 'Special 3');

	return na_options($opt, $value);
}

function na_cols_options($value) {

	$value = (string)$value;
	
	$opt = array();
	for($i=0;$i < 7;$i++) {
		$opt[] = array((string)$i, (string)$i);
	}

	return na_options($opt, $value);
}

function na_num_options($value) {

	$value = (string)$value;
	
	$opt = array();
	for($i=0;$i < 6;$i++) {
		$opt[] = array((string)$i, (string)$i);
	}

	return na_options($opt, $value);
}

function na_raster_options($value) {

	$opt = array();
	$opt[] = array('0', '사용안함');
	$opt[] = array('1', '사선');
	$opt[] = array('2', '역사선');
	$opt[] = array('3', '도트 A');
	$opt[] = array('4', '도트 B');
	$opt[] = array('5', '격자');
	$opt[] = array('6', '벽돌');

	return na_options($opt, $value);
}