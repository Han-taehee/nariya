<?php
include_once('./_common.php');

if($is_guest) {
	alert_close('회원만 이용하실 수 있습니다.');
}

include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// Upload Member Photo
function na_myphoto_upload($mb_id, $del_photo, $file) {
	global $g5, $config;

	if(!$mb_id) 
		return;

	$photo_w = (isset($config['cf_member_img_width']) && $config['cf_member_img_width']) ? $config['cf_member_img_width'] : 80;
	$photo_h = (isset($config['cf_member_img_height']) && $config['cf_member_img_height']) ? $config['cf_member_img_height'] : 80;

	$photo_dir = G5_DATA_PATH.'/member_image/'.substr($mb_id,0,2);
	$temp_dir = G5_DATA_PATH.'/member_image/temp';

	// Delete Photo
	if ($del_photo == "1") {
		@unlink($photo_dir.'/'.$mb_id.'.gif');
	}
    
	// Upload Photo
	if (is_uploaded_file($file['mb_icon2']['tmp_name'])) {
		if (!preg_match("/(\.(gif|jpe?g|bmp|png))$/i", $file['mb_icon2']['name'])) {
			alert('GIF/JPG/PNG 이미지 파일만 가능합니다.');
		} else {
			if(!is_dir(G5_DATA_PATH.'/member_image')) {
				@mkdir(G5_DATA_PATH.'/member_image', G5_DIR_PERMISSION);
				@chmod(G5_DATA_PATH.'/member_image', G5_DIR_PERMISSION);
			}
			if(!is_dir($photo_dir)) {
				@mkdir($photo_dir, G5_DIR_PERMISSION);
				@chmod($photo_dir, G5_DIR_PERMISSION);
			}

			if(!is_dir($temp_dir)) {
				@mkdir($temp_dir, G5_DIR_PERMISSION);
				@chmod($temp_dir, G5_DIR_PERMISSION);
			}

			$filename  = $file['mb_icon2']['name'];
			$filename  = preg_replace('/(<|>|=)/', '', $filename);
			$filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

			$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
			shuffle($chars_array);
			$shuffle = implode('', $chars_array);
	        $filename = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

			$org_photo = $photo_dir.'/'.$mb_id.'.gif';
			$temp_photo = $temp_dir.'/'.$filename;

			move_uploaded_file($file['mb_icon2']['tmp_name'], $temp_photo) or die($file['mb_icon2']['error']);
			chmod($temp_photo, G5_FILE_PERMISSION);
			if(is_file($temp_photo)) {
			    $size = @getimagesize($temp_photo);

				//Non Image
				if (!$size[0]) {
					@unlink($temp_photo);
					alert('회원사진 등록에 실패했습니다. 이미지 파일이 정상적으로 업로드 되지 않았거나, 이미지 파일이 아닙니다.');
				}			

				//Animated GIF
	            $is_animated = false;
	            if($size[2] == 1) {
	                $is_animated = is_animated_gif($temp_photo);
		        }

				if($is_animated) {
					@unlink($temp_photo);
					alert('움직이는 GIF 파일은 회원사진으로 등록할 수 없습니다.');
				} else {
					$thumb = thumbnail($filename, $temp_dir, $temp_dir, $photo_w, $photo_h, true, true);
					if($thumb) {
						if ($size[2] == 2) { //jpg
							$src = @imagecreatefromjpeg($temp_dir.'/'.$thumb);
							@imagegif($src, $temp_dir.'/'.$thumb);
						} else if ($size[2] == 3) { //png
							$src = @imagecreatefrompng($temp_dir.'/'.$thumb);
							@imagealphablending($src, true);
							@imagegif($src, $temp_dir.'/'.$thumb);
						}
						chmod($temp_dir.'/'.$thumb, G5_FILE_PERMISSION);
						copy($temp_dir.'/'.$thumb, $org_photo);
						chmod($org_photo, G5_FILE_PERMISSION);
						@unlink($temp_dir.'/'.$thumb);
						@unlink($temp_photo);
					} else {
						@unlink($temp_photo);
						alert('회원사진 등록에 실패했습니다. 이미지 파일이 정상적으로 업로드 되지 않았거나, 이미지 파일이 아닙니다.');
					}
				}
			}
		}
	}
}

// 설정 저장-------------------------------------------------------
$mode = isset($mode) ? $mode : '';
if ($mode == "u") {
	na_myphoto_upload($member['mb_id'], $del_mb_icon2, $_FILES); //Save
	goto_url(G5_BBS_URL.'/myphoto.php');
}
//--------------------------------------------------------------------

$g5['title'] = '내 사진 관리';
include_once(G5_PATH.'/head.sub.php');

$mb_dir = substr($member['mb_id'],0,2);

$is_photo = (is_file(G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.$member['mb_id'].'.gif')) ? true : false;

$photo_width = (isset($config['cf_member_img_width']) && $config['cf_member_img_width']) ? $config['cf_member_img_width'] : 80;
$photo_height = (isset($config['cf_member_img_height']) && $config['cf_member_img_height']) ? $config['cf_member_img_height'] : 80;

?>

<style>
	body { padding:0; margin:0; background:#fff; }
	.myphoto img { width:<?php echo $photo_width ?>px; height:<?php echo $photo_height ?>px; }
</style>

<section class="p-5">
	<form name="fphotoform" class="form" role="form" method="post" enctype="multipart/form-data" autocomplete="off">
		<input type="hidden" name="mode" value="u">
		
		<h3 class="text-center">My Photo</h3>
		
		<p class="myphoto my-4  text-center">
			<img src="<?php echo na_member_photo($member['mb_id']) ?>?nocache=<?php echo time() ?>" class="rounded-circle">
		</p>
		<p class="my-3 f-de">
			회원사진은 GIF/JPG/PNG 이미지 파일만 가능하며, 등록시 <?php echo $photo_width ?>x<?php echo $photo_height ?> 사이즈로 자동 리사이즈됩니다.
		</p>
		
		<input type=file name="mb_icon2">

		<?php if ($is_photo) { ?>
			<p class="mt-2 f-de">
				<label><input type="checkbox" name="del_mb_icon2" value="1"> 삭제하기</label>
			</p>
		<?php } ?>

		<p class="mt-5 text-center">
			<button type="button" class="btn btn-basic" onclick="window.close();">닫기</button>
			<button type="submit" class="btn btn-primary">확인</button>
		</p>		
	</form>
</section>

<?php
include_once(G5_PATH.'/tail.sub.php');