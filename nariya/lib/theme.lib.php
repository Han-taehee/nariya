<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// Page ID 생성
function na_pid($link='') {
	global $bo_table, $wr_id, $sca, $gr_id, $co_id, $ca_id, $it_id, $qstr, $demo, $bo_skin_demo, $type;

	if(!$link) {
		$link = G5_URL . str_replace(G5_PATH, '', $_SERVER['SCRIPT_FILENAME']);
	}

	$url = @parse_url(str_replace(G5_URL.'/', '', $link));
	$url['path'] = isset($url['path']) ? $url['path'] : '';
	$file = basename($url['path'],".php");

	$mid = array();
	$href = '';
	$is_pid = false;
	if(strpos($link, G5_BBS_URL) !== false) {
		if($bo_table && ($file == 'board' || $file == 'write')) {
			$me_id = G5_BBS_DIR.'-board-'.$bo_table;			
			$mid[] = $me_id;
			if(IS_DEMO) {
				if($demo) {
					$mid[] = $me_id.'-'.$demo;
				}
				if($bo_skin_demo) {
					$mid[] = $me_id.'-'.$bo_skin_demo;
				}
			}
			if($sca) {
				$mid[] = $me_id.'-'.$sca;
			}
			if($wr_id) {
				$mid[] = $me_id.'-'.$wr_id;
				$href = get_pretty_url($bo_table, $wr_id);
			} else {
				$href = get_pretty_url($bo_table, '', $qstr);
			}
		} else if($gr_id && $file == 'group') {
			$mid[] = G5_BBS_DIR.'-group-'.$gr_id;
		} else if($co_id && $file == 'content') {
			$mid[] = G5_BBS_DIR.'-content-'.$co_id;
			$href = get_pretty_url('content', $co_id, $qstr);
		} else if($file == 'qalist' || $file == 'qaview' || $file == 'qawrite') {
			$mid[] = G5_BBS_DIR.'-qa';
		} else {
			$is_pid = true;
		}
	} else if(IS_YC) {
		if(strpos($link, G5_SHOP_URL) !== false) {
			if($ca_id && ($file == 'list' || $file == 'item')) {
				$me_id = G5_SHOP_DIR.'-list-'.$ca_id;
				$mid[] = $me_id;
				if($it_id) {
					$me_it = G5_SHOP_DIR.'-item-'.$it_id;
					$mid[] = $me_it;
					if(IS_DEMO && $demo) {
						$mid[] = $me_it.'-'.$demo;
					}
					$href = shop_item_url($it_id);
				} else {
					if(IS_DEMO && $demo) {
						$mid[] = $me_id.'-'.$demo;
					}
					$href = add_pretty_shop_url(shop_category_url($ca_id), 'shop', $qstr);
				}
			} else if($type && $file == 'listtype') {
				$mid[] = G5_SHOP_DIR.'-type-'.$type;
				$href = add_pretty_shop_url(shop_type_url($type), 'shop', $qstr);
			} else {
				$is_pid = true;
			}
		} else {
			$is_pid = true;
		}
	} else {
		$is_pid = true;
	}

	if($is_pid) {
		$pdir = str_replace('/', '-', str_replace(basename($url['path']), '', $url['path']));
		if($pdir && substr($pdir, -1) === '-') {
			$pdir = substr($pdir, 0, -1); 
		}
		$pdir = ($pdir) ? $pdir : 'root';
		$me_id = $pdir.'-page-'.$file;
		$mid[] = $me_id;
		if(IS_DEMO && $demo) {
			$mid[] = $me_id.'-'.$demo;
		}
	}

	if(!$href) {
		$href = $link;
		$url = @parse_url($_SERVER['REQUEST_URI']);
		if(isset($url['query']) && $url['query']) {
			$href .= '?'.$url['query'];
		}
	}

	$pset = array('pid'=>$mid[0], 'mid'=>$mid, 'href'=>$href, 'file'=>$file);

	return $pset;
}

// SEO 생성
function na_seo($buffer, $opt='') {

	if($opt) {
		ob_start();
		include_once (NA_PATH.'/theme/seo.php');
		$meta = ob_get_contents();
		ob_end_clean();

		if(trim($meta)) {
			$nl = "\n";
		    $buffer = preg_replace('#(</title>)#', "$0{$nl}$meta", $buffer);
		}
	}

	return $buffer;
}

// 테마 설정 불러오기
function na_theme($id, $pid) {

	$data = array();

	$id = na_fid($id);
	$device = (G5_IS_MOBILE) ? 'mo' : 'pc';
	$file = G5_THEME_PATH.'/storage/theme-'.$id.'-'.$device.'.php';
	//초기세팅
	if(!is_file($file)) {
		$file = G5_THEME_PATH.'/adm/theme-'.$id.'-setting.php';
	}
	$data = na_file_var_load($file);

	// 인덱스 설정이 없으면 페이지 설정 가져옴
	if($pid) {
		$pdata = array();
		$pid = na_fid($pid);
		$pdata = na_file_var_load(G5_THEME_PATH.'/storage/page/page-'.$pid.'-'.$device.'.php');
		if(!empty($pdata))
			$data = array_merge($data, $pdata);
	}

	// 데모용
	if(IS_DEMO) {
		global $config;

		$pv_name = $config['cf_theme'].'_'.$id;
		$pv = get_session($pv_name);
		if($pv) {
			$pc = na_unpack(stripslashes($pv));

			if(!empty($pc))
				$data = array_merge($data, $pc);
		}

		// 폰트셋
		if(!G5_IS_MOBILE) {
			$pv_font = $config['cf_theme'].'_font';
			$pvf = get_session($pv_font);
			if($pvf) {
				$data['font'] = $pvf;
			}
		}

		// 컬러셋
		$pv_color = $config['cf_theme'].'_color';
		$pvc = get_session($pv_color);
		if($pvc) {
			$data['color'] = $pvc;
		}
	}

	return $data;
}

// 스킨설정 페이지
function na_setup_href($type, $wid='', $wname='') {

	$href = NA_URL.'/theme/';

	if($type == 'widget') {
		$href .= 'widget_form.php?wname='.urlencode($wname).'&amp;wid='.urlencode($wid);
	} else if($type == 'board') {
		$href .= 'skin_form.php?bo_table='.urlencode($wid).'&amp;skin=board';
	} else {
		$href .= 'skin_form.php?skin='.$type;
	}

	return $href;
}

// 관리페이지 페이지
function na_theme_href($type, $mode='', $fid='', $sid='') {
	global $is_modal_win;

	$href = NA_URL.'/theme/';

	if($type == 'image') {
		$href .= 'image_form.php?mode='.urlencode($mode);
		if($fid)
			$href .= '&amp;fid='.urlencode($fid);
		if($is_modal_win)
			$href .= '&amp;win=1';
	} else if($type == 'menu') {
		$href .= 'menu_search.php';
	} else {
		return;
	}

	return $href;
}

