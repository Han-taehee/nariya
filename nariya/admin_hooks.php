<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

class G5_NARIYA_ADMIN {

    // Hook 포함 클래스 작성 요령
    // https://github.com/Josantonius/PHP-Hook/blob/master/tests/Example.php
    /**
     * Class instance.
     */

    public static function getInstance() {
        static $instance = null;
        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

    public static function singletonMethod() {
        return self::getInstance();
    }

    public function __construct() {

		$this->add_hooks();
    }

	public function add_hooks() {
		global $nariya;

		// 관리자 메뉴
		add_replace('admin_amenu', array($this, 'add_admin_amenu'), 1, 1);
		add_replace('admin_menu', array($this, 'add_admin_menu'), 1, 1);

		// 게시판 필드 추가
		if(IS_NA_BBS) {
			add_event('admin_board_form_update', array($this, 'admin_board_form_update'), 1, 2);
		}
	}

	public function add_admin_amenu($admin_amenu){
		global $nariya;

		$admin_amenu['800'] = 'nariya_amenu';

		@ksort($admin_amenu);

		return $admin_amenu;
	}

	public function add_admin_menu($admin_menu){
		global $nariya;

		$admin_menu['menu800'] = array (
			array('800000', '빌더관리', NA_URL.'/'.G5_ADMIN_DIR.'/nariya_admin/config_form.php', 'nariya'),
			array('800100', '나리야설정', NA_URL.'/'.G5_ADMIN_DIR.'/nariya_admin/config_form.php', 'nariya_config')
		);

		if(defined('IS_NA_XP') && IS_NA_XP) {
			$admin_menu['menu800'][] = array('800200', '경험치관리', NA_URL.'/'.G5_ADMIN_DIR.'/nariya_admin/exp_list.php', 'nariya_exp');
		}

		return $admin_menu;
	}

	public function admin_board_form_update($bo_table, $w){
		global $g5;

		// 테이블 필드 추가
		$write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블
		$row = sql_fetch(" SHOW COLUMNS FROM {$write_table} LIKE 'as_type' ");
		if(!$row){
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
		}
	}
}

$GLOBALS['g5_nariya_admin'] = G5_NARIYA_ADMIN::getInstance();