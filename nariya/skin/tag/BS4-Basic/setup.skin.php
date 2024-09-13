<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// input의 name을 boset[배열키] 형태로 등록
?>
<ul class="list-group">
	<li class="list-group-item">
		<div class="form-group row mb-0">
			<label class="col-sm-2 col-form-label">목록 헤드</label>
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
					<td class="text-center">목록 라인 컬러</td>
					<td class="text-center">
						<select name="wset[head_color]" class="custom-select">
						<option value="">자동 컬러</option>
						<?php echo na_color_options($wset['head_color']);?>
						</select>
					</td>
					<td class="text-muted">
						목록 상단 라인 컬러
					</td>
					</tr>
					<tr>
					<td class="text-center">검색창 보이기</td>
					<td class="text-center">
						<div class="custom-control custom-checkbox">
							<?php $wset['search_open'] = isset($wset['search_open']) ? $wset['search_open'] : ''; ?>
							<input type="checkbox" name="wset[search_open]" value="1"<?php echo get_checked('1', $wset['search_open'])?> class="custom-control-input" id="idCheck<?php echo $idn ?>">
							<label class="custom-control-label" for="idCheck<?php echo $idn; $idn++; ?>"></label>
						</div>
					</td>
					<td class="text-muted">
						목록 상단에 검색창이 보이도록 출력함
					</td>
					</tr>
					<tr>
					<td class="text-center">태그 목록</td>
					<td>
						<div class="input-group">
							<?php $wset['rows'] = isset($wset['rows']) ? $wset['rows'] : ''; ?>
							<input type="text" name="wset[rows]" value="<?php echo $wset['rows'] ?>" class="form-control">
							<div class="input-group-append">
								<span class="input-group-text">개</span>
							</div>
						</div>
					</td>
					<td class="text-muted">
						최신, 색인 페이지 당 태그 출력수 : 기본값 50
					</td>
					</tr>
					<tr>
					<td class="text-center">인기 순위</td>
					<td>
						<div class="input-group">
							<?php $wset['rank'] = isset($wset['rank']) ? $wset['rank'] : ''; ?>
							<input type="text" name="wset[rank]" value="<?php echo $wset['rank'] ?>" class="form-control">
							<div class="input-group-append">
								<span class="input-group-text">개</span>
							</div>
						</div>
					</td>
					<td class="text-muted">
						인기 페이지의 태그 순위 묶음 : 기본값 10
					</td>
					</tr>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</li>
</ul>