// 메뉴 아이템 가공
function na_menu_item($it) {

	$me = array();

	$me = $it;
	
	// url 치환
	$it['href'] = isset($it['href']) ? $it['href'] : '';
	$it['href'] = na_url_amp(na_url($it['href']));

	// 링크 분석
	if(strpos($it['href'], G5_URL) !== false) {

		// url 분해
		$url = @parse_url(str_replace(G5_URL.'/', '', $it['href']));
		$url['path'] = isset($url['path']) ? $url['path'] : '';
		$url['query'] = isset($url['query']) ? $url['query'] : '';
		$file = basename($url['path'],".php");

		// parameter 분해
		@parse_str($url['query'], $query);

		$query['demo'] = isset($query['demo']) ? $query['demo'] : '';

		$is_pid = false;
		if(strpos($it['href'], G5_BBS_URL) !== false) {
			if(isset($query['bo_table']) && $query['bo_table'] && ($file == 'board' || $file == 'write')) {
				$me['id'] = G5_BBS_DIR.'-board-'.$query['bo_table'];

				$query['wr_id'] = isset($query['wr_id']) ? $query['wr_id'] : '';
				$query['sca'] = isset($query['sca']) ? $query['sca'] : '';

				if($query['wr_id']) {
					$me['id'] .= '-'.$query['wr_id'];
				} else if($query['sca']) {
					$me['id'] .= '-'.$query['sca'];
				} else if($query['demo']) {
					$me['id'] .= '-'.$query['demo'];
				} else if($query['bo_skin']) {
					$me['id'] .= '-'.$query['bo_skin'];
				}
				$me['bo_table'] = $query['bo_table'];
				$me['wr_id'] = $query['wr_id'];
				$me['sca'] = $query['sca'];
				$me['href'] = short_url_clean($it['href']);
			} else if(isset($query['gr_id']) && $query['gr_id'] && $file == 'group') {
				$me['id'] = G5_BBS_DIR.'-group-'.$query['gr_id'];
				$me['gr_id'] = $query['gr_id'];
				$me['href'] = $it['href'];
			} else if(isset($query['co_id']) && $query['co_id'] && $file == 'content') {
				$me['id'] = G5_BBS_DIR.'-content-'.$query['co_id'];
				$me['co_id'] = $query['co_id'];
				$me['href'] = short_url_clean($it['href']);
			} else if($file == 'qalist' || $file == 'qaview' || $file == 'qawrite') {
				$me['id'] = G5_BBS_DIR.'-qa';
				$me['href'] = $it['href'];
			} else {
				$is_pid = true;
			}
		} else if(IS_YC) {
			if(strpos($it['href'], G5_SHOP_URL) !== false) {
				if(isset($query['type']) && $query['type'] && $file == 'listtype') {
					$me['id'] = G5_SHOP_DIR.'-type-'.$query['type'];
					$me['type'] = $query['type'];
					//$me['href'] = shop_short_url_clean($it['href']);
					$me['href'] = shop_type_url($query['type']);
				} else if(isset($query['it_id']) && $query['it_id'] && $file == 'item') {
					$me['id'] = G5_SHOP_DIR.'-item-'.$query['it_id'];
					if($query['demo']) {
						$me['id'] .= '-'.$query['demo'];
					}
					$me['it_id'] = $query['it_id'];
					//$me['href'] = shop_short_url_clean($it['href']);
					$me['href'] = shop_item_url($query['it_id']);
				} else if(isset($query['ca_id']) && $query['ca_id'] && $file == 'list') {
					$me['id'] = G5_SHOP_DIR.'-list-'.$query['ca_id'];
					if($query['demo']) {
						$me['id'] .= '-'.$query['demo'];
					}
					$me['ca_id'] = $query['ca_id'];
					//$me['href'] = shop_short_url_clean($it['href']);
					$me['href'] = shop_category_url($query['ca_id']);
				} else {
					$is_pid = true;
				}
			} else {
				$is_pid = true;
			}
		} else {
			$is_pid = true;
		}

		if($is_pid) {
			$pdir = str_replace('/', '-', str_replace(basename($url['path']), '', $url['path']));
			if($pdir && substr($pdir, -1) === '-') {
				$pdir = substr($pdir, 0, -1); 
			}
			$pdir = ($pdir) ? $pdir : 'root';
			$me['id'] = $pdir.'-page-'.$file;
			if($query['demo']) {
				$me['id'] .= '-'.$query['demo'];
			}
			$me['href'] = $it['href'];
		}

	} else {
		$me['id'] = 'link';
		$me['href'] = $it['href'];
	}
	
	// 링크 정리
	$me['href'] = na_url_amp(na_url_amp($me['href']), 1);
	if(!$me['href'])
		$me['href'] = '#';

	// 아이콘 정리
	$me['icon'] = (isset($me['icon']) && $me['icon']) ? $me['icon'] : 'empty';

	// 회원등급
	$me['grade'] = isset($me['grade']) ? (int)$me['grade'] : 0;

	if(isset($me['device']))
		unset($me['device']);

	if(isset($me['title']))
		unset($me['title']);

	return $me;
}

// 메뉴 리스트 생성 - 정리는 나중에...ㅠㅠ
function na_menu_list($nu, $device='') {
	
	$me = array();

	// 주메뉴
	$i = 0;
	$nu_cnt = (is_array($nu)) ? count($nu) : 0;
	for($o=0; $o < $nu_cnt; $o++) {

		// 기기 체크
		$me_device = isset($nu[$o]['device']) ? $nu[$o]['device'] : '';

		if($device && $me_device && $me_device != $device)
			continue;

		$me[$i] = na_menu_item($nu[$o]);

		if(isset($me[$i]['children']))
			unset($me[$i]['children']);

		// 1차 서브
		if(isset($nu[$o]['children'])) {
			$j = 0;
			$nu1_cnt = (is_array($nu[$o]['children'])) ? count($nu[$o]['children']) : 0;
			for($p=0; $p < $nu1_cnt; $p++) {

				// 기기 체크
				$me_device = isset($nu[$o]['children'][$p]['device']) ? $nu[$o]['children'][$p]['device'] : '';
				if($device && $me_device && $me_device != $device)
					continue;

				$me[$i]['s'][$j] = na_menu_item($nu[$o]['children'][$p]);

				if(isset($me[$i]['s'][$j]['children']))
					unset($me[$i]['s'][$j]['children']);

				//2차 서브
				if(isset($nu[$o]['children'][$p]['children'])) {
					$k = 0;
					$nu2_cnt = (is_array($nu[$o]['children'][$p]['children'])) ? count($nu[$o]['children'][$p]['children']) : 0;
					for($q=0; $q < $nu2_cnt; $q++) {

						// 기기 체크
						$me_device = isset($nu[$o]['children'][$p]['children'][$q]['device']) ? $nu[$o]['children'][$p]['children'][$q]['device'] : '';
						if($device && $me_device && $me_device != $device)
							continue;

						$me[$i]['s'][$j]['s'][$k] = na_menu_item($nu[$o]['children'][$p]['children'][$q]);
						
						if(isset($me[$i]['s'][$j]['s'][$k]['children']))
							unset($me[$i]['s'][$j]['s'][$k]['children']);

						// 3차 서브
						if(isset($nu[$o]['children'][$p]['children'][$q]['children'])) { 
							$l = 0;
							$nu3_cnt = (is_array($nu[$o]['children'][$p]['children'][$q]['children'])) ? count($nu[$o]['children'][$p]['children'][$q]['children']) : 0;
							for($r=0; $r < $nu3_cnt; $r++) {

								// 기기 체크
								$me_device = isset($nu[$o]['children'][$p]['children'][$q]['children'][$r]['device']) ? $nu[$o]['children'][$p]['children'][$q]['children'][$r]['device'] : '';
								if($device && $me_device && $me_device != $device)
									continue;

								$me[$i]['s'][$j]['s'][$k]['s'][$l] = na_menu_item($nu[$o]['children'][$p]['children'][$q]['children'][$r]);
								
								if(isset($me[$i]['s'][$j]['s'][$k]['s'][$l]['children']))
									unset($me[$i]['s'][$j]['s'][$k]['s'][$l]['children']);

								// 4차 서브
								if(isset($nu[$o]['children'][$p]['children'][$q]['children'][$r]['children'])) { 
									$m = 0;
									$nu4_cnt = (is_array($nu[$o]['children'][$p]['children'][$q]['children'][$r]['children'])) ? count($nu[$o]['children'][$p]['children'][$q]['children'][$r]['children']) : 0;
									for($s=0; $s < $nu4_cnt; $s++) {

										// 기기 체크
										$me_device = isset($nu[$o]['children'][$p]['children'][$q]['children'][$r]['children'][$s]['device']) ? $nu[$o]['children'][$p]['children'][$q]['children'][$r]['children'][$s]['device'] : '';
										if($device && $me_device && $me_device != $device)
											continue;

										$me[$i]['s'][$j]['s'][$k]['s'][$l]['s'][$m] = na_menu_item($nu[$o]['children'][$p]['children'][$q]['children'][$r]['children'][$s]);
	
										if(isset($me[$i]['s'][$j]['s'][$k]['s'][$l]['s'][$m]['children']))
											unset($me[$i]['s'][$j]['s'][$k]['s'][$l]['s'][$m]['children']);
										$m++;
									}
								}
								$l++;
							}
						}
						$k++;
					}
				}
				$j++;
			}
		}
		$i++;
	}

	return $me;
}

