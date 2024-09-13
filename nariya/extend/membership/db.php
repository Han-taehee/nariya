<?php
include_once('./_common.php');

// Auth
if($is_admin != 'super') {
	die("최고관리자만 접근할 수 있습니다.");
}

// 테이블
if(!isset($g5['na_xp']) || !$g5['na_xp']) {
	$g5['na_xp'] = G5_TABLE_PREFIX.'na_xp';
}

$na_db_set = na_db_set();

$is_check = false;

// 경험치 테이블
if(!sql_query(" DESC {$g5['na_xp']} ", false)) {
	sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['na_xp']}` (
				`xp_id` int(11) NOT NULL AUTO_INCREMENT,
				`mb_id` varchar(20) NOT NULL DEFAULT '',
				`xp_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				`xp_content` varchar(255) NOT NULL DEFAULT '',
				`xp_point` int(11) NOT NULL DEFAULT '0',
				`xp_rel_table` varchar(20) NOT NULL DEFAULT '',
				`xp_rel_id` varchar(20) NOT NULL DEFAULT '',
				`xp_rel_action` varchar(100) NOT NULL DEFAULT '',
				PRIMARY KEY (`xp_id`),
				KEY `index1` (`mb_id`,`xp_rel_table`,`xp_rel_id`,`xp_rel_action`)
			) ".$na_db_set."; ", false);

	$is_check = true;
}

// xp_rel_action 필드 255에서 100으로 자료형 변경 : KEY 때문에...ㅠㅠ
sql_query(" ALTER TABLE `{$g5['na_xp']}` CHANGE `xp_rel_action` `xp_rel_action` VARCHAR(100) NOT NULL DEFAULT '' ", false);

if(!isset($member['as_msg'])) {
	sql_query(" ALTER TABLE `{$g5['member_table']}`
					ADD `as_msg` tinyint(4) NOT NULL DEFAULT '0' AFTER `mb_10`,
					ADD `as_exp` int(11) NOT NULL DEFAULT '0' AFTER `as_msg`,
					ADD `as_level` int(11) NOT NULL DEFAULT '1' AFTER `as_exp` ", false);

    $is_check = true;
}

if(!isset($member['as_max'])) {
	sql_query(" ALTER TABLE `{$g5['member_table']}`
					ADD `as_max` int(11) NOT NULL DEFAULT '0' AFTER `as_level` ", false);

    $is_check = true;
}

if(!isset($is_xp_db)) {
	if($is_check) {
		die('멤버십 플러그인 DB 업그레이드가 완료되었습니다.');
	} else {
		die('더 이상 업그레이드 할 내용이 없습니다.'.PHP_EOL.'현재 DB 업그레이드가 완료된 상태입니다.');
	}
}