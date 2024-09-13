<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// input의 name을 boset[배열키] 형태로 등록
?>
<ul class="list-group">
	<li class="list-group-item">
		<div class="form-group row mb-0">
			<label class="col-sm-2 col-form-label">웹진 스타일</label>
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
					<td class="text-center">글내용 길이</td>
					<td class="text-center">
						<div class="input-group">
							<?php $boset['wcut'] = (!isset($boset['wcut']) || $boset['wcut'] == "") ? 80 : (int)$boset['wcut']; ?>
							<input type="text" name="boset[wcut]" value="<?php echo $boset['wcut'] ?>" class="form-control" placeholder="80">
							<div class="input-group-append">
								<span class="input-group-text">자</span>
							</div>
						</div>
					</td>
					<td class="text-muted">기본값 : 80자</td>
					</tr>
					<tr>
					<td class="text-center">썸네일 너비</td>
					<td>
						<div class="input-group">
							<?php $boset['thumb_w'] = (!isset($boset['thumb_w']) || $boset['thumb_w'] == "") ? 400 : (int)$boset['thumb_w']; ?>
							<input type="text" name="boset[thumb_w]" value="<?php echo $boset['thumb_w'] ?>" class="form-control">
							<div class="input-group-append">
								<span class="input-group-text">px</span>
							</div>
						</div>
					</td>
					<td class="text-muted">기본값 : 400 - 0 설정시 썸네일 생성 안 함</td>
					</tr>
					<tr>
					<td class="text-center">썸네일 높이</td>
					<td class="text-center">
						<div class="input-group">
							<?php $boset['thumb_h'] = (!isset($boset['thumb_h']) || $boset['thumb_h'] == "") ? 225 : (int)$boset['thumb_h']; ?>
							<input type="text" name="boset[thumb_h]" value="<?php echo $boset['thumb_h'] ?>" class="form-control">
							<div class="input-group-append">
								<span class="input-group-text">px</span>
							</div>
						</div>
					</td>
					<td class="text-muted">기본값 : 225 - 0 설정시 이미지 비율대로 생성</td>
					</tr>
					<tr>
					<td class="text-center">썸네일 기본 높이</td>
					<td>
						<div class="input-group">
							<?php $boset['thumb_d'] = (!isset($boset['thumb_d']) || $boset['thumb_d'] == "") ? '56.25%' : $boset['thumb_d']; ?>
							<input type="text" name="boset[thumb_d]" value="<?php echo $boset['thumb_d'] ?>" class="form-control">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</td>
					<td class="text-muted">기본값 : 56.25 - 썸네일 높이를 0으로 설정시 적용</td>
					</tr>
					<tr>
					<tr>
					<td class="text-center">XL 이미지 크기</td>
					<td>
						<div class="input-group">
							<input name="boset[wxl]" value="<?php echo isset($boset['wxl']) ? $boset['wxl'] : '4'; ?>" class="form-control">
							<div class="input-group-append">
								<span class="input-group-text">칼럼</span>
							</div>
						</div>
					</td>
					<td class="text-muted">1200px 이상 : Extra large screen / wide desktop</td>
					</tr>
					<tr>
					<td class="text-center">LG 이미지 크기</td>
					<td>
						<div class="input-group">
							<input name="boset[wlg]" value="<?php echo isset($boset['wlg']) ? $boset['wlg'] : ''; ?>" class="form-control">
							<div class="input-group-append">
								<span class="input-group-text">칼럼</span>
							</div>
						</div>
					</td>
					<td class="text-muted">992px 이상 : Large screen / desktop</td>
					</tr>
					<tr>
					<td class="text-center">MD 이미지 크기</td>
					<td>
						<div class="input-group">
							<input name="boset[wmd]" value="<?php echo isset($boset['wmd']) ? $boset['wmd'] : ''; ?>" class="form-control">
							<div class="input-group-append">
								<span class="input-group-text">칼럼</span>
							</div>
						</div>
					</td>
					<td class="text-muted">768px 이상 : Medium screen / tablet</td>
					</tr>
					<tr>
					<td class="text-center">SM 이미지 크기</td>
					<td>
						<div class="input-group">
							<input name="boset[wsm]" value="<?php echo isset($boset['wsm']) ? $boset['wsm'] : '5'; ?>" class="form-control">
							<div class="input-group-append">
								<span class="input-group-text">칼럼</span>
							</div>
						</div>
					</td>
					<td class="text-muted">576px 이상 : Small screen / phone</td>
					</tr>
					<tr>
					<td class="text-center">XS 이미지 크기</td>
					<td class="text-center">
						1단 고정
					</td>
					<td class="text-muted">0px 이상 : Extra small screen / phone</td>
					</tr>
					<tr>
					<td class="text-center">우측 이미지</td>
					<td class="text-center">
						<div class="custom-control custom-checkbox">
							<?php $boset['wimg'] = isset($boset['wimg']) ? $boset['wimg'] : ''; ?>
							<input type="checkbox" name="boset[wimg]" value="1"<?php echo get_checked('1', $boset['wimg'])?> class="custom-control-input" id="idCheck<?php echo $idn ?>">
							<label class="custom-control-label" for="idCheck<?php echo $idn; $idn++; ?>"></label>
						</div>
					</td>
					<td class="text-muted">
						이미지를 글 오른쪽에 출력함
					</td>
					</tr>
					<tr>
					<td class="text-center">No 이미지</td>
					<td colspan="2">
						<div class="input-group">
							<div class="input-group-prepend">
								<a href="<?php echo na_theme_href('image', 'no').'&amp;fid=no_img'; ?>" class="btn btn-primary btn-setup">
									<i class="fa fa-search"></i>
								</a>
							</div>
							<?php $boset['no_img'] = isset($boset['no_img']) ? $boset['no_img'] : ''; ?>
							<input type="text" id="no_img" name="boset[no_img]" value="<?php echo $boset['no_img'] ?>" class="form-control" placeholder="http://...">
						</div>
					</td>
					</tr>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</li>

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
					<td class="text-center">헤드 라인 컬러</td>
					<td class="text-center">
						<select name="boset[head_color]" class="custom-select">
						<option value="">자동 컬러</option>
						<?php 
							$boset['head_color'] = isset($boset['head_color']) ? $boset['head_color'] : '';
							echo na_color_options($boset['head_color']);
						?>
						</select>
					</td>
					<td class="text-muted">
						목록 상단 라인 컬러
					</td>
					</tr>
					<tr>
					<td class="text-center">새글 표시 색상</td>
					<td class="text-center">
						<select name="boset[new]" class="custom-select">
							<option value="">자동 컬러</option>
							<?php 
								$boset['new'] = isset($boset['new']) ? $boset['new'] : '';
								echo na_color_options($boset['new']);
							?>
						</select>
					</td>
					<td class="text-muted">
						&nbsp;
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
