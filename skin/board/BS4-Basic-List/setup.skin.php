<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// input의 name을 boset[배열키] 형태로 등록
?>
<ul class="list-group">
	<li class="list-group-item">
		<div class="form-group row mb-0">
			<label class="col-sm-2 col-form-label">출력 설정</label>
			<div class="col-sm-10">
				<div class="table-responsive">
					<table class="table table-bordered mb-0">
					<tbody>
					<tr class="bg-light">
						<th class="text-center nw-c1">구분</th>
						<th class="text-center nw-c2">설정</th>
						<th class="text-center">비고</th>
					</tr>
					<tr>
					<td class="text-center">목록 헤드 스킨</td>
					<td class="text-center">
						<select name="boset[head_skin]" class="custom-select">
							<option value="">기본 헤드</option>
							<?php
								$wset['head_skin'] = isset($wset['head_skin']) ? $wset['head_skin'] : '';
								$skinlist = na_file_list(NA_PATH.'/skin/head', 'css');
								for ($k=0; $k<count($skinlist); $k++) {
									echo "<option value=\"".$skinlist[$k]."\"".get_selected($boset['head_skin'], $skinlist[$k]).">".$skinlist[$k]."</option>\n";
								} 
							?>
						</select>
					</td>
					<td class="text-muted">
						&nbsp;
					</td>
					</tr>
					<tr>
					<td class="text-center">헤드 라인 컬러</td>
					<td class="text-center">
						<select name="boset[head_color]" class="custom-select">
						<option value="">자동 컬러</option>
						<?php 
							$wset['head_color'] = isset($wset['head_color']) ? $wset['head_color'] : '';
							echo na_color_options($boset['head_color']);
						?>
						</select>
					</td>
					<td class="text-muted">
						기본 헤드 및 상단 라인 컬러
					</td>
					</tr>
					<tr>
					<td class="text-center">검색창 보이기</td>
					<td class="text-center">
						<div class="custom-control custom-checkbox">
							<?php $boset['search_open'] = isset($boset['search_open']) ? $boset['search_open'] : ''; ?>
							<input type="checkbox" name="boset[search_open]" value="1"<?php echo get_checked('1', $boset['search_open'])?> class="custom-control-input" id="idCheck<?php echo $idn ?>">
							<label class="custom-control-label" for="idCheck<?php echo $idn; $idn++; ?>"></label>
						</div>
					</td>
					<td class="text-muted">
						글 목록 상단에 검색창이 보이도록 출력함
					</td>
					</tr>
					<tr>
					<td class="text-center">목록 글 이동</td>
					<td class="text-center">
						<select name="boset[target]" class="custom-select">
							<?php 
								$boset['target'] = isset($boset['target']) ? $boset['target'] : '';
								echo na_target_options($boset['target']);
							?>
						</select>
					</td>
					<td class="text-muted">
						글 내용 또는 관련 링크1 페이지로 이동
					</td>
					</tr>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</li>
</ul>