// 메뉴 출력
function na_menu($id, $mid, $dm='') {
	global $g5, $member, $sca;

	$me = array();
	$nu = array();
	$nav = array();
	$icon = array();

	$id = na_fid($id);
	$device = (G5_IS_MOBILE) ? 'mo' : 'pc';

	// 메뉴 불러오기
	$nu = na_file_var_load(G5_THEME_PATH.'/storage/menu-'.$id.'-'.$device.'.php');

	// 데모 메뉴
	if(IS_DEMO && $dm) {
		@include(NA_PATH.'/extend/demo/'.$dm.'.php');
		if(isset($menu) && is_array($menu) && count($menu) > 0) {
			$i = (is_array($nu)) ? count($nu) : 0;
			for($j=0; $j < count($menu); $j++) {
				$nu[$i] = $menu[$j];
				$i++;
			}
		}
	}

	// 메뉴 정리
	$i = 0;
	$mb_level = isset($member['mb_level']) ? (int)$member['mb_level'] : 0;
	$nu_cnt = (is_array($nu)) ? count($nu) : 0;
	for($o=0; $o < $nu_cnt; $o++) {

		// 권한 체크
		if(isset($nu[$o]['limit']) && $nu[$o]['limit'] && isset($nu[$o]['grade']) && (int)$nu[$o]['grade'] > $mb_level)
			continue;

		// 1차 서브
		$on1 = 0;
		if(isset($nu[$o]['s'])) {
			$j = 0;
			$nu1_cnt = (is_array($nu[$o]['s'])) ? count($nu[$o]['s']) : 0;
			for($p=0; $p < $nu1_cnt; $p++) {

				// 권한 체크
				$limit1 = isset($nu[$o]['s'][$p]['limit']) ? $nu[$o]['s'][$p]['limit'] : '';
				$grade1 = isset($nu[$o]['s'][$p]['grade']) ? (int)$nu[$o]['s'][$p]['grade'] : 0;
				if($limit1 && $grade1 > $mb_level)
					continue;

				//2차 서브
				$on2 = 0;
				if(isset($nu[$o]['s'][$p]['s'])) {
					$k = 0;
					$nu2_cnt = (is_array($nu[$o]['s'][$p]['s'])) ? count($nu[$o]['s'][$p]['s']) : 0;
					for($q=0; $q < $nu2_cnt; $q++) {

						// 권한 체크
						$limit2 = isset($nu[$o]['s'][$p]['s'][$q]['limit']) ? $nu[$o]['s'][$p]['s'][$q]['limit'] : '';
						$grade2 = isset($nu[$o]['s'][$p]['s'][$q]['grade']) ? (int)$nu[$o]['s'][$p]['s'][$q]['grade'] : 0;
						if($limit2 && $grade2 > $mb_level)
							continue;

						// 3차 서브
						$on3 = 0;
						if(isset($nu[$o]['s'][$p]['s'][$q]['s'])) { 
							$l = 0;
							$nu3_cnt = (is_array($nu[$o]['s'][$p]['s'][$q]['s'])) ? count($nu[$o]['s'][$p]['s'][$q]['s']) : 0;
							for($r=0; $r < $nu3_cnt; $r++) {

								// 권한 체크
								$limit3 = isset($nu[$o]['s'][$p]['s'][$q]['s'][$r]['limit']) ? $nu[$o]['s'][$p]['s'][$q]['s'][$r]['limit'] : '';
								$grade3 = isset($nu[$o]['s'][$p]['s'][$q]['s'][$r]['grade']) ? (int)$nu[$o]['s'][$p]['s'][$q]['s'][$r]['grade'] : 0;
								if($limit3 && $grade3 > $mb_level)
									continue;

								// 4차 서브
								$on4 = 0;
								if(isset($nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'])) { 
									$m = 0;
									$nu4_cnt = (is_array($nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'])) ? count($nu[$o]['s'][$p]['s'][$q]['s'][$r]['s']) : 0;
									for($s=0; $s < $nu4_cnt; $s++) {

										// 권한 체크
										$limit4 = isset($nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'][$s]['limit']) ? $nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'][$s]['limit'] : '';
										$grade4 = isset($nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'][$s]['grade']) ? (int)$nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'][$s]['grade'] : 0;
										if($limit4 && $grade4 > $mb_level)
											continue;

										// 현재 위치
										$me_id = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'][$s]['id'];
										$is_nav = true;
										if($mid && $me_id && in_array($me_id, $mid)) {
											$nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'][$s]['on'] = 1;
											$on4++;
											$text = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'][$s]['text'];
											$href = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'][$s]['href'];
											$target = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'][$s]['target'];
										} else {
											$is_nav = false;
											$nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'][$s]['on'] = 0;
										}

										if($is_nav) {
											$nav[] = array('href'=>$href, 'target'=>$target, 'text'=>$text);
											$icon[] = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'][$s]['icon'];
										}

										// 담기
										$me[$i]['s'][$j]['s'][$k]['s'][$l]['s'][$m] = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'][$s];
										$m++;
									}

									unset($nu[$o]['s'][$p]['s'][$q]['s'][$r]['s']);

									if($m) {
										$nu[$o]['s'][$p]['s'][$q]['s'][$r]['s'] = $me[$i]['s'][$j]['s'][$k]['s'][$l]['s'];
									} else {
										unset($nu[$o]['s'][$p]['s'][$q]['s'][$r]['s']);
									}
								}

								// 현재 위치
								$is_nav = true;
								if($on4) {
									$nu[$o]['s'][$p]['s'][$q]['s'][$r]['on'] = 1;
									$on3++;
									$text = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['text'];
									$href = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['href'];
									$target = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['target'];
								} else {
									$me_id = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['id'];
									if($mid && $me_id && in_array($me_id, $mid)) {
										$nu[$o]['s'][$p]['s'][$q]['s'][$r]['on'] = 1;
										$on3++;
										$text = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['text'];
										$href = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['href'];
										$target = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['target'];
									} else {
										$is_nav = false;
										$nu[$o]['s'][$p]['s'][$q]['s'][$r]['on'] = 0;
									}
								}

								if($is_nav) {
									$nav[] = array('href'=>$href, 'target'=>$target, 'text'=>$text);
									$icon[] = $nu[$o]['s'][$p]['s'][$q]['s'][$r]['icon'];
								}

								// 담기
								$me[$i]['s'][$j]['s'][$k]['s'][$l] = $nu[$o]['s'][$p]['s'][$q]['s'][$r];
								$l++;
							}

							if($l) {
								$nu[$o]['s'][$p]['s'][$q]['s'] = $me[$i]['s'][$j]['s'][$k]['s'];
							} else {
								unset($nu[$o]['s'][$p]['s'][$q]['s']);
							}
						}

						// 현재 위치
						$is_nav = true;
						if($on3) {
							$nu[$o]['s'][$p]['s'][$q]['on'] = 1;
							$on2++;
							$text = $nu[$o]['s'][$p]['s'][$q]['text'];
							$href = $nu[$o]['s'][$p]['s'][$q]['href'];
							$target = $nu[$o]['s'][$p]['s'][$q]['target'];
						} else {
							$me_id = $nu[$o]['s'][$p]['s'][$q]['id'];
							if($mid && $me_id && in_array($me_id, $mid)) {
								$nu[$o]['s'][$p]['s'][$q]['on'] = 1;
								$on2++;
								$text = $nu[$o]['s'][$p]['s'][$q]['text'];
								$href = $nu[$o]['s'][$p]['s'][$q]['href'];
								$target = $nu[$o]['s'][$p]['s'][$q]['target'];
							} else {
								$is_nav = false;
								$nu[$o]['s'][$p]['s'][$q]['on'] = 0;
							}
						}

						if($is_nav) {
							$nav[] = array('href'=>$href, 'target'=>$target, 'text'=>$text);
							$icon[] = $nu[$o]['s'][$p]['s'][$q]['icon'];
						}

						// 담기
						$me[$i]['s'][$j]['s'][$k] = $nu[$o]['s'][$p]['s'][$q];
						$k++;
					}

					if($k) {
						$nu[$o]['s'][$p]['s'] = $me[$i]['s'][$j]['s'];
					} else {
						unset($nu[$o]['s'][$p]['s']);
					}
				}

				// 현재 위치
				$is_nav = true;
				if($on2) {
					$nu[$o]['s'][$p]['on'] = 1;
					$on1++;
					$text = $nu[$o]['s'][$p]['text'];
					$href = $nu[$o]['s'][$p]['href'];
					$target = $nu[$o]['s'][$p]['target'];
				} else {
					$me_id = $nu[$o]['s'][$p]['id'];
					if($mid && $me_id && in_array($me_id, $mid)) {
						$nu[$o]['s'][$p]['on'] = 1;
						$on1++;
						$text = $nu[$o]['s'][$p]['text'];
						$href = $nu[$o]['s'][$p]['href'];
						$target = $nu[$o]['s'][$p]['target'];
					} else {
						$is_nav = false;
						$nu[$o]['s'][$p]['on'] = 0;
					}
				}

				if($is_nav) {
					$nav[] = array('href'=>$href, 'target'=>$target, 'text'=>$text);
					$icon[] = $nu[$o]['s'][$p]['icon'];
				}

				// 담기
				$me[$i]['s'][$j] = $nu[$o]['s'][$p];
				$j++;
			}

			if($j) {
				$nu[$o]['s'] = $me[$i]['s'];
			} else {
				unset($nu[$o]['s']);
			}
		}

		// 현재 위치
		$is_nav = true;
		if($on1) {
			$nu[$o]['on'] = 1;
			$text = $nu[$o]['text'];
			$href = $nu[$o]['href'];
			$target = $nu[$o]['target'];
		} else {
			$me_id = $nu[$o]['id'];
			if($mid && $me_id && in_array($me_id, $mid)) {
				$nu[$o]['on'] = 1;
				$text = $nu[$o]['text'];
				$href = $nu[$o]['href'];
				$target = $nu[$o]['target'];
			} else {
				$is_nav = false;
				$nu[$o]['on'] = 0;
			}
		}

		if($is_nav) {
			$nav[] = array('href'=>$href, 'target'=>$target, 'text'=>$text);
			$icon[] = $nu[$o]['icon'];
		}

		// 담기
		$me[$i] = $nu[$o];
		$i++;
	}

	if(!empty($nav))
		$nav = array_reverse($nav);

	return array($me, $nav);
}

// 사이트 추가 통계 생성
function na_stats_data() {
    global $g5, $config;

	$stats = array();

	// 현재 접속자
    $row = sql_fetch(" select sum(IF(mb_id<>'',1,0)) as mb_cnt, count(*) as total_cnt from {$g5['login_table']} where mb_id <> '{$config['cf_admin']}' ", false);
	$stats['now_total'] = isset($row['total_cnt']) ? (int)$row['total_cnt'] : 0;
	$stats['now_mb'] = isset($row['mb_cnt']) ? (int)$row['mb_cnt'] : 0;

	// 전체회원
	$row = sql_fetch(" select count(*) as cnt from {$g5['member_table']} ", false); 
	$stats['join_total'] = isset($row['cnt']) ? (int)$row['cnt'] : 0;

	// 총 글수 : 검색가능 게시판에 대해서만 집계
	$row = sql_fetch(" select sum(bo_count_write) as w_cnt, sum(bo_count_comment) as c_cnt from {$g5['board_table']} where bo_use_search = 1 ", false); 
	$stats['total_write'] = isset($row['w_cnt']) ? (int)$row['w_cnt'] : 0;
	$stats['total_comment'] = isset($row['c_cnt']) ? (int)$row['c_cnt'] : 0;

    return $stats;
}

// 사이트 통계 출력
function na_stats($ctime) {
	global $g5, $config;

	$ctime = (int)$ctime;

	if($ctime > 0) {
		// 캐시 아이디
		$c_name = 's-'.$g5['ms_id'].'-'.$config['cf_theme'].'-stats';
		$caches = g5_get_cache($c_name);
	    if($caches === false){
			$caches = na_stats_data();
			g5_set_cache($c_name, $caches, 60 * $ctime);
		} else {
			$caches = na_stats_data();
		}
	} else {
		$caches = na_stats_data();
	}

	// 방문통계는 그대로 출력
    // visit 배열변수에 $visit[1] = 오늘, $visit[2] = 어제, $visit[3] = 최대, $visit[4] = 전체 숫자가 들어감
    preg_match("/오늘:(.*),어제:(.*),최대:(.*),전체:(.*)/", $config['cf_visit'], $visit);

	$caches['visit_today'] = isset($visit[1]) ? (int)$visit[1] : 0;
	$caches['visit_yesterday'] = isset($visit[2]) ? (int)$visit[2] : 0;
	$caches['visit_max'] = isset($visit[3]) ? (int)$visit[3] : 0;
	$caches['visit_total'] = isset($visit[4]) ? (int)$visit[4] : 0;

    return $caches;
}

// Page Title
function na_page_title($tset) {
	global $g5, $board, $ca, $it;

	if(isset($tset['page_title']) && $tset['page_title']) {
		$title = $tset['page_title'];
	} else if(isset($board['bo_subject']) && $board['bo_subject']) {
		$title = $board['bo_subject'];
	} else if(isset($it['ca_name']) && $it['ca_name']) {
		$title = $it['ca_name'];
	} else if(isset($ca['ca_name']) && $ca['ca_name']) {
		$title = $ca['ca_name'];
	} else {
		$title = $g5['title'];
	}

	return $title;
}

// Layout Skin Path
function na_layout_content($type, $skin, $basic='') {

	$path = array();
	if($skin == 'none')
		return $path;

	$skin = ($skin) ? $skin : $basic;

	if($skin)
		$path = array(G5_THEME_URL.'/layout/'.$type.'/'.$skin, G5_THEME_PATH.'/layout/'.$type.'/'.$skin); 

	return $path;
}

// Shadow Line
function na_shadow($type='1') {

	if(!$type)
		return;

	$line = '<div class="shadow-line"><img src="'.NA_URL.'/img/shadow'.$type.'.png" alt="Shadow'.$type.'"></div>'.PHP_EOL;

	return $line;
}

// 회원등급명
function na_grade($grade) {
	global $nariya;

	if(!$grade)
		$grade = 1;

	$g = 'mb_gn'.$grade;

	$gn = (isset($nariya[$g]) && $nariya[$g]) ? $nariya[$g] : '';

	return $gn;
}

// 회원정보 재가공
function na_member($member) {
	global $g5, $is_member;

	$member['is_auth'] = false;

	if ($is_member) {
		$member['sideview'] = na_name_photo($member['mb_id'], get_sideview($member['mb_id'], $member['mb_nick'], $member['mb_email'], $member['mb_homepage']));
		$member['mb_scrap_cnt'] = isset($member['mb_scrap_cnt']) ? (int)$member['mb_scrap_cnt'] : 0;
		$sql = " select count(*) as cnt from {$g5['auth_table']} where mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if ($row['cnt'])
           	$member['is_auth'] = true;
    }

	$member['as_level'] = isset($member['as_level']) ? $member['as_level'] : 1; // 레벨	
	$member['as_noti'] = isset($member['as_noti']) ? $member['as_noti'] : 0; // 알림수
	$member['mb_memo_cnt'] = isset($member['mb_memo_cnt']) ? $member['mb_memo_cnt'] : 0; // 쪽지수
	$member['noti_cnt'] = $member['as_noti'] + $member['mb_memo_cnt'];
	$member['mb_grade'] = na_grade($member['mb_level']);

	return $member;
}

// 위젯 캐시
function na_widget_cache($widget_file, $wset, $wcache){
	global $g5, $config;

	if(!is_file($widget_file)) 
		return;

	$widget_url = isset($wcache['url']) ? $wcache['url'] : '';
	$widget_path = isset($wcache['path']) ? $wcache['path'] : '';

	$wid = isset($wcache['id']) ? $wcache['id'] : '';
	$addon = isset($wset['addon']) ? $wset['addon'] : '';
	$ctime = isset($wset['cache']) ? (int)$wset['cache'] : 0;

	//캐시 적용시
	if($ctime > 0) { 
		// 캐시 아이디
		$c_id = 'w-'.$g5['ms_id'].'-'.$config['cf_theme'];
		if($addon) {
			$c_name = (G5_IS_MOBILE) ? $c_id.'-ma-'.$wid : $c_id.'-pa-'.$wid;
		} else {
			$c_name = (G5_IS_MOBILE) ? $c_id.'-mw-'.$wid : $c_id.'-pw-'.$wid;
		}

		$caches = g5_get_cache($c_name);

	    if($caches === false){
			ob_start();
			@include($widget_file);
			$widget = ob_get_contents();
			ob_end_clean();

            $caches = array('widget' => $widget);

			g5_set_cache($c_name, $caches, 60 * $ctime);

		} else {
			$widget = isset($caches['widget']) ? $caches['widget'] : '';
		}
	} else {
	    ob_start();
		@include ($widget_file);
	    $widget = ob_get_contents();
		ob_end_clean();
	}

	return $widget;
}

// 위젯 파일 캐시
function na_widget_cache_file($widget_file, $wset, $wcache){

	if(!is_file($widget_file)) 
		return;

	$widget_url = $wcache['url'];
	$widget_path = $wcache['path'];

	$wid = $wcache['id'];
	$cache = (int)$wset['cache'];

	if($cache > 0) { //캐시 적용시

		$cache_file = $wcache['file'];

		$is_cache = true;
		if(is_file($cache_file)) {
			$filetime = filemtime($cache_file);
			if($filetime && $filetime > (G5_SERVER_TIME - $cache)) {
				$is_cache = false;
			}
        } 

		if($is_cache) {
			ob_start();
			@include($widget_file);
			$widget = ob_get_contents();
			ob_end_clean();

			// 이전 캐시 삭제
			@unlink($cache_file);

			$handle = fopen($cache_file, 'w');
			$content = "<?php\nif (!defined('_GNUBOARD_')) exit;\n?>\n".$widget."\n";
			fwrite($handle, $content);
			fclose($handle);
		} else {
			ob_start();
			@include($cache_file);
			$widget = ob_get_contents();
			ob_end_clean();
		}
	} else {
	    ob_start();
		@include ($widget_file);
	    $widget = ob_get_contents();
		ob_end_clean();
	}

	return $widget;
}

// 위젯 함수
function na_widget($wname, $wid='', $opt='', $mopt='', $wdir='', $addon=''){
	global $is_admin;

	// 적합성 체크
	if(!na_check_id($wname) || !na_check_id($wid))
		return '<p class="text-muted text-center">스킨명과 아이디는 영문자, 숫자, -, _ 만 가능함</p>';

	if($wdir) {
	    $wdir = preg_replace('/[^-A-Za-z0-9_\/]/i', '', trim(str_replace(G5_PATH, '', $wdir)));
		$widget_path = G5_PATH.$wdir.'/'.$wname;
		$widget_url = str_replace(G5_PATH, G5_URL, $widget_path);
	} else if($addon) {
		$widget_url = NA_URL.'/skin/addon/'.$wname;
		$widget_path = NA_PATH.'/skin/addon/'.$wname;
	} else {
		$widget_url = G5_THEME_URL.'/widget/'.$wname;
		$widget_path = G5_THEME_PATH.'/widget/'.$wname;
	}

	if(!is_file($widget_path.'/widget.php')) 
		return;

	$wchk = ($addon) ? 'addon' : 'widget'; 
	$wfile = (G5_IS_MOBILE) ? 'mo' : 'pc'; 
	$widget_file = G5_THEME_PATH.'/storage/'.$wchk.'/'.$wchk.'-'.$wname.'-'.$wid.'-'.$wfile.'.php';
	$cache_file = G5_THEME_PATH.'/storage/cache/'.$wchk.'-'.$wname.'-'.$wid.'-'.$wfile.'-cache.php';
	$setup_href = '';

	// 캐시용
	$wcache = array('id'=>$wid, 'url'=>$widget_url, 'path'=>$widget_path, 'file'=>$cache_file, 'addon'=>$addon);

	$wset = array();

	$is_opt = true;
	if($wid) {
		if(is_file($widget_file)) {
			$wset = na_file_var_load($widget_file);
			$is_opt = false;
		}

		if($is_admin == 'super' || IS_DEMO) {
			$setup_href = na_setup_href('widget', $wid, $wname);
			if($wdir) {
				$setup_href .= '&amp;wdir='.urlencode($wdir);
			}
			if($addon) {
				$setup_href .= '&amp;opt=1';
			}
		}
	}
	
	if($is_opt && $opt) {
		$wset = na_query($opt);
		if(G5_IS_MOBILE && !empty($wset) && $mopt) {
			$wset = array_merge($wset, na_query($mopt));
		}

		// 설정 초기값
		if($setup_href) {
			$setup_href .= '&amp;optp='.urlencode($opt);
			if($mopt) {
				$setup_href .= '&amp;optm='.urlencode($mopt);
			}
		}
		// 옵션지정시 추가쿼리구문 작동안됨
		unset($wset['where']);
		unset($wset['orderby']);
	}

	// 초기값
	$wset['cache'] = isset($wset['cache']) ? (int)$wset['cache'] : 0;
	$wset['bo_new'] = isset($wset['bo_new']) ? (int)$wset['bo_new'] : 24;

	$is_ajax = false;

    ob_start();
	@include ($widget_path.'/widget.php');
    $widget = ob_get_contents();
	ob_end_clean();

	return $widget;
}

// 애드온 함수
function na_addon($wname, $wid='', $opt='', $mopt='', $wdir=''){
	return na_widget($wname, $wid, $opt, $mopt, $wdir, 1);
}

// 기간 체크
function na_sql_term($term, $field) {

	$sql_term = '';
	if($term && $field) {
		if($term > 0 || $term == 'week') {
			$term = ($term == 'week') ? 1 + (int)date("w", G5_SERVER_TIME) : $term;
			$chk_term = date("Y-m-d H:i:s", G5_SERVER_TIME - ($term * 86400));
			$sql_term = " and $field >= '{$chk_term}' ";
		} else {
			$day = getdate();
			$today = $day['year'].'-'.sprintf("%02d",$day['mon']).'-'.sprintf("%02d",$day['mday']).' 00:00:00';	// 오늘
			$yesterday = date("Y-m-d", (G5_SERVER_TIME - 86400)).' 00:00:00'; // 어제
			$nowmonth = $day['year'].'-'.sprintf("%02d",$day['mon']).'-01 00:00:00'; // 이번달

			if($day['mon'] == "1") { //1월이면
				$prevyear = $day['year'] - 1;
				$prevmonth = $prevyear.'-12-01 00:00:00';
			} else {
				$prev = $day['mon'] - 1;
				$prevmonth = $day['year'].'-'.sprintf("%02d",$prev).'-01 00:00:00';
			}

			switch($term) {
				case 'today'		: $sql_term = " and $field >= '{$today}'"; break;
				case 'yesterday'	: $sql_term = " and $field >= '{$yesterday}' and $field < '{$today}'"; break;
				case 'month'		: $sql_term = " and $field >= '{$nowmonth}'"; break;
				case 'prev'			: $sql_term = " and $field >= '{$prevmonth}' and $field < '{$nowmonth}'"; break;
			}
		}
	}

	return $sql_term;
}

// 자료 소팅
function na_sql_sort($type, $sort) {

	$orderby = '';
	if($type == 'new') {
		if(IS_NA_BBS) {
			switch($sort) { 
				case 'asc'		: $orderby = 'a.bn_id'; break;
				case 'date'		: $orderby = 'a.bn_datetime desc'; break;
				case 'comment'	: $orderby = 'a.as_comment desc'; break;
				case 'good'		: $orderby = 'a.as_good desc'; break;
				case 'nogood'	: $orderby = 'a.as_nogood desc'; break;
				case 'like'		: $orderby = '(a.as_good - a.as_nogood) desc'; break;
				case 'rand'		: $orderby = 'rand()'; break;
				default			: $orderby = 'a.bn_id desc'; break;
			}
		} else {
			switch($sort) { 
				case 'asc'		: $orderby = 'a.bn_id'; break;
				case 'date'		: $orderby = 'a.bn_datetime desc'; break;
				case 'rand'		: $orderby = 'rand()'; break;
				default			: $orderby = 'a.bn_id desc'; break;
			}
		}
	} else if($type == 'bo') {
		switch($sort) { 
			case 'asc'			: $orderby = 'wr_id'; break;
			case 'date'			: $orderby = 'wr_datetime desc'; break;
			case 'hit'			: $orderby = 'wr_hit desc'; break;
			case 'comment'		: $orderby = 'wr_comment desc'; break;
			case 'good'			: $orderby = 'wr_good desc'; break;
			case 'nogood'		: $orderby = 'wr_nogood desc'; break;
			case 'like'			: $orderby = '(wr_good - wr_nogood) desc'; break;
			case 'link'			: $orderby = '(wr_link1_hit + wr_link2_hit) desc'; break;
			case 'rand'			: $orderby = 'rand()'; break;
			default				: $orderby = 'wr_id desc'; break;
		}
	}

	return $orderby;
}

// 게시판 정리
function na_sql_find($field, $str, $ex) {

	if(!$field || !$str)
		return;

	$ex = ($ex) ? '=0' : '';
	$sql = "and find_in_set(".$field.", '".$str."')".$ex;

	return $sql;
}

// 랭킹시작 번호
function na_rank_start($rows, $page) {

	$rows = (int)$rows;
	$page = (int)$page;

	$rank = ($rows > 0 && $page > 1) ?  (($page - 1) * $rows + 1) : 1;

	return $rank;
}

// Date & Time
function na_date($date, $class='', $day='H:i', $month='m.d H:i', $year='Y.m.d H:i') {

	$date = strtotime($date);
	$today = date('Y-m-d', $date);

	if (G5_TIME_YMD == $today) {
		if($day == 'before') {
			$diff = G5_SERVER_TIME - $date;

			$s = 60; //1분 = 60초
			$h = $s * 60; //1시간 = 60분
			$d = $h * 24; //1일 = 24시간
			$y = $d * 10; //1년 = 1일 * 10일

			if ($diff < $s) {
				$time = $diff.'초 전';
			} else if ($h > $diff && $diff >= $s) {
				$time = round($diff / $s).'분 전';
			} else if ($d > $diff && $diff >= $h) {
				$time = round($diff / $h).'시간 전';
			} else {
				$time = date($day, $date);
			} 
		} else {
			$time = date($day, $date);
		}

		if($class) {
			$time = '<span class="'.$class.'">'.$time.'</span>';
		}
	} else if(substr(G5_TIME_YMD, 0, 7) == substr($today, 0, 7)) {
		$time = date($month, $date);
	} else {
		$time = date($year, $date);
	} 

	return $time;
}

// 게시물 정리
function na_wr_row($wr, $wset) {
	global $g5;

	//비번은 아예 배열에서 삭제
	if(isset($wr['wr_password']))
		unset($wr['wr_password']);

	//이메일 저장 안함
	$wr['wr_email'] = '';
	if($wset['comment']) { // 댓글일 때
		if (strstr($wr['wr_option'], 'secret')){
			$wr['wr_subject'] = $wr['wr_content'] = '비밀댓글입니다.';
		} else {
			$tmp_write_table = $g5['write_prefix'] . $wr['bo_table'];
			$row = sql_fetch("select wr_option from $tmp_write_table where wr_id = '{$wr['wr_parent']}' ", false);
			if (strstr($row['wr_option'], 'secret')){
				$wr['wr_subject'] = $wr['wr_content'] = '비밀댓글입니다.';
				$wr['wr_option'] = $row['wr_option'];
			} else {
				// 댓글에서 40자 잘라서 글제목으로
				$wr['wr_subject'] = cut_str($wr['wr_content'], 80);
			}
		}
	} else if (strstr($wr['wr_option'], 'secret')){
		$wr['wr_content'] = '비밀글입니다.';
		$wr['wr_link1'] = $wr['wr_link2'] = '';
		$wr['file'] = array('count'=>0);
	}

	$bo = array();
	$bo['bo_table'] = $wr['bo_table'];
	$bo['bo_new'] = (isset($wset['bo_new']) && $wset['bo_new']) ? $wset['bo_new'] : 24;
	$bo['bo_use_list_content'] = isset($wset['list_content']) ? $wset['list_content'] : '';
	$bo['bo_use_sideview'] = isset($wset['sideview']) ? $wset['sideview'] : '';
	$bo['bo_use_list_file'] = isset($wset['list_file']) ? $wset['list_file'] : '';

	$list = array();
	$list = na_get_list($wr, $bo);

	if($bo['bo_use_sideview']) {
		$list['name'] = na_name_photo($list['mb_id'], $list['name']);
	}

	return $list;
}

// 그룹 내 게시판
function na_bo_list($gr_list, $gr_except, $bo_list, $bo_except) {
	global $g5;

	$bo = array();
	$plus = array();
	$minus = array();

	if($gr_list) {
		$gr = array();

		// 지정그룹의 게시판 다 뽑기
		$result = sql_query(" select bo_table from {$g5['board_table']} where find_in_set(gr_id, '{$gr_list}') ", false);
		if($result) {
			for ($i=0; $row=sql_fetch_array($result); $i++) {
				$gr[] = $row['bo_table'];
			}
		}

		if($bo_list) {
			$bo = na_explode(',', $bo_list);
			if($gr_except) {
				if($bo_except) {
					$gr = array_merge($gr, $bo);
					$minus = array_unique($gr);
				} else {
					$minus = array_diff($gr, $bo);
					$plus = $bo;
				}
			} else {
				if($bo_except) {
					$plus = array_diff($gr, $bo);
					$minus = $bo;
				} else {
					$gr = array_merge($gr, $bo);
					$plus = array_unique($gr);
				}
			}
		} else {
			if($gr_except) {
				$minus = $gr;				
			} else {
				$plus = $gr;
			}
		}
	} else if($bo_list) {
		$bo = na_explode(',', $bo_list);
		if($bo_except) {
			$minus = $bo;
		} else {
			$plus = $bo;
		}
	} 

	return array(implode(',', $plus), implode(',', $minus));
}

// 게시물 추출
function na_board_rows($wset) {
	global $g5, $member;

	$list = array();

	$rows = isset($wset['rows']) ? (int)$wset['rows'] : 0;
	$rows = ($rows > 0) ? $rows : 7;
	$page = isset($wset['page']) ? (int)$wset['page'] : 0;
	$page = ($page > 1) ? $page : 1;

	//페이징
	$paging = isset($wset['paging']) ? (int)$wset['paging'] : 0;
	$pmax = isset($wset['pmax']) ? (int)$wset['pmax'] : 0;

	$wset['bo_list'] = isset($wset['bo_list']) ? $wset['bo_list'] : '';
	$wset['bo_except'] = isset($wset['bo_except']) ? $wset['bo_except'] : '';
	$wset['gr_list'] = isset($wset['gr_list']) ? $wset['gr_list'] : '';
	$wset['gr_except'] = isset($wset['gr_except']) ? $wset['gr_except'] : '';
	$wset['ca_list'] = isset($wset['ca_list']) ? $wset['ca_list'] : '';
	$wset['ca_except'] = isset($wset['ca_except']) ? $wset['ca_except'] : '';
	$wset['mb_list'] = isset($wset['mb_list']) ? $wset['mb_list'] : '';
	$wset['mb_except'] = isset($wset['mb_except']) ? $wset['mb_except'] : '';
	$wset['list_content'] = isset($wset['list_content']) ? $wset['list_content'] : '';
	$wset['sideview'] = isset($wset['sideview']) ? $wset['sideview'] : '';
	$wset['list_file'] = isset($wset['list_file']) ? $wset['list_file'] : '';

	$wset['term'] = isset($wset['term']) ? $wset['term'] : '';
	$wset['dayterm'] = isset($wset['dayterm']) ? (int)$wset['dayterm'] : 0;
	$wset['main'] = (IS_NA_BBS && isset($wset['main'])) ? (int)$wset['main'] : '';
	$wset['sort'] = isset($wset['sort']) ? $wset['sort'] : '';
	$wset['comment'] = isset($wset['comment']) ? $wset['comment'] : '';

	$bo_table = $wset['bo_list'];
	$term = ($wset['term'] == 'day' && $wset['dayterm'] > 0) ? $wset['dayterm'] : $wset['term'];
	$sql_where = (isset($wset['where']) && $wset['where']) ? 'and '.$wset['where'] : '';
	$sql_orderby = (isset($wset['orderby']) && $wset['orderby']) ? $wset['orderby'].',' : '';

	$start_rows = 0;
	$board_cnt = na_explode(',', $bo_table);
	if(!$bo_table || count($board_cnt) > 1 || $wset['bo_except']) {

		// 메인글
		$sql_main = ($wset['main']) ? "and a.as_type = '".$wset['main']."'" : "";

		// 회원글
		$sql_mb = na_sql_find('a.mb_id', $wset['mb_list'], $wset['mb_except']);

		// 정렬
		$orderby = na_sql_sort('new', $wset['sort']);
		$orderby = ($orderby) ? $orderby : 'a.bn_id desc';

		// 추출게시판 정리
		list($plus, $minus) = na_bo_list($wset['gr_list'], $wset['gr_except'], $wset['bo_list'], $wset['bo_except']);
		$sql_plus = na_sql_find('a.bo_table', $plus, 0);
		$sql_minus = na_sql_find('a.bo_table', $minus, 1);

		//글, 댓글
		$sql_wr = ($wset['comment']) ? "and a.wr_parent <> a.wr_id" : "and a.wr_parent = a.wr_id";

		// 기간(일수,today,yesterday,month,prev)
		$sql_term = na_sql_term($term, 'a.bn_datetime');
		
		// 공통쿼리
		$sql_common = " from {$g5['board_new_table']} a, {$g5['board_table']} b where a.bo_table = b.bo_table and b.bo_use_search = 1 $sql_plus $sql_minus $sql_wr $sql_term $sql_mb $sql_main $sql_where ";
		if($paging || $page > 1) {
			$total = sql_fetch("select count(*) as cnt $sql_common ", false);
			$total_count = $total['cnt'];
			$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
			if($pmax && $total_page > $pmax) {
				$total_page = $pmax; //최대 페이지 지정
			}
			$start_rows = ($page - 1) * $rows; // 시작 열을 구함
		}
		$result = sql_query(" select a.bo_table, a.wr_id, b.bo_subject $sql_common order by $sql_orderby $orderby limit $start_rows, $rows ", false);
		if($result) {
			for ($i=0; $row=sql_fetch_array($result); $i++) {

				$tmp_write_table = $g5['write_prefix'] . $row['bo_table']; 

				$wr = sql_fetch(" select * from $tmp_write_table where wr_id = '{$row['wr_id']}' ", false);
				
				$wr['bo_table'] = $row['bo_table'];
				$wr['bo_subject'] = $row['bo_subject'];

				$list[$i] = na_wr_row($wr, $wset);
			}
		}
	} else { //단수

		// 메인글
		$sql_main = ($wset['main']) ? "and as_type = '".$wset['main']."'" : "";

		// 회원글
		$sql_mb = na_sql_find('mb_id', $wset['mb_list'], $wset['mb_except']);

		// 정렬
		$orderby = na_sql_sort('bo', $wset['sort']);
		$orderby = ($orderby) ? $orderby : 'wr_id desc';

		// 기간(일수,today,yesterday,month,prev)
		$sql_term = na_sql_term($term, 'wr_datetime');

		// 분류
		$sql_ca = na_sql_find('ca_name', $wset['ca_list'], $wset['ca_except']);

		//글, 댓글
		$sql_wr = ($wset['comment']) ? 1 : 0;

		$tmp_write_table = $g5['write_prefix'] . $bo_table;

		$sql_common = "from $tmp_write_table where wr_is_comment = '{$sql_wr}' $sql_ca $sql_term $sql_mb $sql_main $sql_where";
		if($paging || $page > 1) {
			$total = sql_fetch("select count(*) as cnt $sql_common ", false);
			$total_count = $total['cnt'];
			$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
			if($pmax && $total_page > $pmax) {
				$total_page = $pmax; //최대 페이지 지정
			}
			$start_rows = ($page - 1) * $rows; // 시작 열을 구함
		}
		$result = sql_query(" select * $sql_common order by $sql_orderby $orderby limit $start_rows, $rows ", false);
		if($result) {
			for ($i=0; $row=sql_fetch_array($result); $i++) { 

				$row['bo_table'] = $bo_table;

				$list[$i] = na_wr_row($row, $wset);
			}
		}
	}

	if($paging)
		return array($total_page, $list);

	return $list;
}

// 회원추출
function na_member_rows($wset) {
	global $g5;

	$list = array();

	$rows = isset($wset['rows']) ? (int)$wset['rows'] : 0;
	$rows = ($rows > 0) ? $rows : 7;

	$mode = isset($wset['mode']) ? $wset['mode'] : '';

	$wset['term'] = isset($wset['term']) ? $wset['term'] : '';
	$wset['dayterm'] = isset($wset['dayterm']) ? (int)$wset['dayterm'] : 0;
	$term = ($wset['term'] == 'day' && (int)$wset['dayterm'] > 0) ? $wset['dayterm'] : $wset['term'];

	$wset['mb_list'] = isset($wset['mb_list']) ? $wset['mb_list'] : '';
	$sql_mb = na_sql_find('mb_id', $wset['mb_list'], 1);

	if($mode == 'connect') { // 현재접속회원
		$sql = " select * from {$g5['login_table']} where mb_id <> '' $sql_mb order by lo_datetime desc ";

	} else if($mode == 'post' || $mode == 'comment') { // 글,댓글 등록수
		$sql_term = na_sql_term($term, 'bn_datetime');
		$sql_wr = ($mode == 'comment') ? "and wr_parent <> wr_id" : "and wr_parent = wr_id";
		$sql = " select mb_id, count(mb_id) as cnt from {$g5['board_new_table']} 
					where mb_id <> '' $sql_wr $sql_mb $sql_term group by mb_id order by cnt desc limit 0, $rows ";

	} else if($term && $mode == 'point') { // 포인트(기간설정)
		$sql_term = na_sql_term($term, 'po_datetime');
		$sql = " select mb_id, sum(po_point) as cnt from {$g5['point_table']} 
					where po_point > 0 $sql_mb $sql_term group by mb_id order by cnt desc limit 0, $rows ";

	} else if($term && $mode == 'exp') { // 경험치(기간설정)
		$sql_term = na_sql_term($term, 'xp_datetime');
		$sql = " select mb_id, sum(xp_point) as cnt from {$g5['na_xp']} 
					where 1 $sql_mb $sql_term group by mb_id order by cnt desc limit 0, $rows ";

	} else {
		$field = 'mb_point';
		switch($mode) {
			case 'exp'		: $field = 'as_exp'; $orderby = 'as_exp desc'; break; //경험치
			case 'new'		: $orderby = 'mb_datetime desc'; break; //신규가입
			case 'recent'	: $orderby = 'mb_today_login desc'; break; //최근접속
			default			: $orderby = 'mb_point desc'; break; //포인트(기본값)
		}
		$sql = "select *, $field as cnt from {$g5['member_table']} where mb_leave_date = '' and mb_intercept_date = '' $sql_mb order by $orderby limit 0, $rows ";
	}

	$result = sql_query($sql, false);
	if($result) {
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			$list[$i] = ($row['mb_id'] && $row['mb_nick']) ? $row : get_member($row['mb_id']);
			$list[$i]['cnt'] = $row['cnt'];
			if(!$list[$i]['mb_open']) {
				$list[$i]['mb_email'] = '';
				$list[$i]['mb_homepage'] = '';
			}
			$list[$i]['name'] = get_sideview($list[$i]['mb_id'], $list[$i]['mb_nick'], $list[$i]['mb_email'], $list[$i]['mb_homepage']);
		}
	}

	return $list;
}

// 인기검색어 추출
function na_popular_rows($wset) {
	global $g5;

	$list = array();

	$rows = isset($wset['rows']) ? (int)$wset['rows'] : 0;
	$rows = ($rows > 0) ? $rows : 10;

	$wset['term'] = isset($wset['term']) ? $wset['term'] : '';
	$wset['dayterm'] = isset($wset['dayterm']) ? (int)$wset['dayterm'] : 0;
	$term = ($wset['term'] == 'day' && (int)$wset['dayterm'] > 0) ? $wset['dayterm'] : $wset['term'];
	$sql_term = na_sql_term($term, 'pp_date');

	// 한글이 포함된 검색어만
	$wset['han'] = isset($wset['han']) ? $wset['han'] : '';
	$sql_han = ($wset['han']) ? "and pp_word regexp '[가-힣]'" : '';
	$sql = " select pp_word, count(pp_word) as cnt from {$g5['popular_table']} where (1) $sql_term $sql_han group by pp_word order by cnt desc limit 0, $rows ";
	$result = sql_query($sql, false);
	if($result) {
		for ($i=0; $row=sql_fetch_array($result); $i++) { 
			$list[$i] = $row;
		}
	}

	return $list;
}

// 태그추출
function na_tag_rows($wset) {
	global $g5;

	$list = array();

	$rows = isset($wset['rows']) ? (int)$wset['rows'] : 0;
	$rows = ($rows > 0) ? $rows : 10;

	$wset['new'] = isset($wset['new']) ? (int)$wset['new'] : 0;
	$orderby = ($wset['new'] > 0) ? "lastdate desc," : "";
	$result = sql_query(" select * from {$g5['na_tag']} where cnt > 0 order by $orderby cnt desc, type, idx, tag limit 0, $rows ", false);
	if($result) {
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			$list[$i] = $row;
			$list[$i]['href'] = G5_BBS_URL.'/tag.php?q='.urlencode($row['tag']);
		}
	}

	return $list;
}

// 태그 관련글 추출
function na_tag_post_rows($wset) {
	global $g5;

	$list = array();

	$tag = isset($wset['tag']) ? $wset['tag'] : '';

	if(!$tag)
		return $list;	

	$rows = isset($wset['rows']) ? (int)$wset['rows'] : 0;
	$rows = ($rows > 0) ? $rows : 7;
	$page = isset($wset['page']) ? (int)$wset['page'] : 0;
	$page = ($page > 1) ? $page : 1;


	$wset['term'] = isset($wset['term']) ? $wset['term'] : '';
	$wset['dayterm'] = isset($wset['dayterm']) ? (int)$wset['dayterm'] : 0;
	$term = ($wset['term'] == 'day' && (int)$wset['dayterm'] > 0) ? $wset['dayterm'] : $wset['term'];

	$wset['bo_list'] = isset($wset['bo_list']) ? $wset['bo_list'] : '';
	$wset['bo_except'] = isset($wset['bo_except']) ? $wset['bo_except'] : '';
	$wset['gr_list'] = isset($wset['gr_list']) ? $wset['gr_list'] : '';
	$wset['gr_except'] = isset($wset['gr_except']) ? $wset['gr_except'] : '';
	$wset['mb_list'] = isset($wset['mb_list']) ? $wset['mb_list'] : '';
	$wset['mb_except'] = isset($wset['mb_except']) ? $wset['mb_except'] : '';

	// 회원글
	$sql_mb = na_sql_find('mb_id', $wset['mb_list'], $wset['mb_except']);

	// 추출게시판 정리
	list($plus, $minus) = na_bo_list($wset['gr_list'], $wset['gr_except'], $wset['bo_list'], $wset['bo_except']);
	$sql_plus = na_sql_find('bo_table', $plus, 0);
	$sql_minus = na_sql_find('bo_table', $minus, 1);

	// 기간(일수,today,yesterday,month,prev)
	$sql_term = na_sql_term($term, 'lastdate');

	$start_rows = 0;

	// 공통쿼리
	$sql_common = " from {$g5['na_tag_log']} where bo_table <> '' and find_in_set(tag, '{$tag}') $sql_plus $sql_minus $sql_mb $sql_term group by bo_table, wr_id ";

	if($page > 1) {
		$total = sql_query(" select count(*) as cnt $sql_common ", false);
		$total_count = @sql_num_rows($total);
		$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
		$start_rows = ($page - 1) * $rows; // 시작 열을 구함
	}

	$result = sql_query(" select bo_table, wr_id $sql_common order by regdate desc limit $start_rows, $rows ", false);
	if($result) {
		for ($i=0; $row=sql_fetch_array($result); $i++) {

			$tmp_write_table = $g5['write_prefix'] . $row['bo_table']; 

			$wr = sql_fetch(" select * from $tmp_write_table where wr_id = '{$row['wr_id']}' ", false);
			
			$wr['bo_table'] = $row['bo_table'];

			$list[$i] = na_wr_row($wr, $wset);
		}
	}

	return $list;
}

// FAQ 추출
function na_faq_rows($wset) {
	global $g5;

	$list = array();

	$rows = isset($wset['rows']) ? (int)$wset['rows'] : 0;
	$rows = ($rows > 0) ? $rows : 7;

	$wset['fa_list'] = isset($wset['fa_list']) ? $wset['fa_list'] : '';
	$wset['except'] = isset($wset['except']) ? $wset['except'] : '';

	$sql_fa = na_sql_find('fm_id', $wset['fa_list'], $wset['except']);

	$result = sql_query(" select * from {$g5['faq_table']} where 1 $sql_fa order by fa_order, fa_id limit 0, $rows ", false);
	if($result) {
		for ($i=0; $row=sql_fetch_array($result); $i++) { 
			$list[$i] = $row;
			$list[$i]['subject'] = get_text($row['fa_subject']);
			$list[$i]['content'] = conv_content($row['fa_content'], 1);
			$list[$i]['href'] = G5_BBS_URL.'/faq.php?fm_id='.$row['fm_id'];
		}
	}

	return $list;
}

// 칼럼
function na_row_cols($xs, $sm='', $md='', $lg='', $xl='') {

	$cols = '';
	if($xs)
		$cols .= ' row-cols-'.$xs;
	if($sm)
		$cols .= ' row-cols-sm-'.$sm;
	if($md)
		$cols .= ' row-cols-md-'.$md;
	if($lg)
		$cols .= ' row-cols-lg-'.$lg;
	if($xl)
		$cols .= ' row-cols-xl-'.$xl;

	return $cols;
}

// Owlcarousel 옵션
function na_owl_resp($wset) {

	$resp = array();
	$arr = array();
	$o = array('xs','sm','md','lg','xl');
	$s = array('0','575','767','991','1199');
	for($i=0; $i < 5; $i++) {
		$n = $o[$i];
		if($wset[$n] != '')
			$arr[] = 'items:'.(int)$wset[$n];
		if($wset['m'.$n] != '')
			$arr[] = 'margin:'.(int)$wset['m'.$n];
		if($wset['s'.$n] != '')
			$arr[] = 'stagePadding:'.(int)$wset['s'.$n];
		if(!empty($arr)) {
			$resp[$i] = $s[$i].':{'.implode(',', $arr).'}';
		}
		unset($arr);
	}

	return (!empty($resp)) ? ',responsive:{'.implode(',', $resp).'}' : '';
}

// 주사선
function na_raster($raster) {

	return ($raster) ? NA_URL.'/img/raster-'.$raster.'.png' : '';

}