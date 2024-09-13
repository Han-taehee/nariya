<?php
include_once('./_common.php');

// Auth
if($is_admin != 'super') {
	die("최고관리자만 접근할 수 있습니다.");
}

// 테이블
if(!isset($g5['na_tag']) || !$g5['na_tag']) {
	$g5['na_tag'] = G5_TABLE_PREFIX.'na_tag';
	$g5['na_tag_log'] = G5_TABLE_PREFIX.'na_tag_log';
	$g5['na_shingo'] = G5_TABLE_PREFIX.'na_shingo';
}

$na_db_set = na_db_set();

$is_check = false;

// 태그
if(!sql_query(" DESC {$g5['na_tag']} ", false)) {
	sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['na_tag']}` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`type` tinyint(4) NOT NULL DEFAULT '0',
				`idx` varchar(10) NOT NULL DEFAULT '',
				`tag` varchar(255) NOT NULL DEFAULT '',
				`cnt` int(11) NOT NULL DEFAULT '0',
				`regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				`lastdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY  (`id`),
				KEY tag (`tag`, `lastdate`)
			) ".$na_db_set."; ", false);

	$is_check = true;
}

// 태그로그
if(!sql_query(" DESC {$g5['na_tag_log']} ", false)) {
	sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['na_tag_log']}` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`bo_table` varchar(20) NOT NULL DEFAULT '',
				`wr_id` int(11) NOT NULL default '0',
				`tag_id` int(11) NOT NULL DEFAULT '0',
				`tag` varchar(255) NOT NULL DEFAULT '',
				`mb_id` varchar(255) NOT NULL DEFAULT '',
				`regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY  (`id`),
				KEY tag (`tag`)
			) ".$na_db_set."; ", false);

	$is_check = true;
}

// 신고
if(!sql_query(" DESC {$g5['na_shingo']} ", false)) {
	sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['na_shingo']}` (
				`id` int(11) NOT NULL auto_increment,
				`bo_table` varchar(20) NOT NULL default '',
				`wr_id` int(11) NOT NULL default '0',
				`wr_parent` int(11) NOT NULL default '0',
				`mb_id` varchar(255) NOT NULL default '',
				`flag` tinyint(4) NOT NULL default '0',
				`regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY  (`id`),
				UNIQUE KEY `fkey1` (`bo_table`,`wr_id`,`wr_parent`)
			) ".$na_db_set."; ", false);

	$is_check = true;
}

// 새글
$row = sql_fetch(" SHOW COLUMNS FROM `{$g5['board_new_table']}` LIKE 'as_type' ");
if(!$row) {
	sql_query(" ALTER TABLE `{$g5['board_new_table']}` 
					ADD `as_type` tinyint(4) NOT NULL DEFAULT '0' AFTER `mb_id`,
					ADD `as_comment` int(11) NOT NULL DEFAULT '0' AFTER `as_type`,
					ADD `as_good` int(11) NOT NULL DEFAULT '0' AFTER `as_comment`,
					ADD `as_nogood` int(11) NOT NULL DEFAULT '0' AFTER `as_good`
			", false);

	$is_check = true;
}

// 새글
$row = sql_fetch(" SHOW COLUMNS FROM `{$g5['board_new_table']}` LIKE 'as_comment' ");
if(!$row) {
	sql_query(" ALTER TABLE `{$g5['board_new_table']}` 
					ADD `as_comment` int(11) NOT NULL DEFAULT '0' AFTER `as_type`,
					ADD `as_good` int(11) NOT NULL DEFAULT '0' AFTER `as_comment`,
					ADD `as_nogood` int(11) NOT NULL DEFAULT '0' AFTER `as_good`
			", false);

	$is_check = true;
}

// 게시물
$result = sql_query(" select bo_table from `{$g5['board_table']}` ");
if($result) {
	for ($i=0; $row=sql_fetch_array($result); $i++) {

		$write_table = $g5['write_prefix'] . $row['bo_table']; // 게시판 테이블

		$row1 = sql_fetch(" SHOW COLUMNS FROM {$write_table} LIKE 'as_type' ");

		if(!$row1){
			sql_query(" ALTER TABLE `{$write_table}`
							ADD `as_type` tinyint(4) NOT NULL DEFAULT '0' AFTER `wr_10`,
							ADD `as_img` tinyint(4) NOT NULL DEFAULT '0' AFTER `as_type`,
							ADD `as_extend` tinyint(4) NOT NULL DEFAULT '0' AFTER `as_img`,
							ADD `as_down` int(11) NOT NULL DEFAULT '0' AFTER `as_extend`,
							ADD `as_view` int(11) NOT NULL DEFAULT '0' AFTER `as_down`,
							ADD `as_star_score` int(11) NOT NULL DEFAULT '0' AFTER `as_view`,
							ADD `as_star_cnt` int(11) NOT NULL DEFAULT '0' AFTER `as_star_score`,
							ADD `as_choice` int(11) NOT NULL DEFAULT '0' AFTER `as_star_cnt`,
							ADD `as_choice_cnt` int(11) NOT NULL DEFAULT '0' AFTER `as_choice`,
							ADD `as_tag` varchar(255) NOT NULL AFTER `as_choice_cnt`,
							ADD `as_thumb` varchar(255) NOT NULL AFTER `as_tag` 
						", false);

			$is_check = true;
		}

		$row2 = sql_fetch(" SHOW COLUMNS FROM {$write_table} LIKE 'as_img' ");

		if(!$row2){
			sql_query(" ALTER TABLE `{$write_table}` ADD `as_img` tinyint(4) NOT NULL DEFAULT '0' AFTER `as_type` ", false);
			$is_check = true;
		}
	}
}

if(!isset($is_bbs_db)) {
	if($is_check) {
		die('게시판 플러그인 DB 업그레이드가 완료되었습니다.');
	} else {
		die('더 이상 업그레이드 할 내용이 없습니다.'.PHP_EOL.'현재 DB 업그레이드가 완료된 상태입니다.');
	}
}