<?php
include_once('./_common.php');

// 클립모달
$is_clip_modal = false;

// 클립보드
$is_clip = (isset($clip) && $clip) ? true : false;

// 지도 초기값
$lat = isset($lat) ? $lat : '37.566535';
$lng = isset($lng) ? $lng : '126.977969';
$zoom = isset($zoom) ? $zoom : 16; // 구글맵
$marker = isset($marker) ? $marker : '';

$g5['title'] = '지도';
include_once(G5_THEME_PATH.'/head.sub.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.NA_URL.'/css/modal.css">', 0);

// 모달 내 모달
$is_modal_win = true;

// Loader
if(is_file(G5_THEME_PATH.'/_loader.php')) {
	include_once(G5_THEME_PATH.'/_loader.php');
} else {
	include_once(NA_PATH.'/theme/loader.php');
}

?>

<?php if($is_clip) { ?>
<!-- 클립보드 복사 시작 { -->
<script src="<?php echo NA_URL ?>/js/clipboard.min.js"></script>
<div class="modal fade" id="clipModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h5 class="modal-title text-white"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> <b>Clipboard</b></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span class="text-white" aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<textarea type="text" id="txtClip" class="form-control" rows="5"></textarea>
				<div class="text-center pt-3">
					<button type="button" class="btn btn-primary btn-lg btn-clip en" data-clipboard-target="#txtClip">
						Copy Code
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 클립보드 복사 끝 { -->
<?php } ?>

<div id="topNav" class="bg-primary text-white">
	<div class="p-3">
		<button type="button" class="close clip-close" aria-label="Close">
			<span aria-hidden="true" class="text-white">&times;</span>
		</button>
		<h5><i class="fa fa-map-marker" aria-hidden="true"></i>	MAP</h5>
	</div>
</div>

<div id="topHeight"></div>

<?php 
if(isset($nariya['kakaomap_key']) && $nariya['kakaomap_key']) { 
    add_javascript(G5_POSTCODE_JS, 0); //다음 주소 js
?>

	<form id="fmap" name="fmap">
		<table class="table f-de mb-0">
		<tbody>
		<th class="text-center">주소</th>
		<td>
			<div class="input-group">
				<input type="text" name="address" value="" id="address" class="form-control" placeholder="Search for..." readonly>
				<div class="input-group-append">
					<button type="button" class="btn btn-primary" id="btn_addr">
						<i class="fa fa-search" aria-hidden="true"></i>
					</button>
				</div>
			</div>
			<input type="hidden" id="map_lat" value="<?php echo $lat ?>">
			<input type="hidden" id="map_lng" value="<?php echo $lng ?>">
			<input type="hidden" id="map_zoom" value="<?php echo $zoom ?>">
		</td>
		</tr>
		<tr>
			<th class="text-center">마커</th>
			<td>
				<div class="input-group">
					<input type="text" id="map_marker" class="form-control" value="<?php echo $marker; ?>">
					<div class="input-group-append">
						<button type="button" class="btn btn-primary" onclick="map_submit()">
							<i class="fa fa-code fa-fw" aria-hidden="true"></i>
							코드 생성
						</button>
					</div>
				</div>
			</td>
		</tr>
		</tbody>
		</table>
	</form>

	<div class="na-mapwrap mb-0">
		<div id="map" class="na-map">
			<div id="map_canvas" class="na-canvas"></div>
		</div>
	</div>

	<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?php echo $nariya['kakaomap_key'] ?>&libraries=services"></script>

	<script>
	var mapContainer = document.getElementById('map_canvas'), // 지도를 표시할 div 
		mapOption = {
			center: new daum.maps.LatLng(<?php echo $lat ?>, <?php echo $lng ?>), // 지도의 중심좌표
			level: 3 // 지도의 확대 레벨
		};

	// 지도를 생성
	var map = new daum.maps.Map(mapContainer, mapOption);

	// 주소-좌표 변환 객체 생성
	var geocoder = new daum.maps.services.Geocoder();

	// 마커
	var marker = new daum.maps.Marker({
		map: map,
		// 지도 중심좌표에 마커를 생성
		position: map.getCenter()
	});

	// 클릭한 위치에 마커 표시하기
	kakao.maps.event.addListener(map, 'click', function(mouseEvent) {        
		
		// 클릭한 위도, 경도 정보를 가져옵니다 
		var latlng = mouseEvent.latLng; 

		// 좌표값을 넣어준다.
		document.getElementById('map_lat').value = latlng.getLat();
		document.getElementById('map_lng').value = latlng.getLng();

		// 마커 위치를 클릭한 위치로 옮깁니다
		marker.setPosition(latlng);

	});

	// 주소검색 API (주소 > 좌표변환처리)
	$(function() {
		$("#address, #btn_addr").on("click", function() {
			new daum.Postcode({
				oncomplete: function(data) {
					// console.log(data);
					$("#address").val(data.address);
					geocoder.addressSearch(data.address, function(results, status) {
						// 정상적으로 검색이 완료됐으면
						if (status === daum.maps.services.Status.OK) {

							// 첫번째 결과의 값을 활용
							var result = results[0];

							// 해당 주소에 대한 좌표를 받아서
							var latlng = new daum.maps.LatLng(result.y, result.x);

							// 지도를 보여준다.
							map.relayout();

							// 지도 중심을 변경한다.
							map.setCenter(latlng);

							// 좌표값을 넣어준다.
							document.getElementById('map_lat').value = latlng.getLat();
							document.getElementById('map_lng').value = latlng.getLng();

							// 마커를 결과값으로 받은 위치로 옮긴다.
							marker.setPosition(latlng);

						} else if (status === daum.maps.services.Status.ZERO_RESULT) {
							alert('찾으신 주소에 대한 좌표가 존재하지 않습니다.');
							return;
						} else if (status === daum.maps.services.Status.ERROR) {
							alert('오류가 발생하였습니다.');
							return;
						}
					});
				}
			}).open();
		});
	});
		
	// 마커를 기준으로 가운데 정렬이 될 수 있도록 추가
	var markerPosition = marker.getPosition(); 
	map.relayout();
	map.setCenter(markerPosition);

	$(window).on('resize', function () {
		var markerPosition = marker.getPosition(); 
		map.relayout();
		map.setCenter(markerPosition)
	});
	</script>
<?php } else { ?>
	<style>
	div#map { 
		position: relative; 
		overflow:hidden; }
	div#crosshair {
		position: absolute;
		top: 50%;
		height: 50px;
		width: 50px;
		left: 50%;
		margin-left: -25px;
		margin-top:-50px;
		display: block;
		background-image: url('<?php echo NA_URL ?>/img/map-icon.png');
		background-position: center center;
		background-repeat: no-repeat;
	}
	</style>

	<script src="https://maps.google.com/maps/api/js?v=3.exp&language=ko&region=kr&key=<?php echo $nariya['google_key'] ?>"></script>
	<script>
		var map;
		var geocoder;
		var centerChangedLast;
		var reverseGeocodedLast;
		var currentReverseGeocodeResponse;

		function addLoadEvent(func) {
			var oldonload = window.onload;
			if (typeof window.onload != 'function') {
				window.onload = func;
			} else {
				window.onload = function() {
					if (oldonload) {
						oldonload();
					}
					func();
				}
			}
		}

		function initialize() {
			var latlng = new google.maps.LatLng(<?php echo $lat;?>, <?php echo $lng;?>);
			var myOptions = {
				zoom: <?php echo $zoom;?>,
				scaleControl: true,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};

			map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
			geocoder = new google.maps.Geocoder();

			google.maps.event.addListener(map, 'zoom_changed', function() {
				document.getElementById("zoom_level").innerHTML = map.getZoom();
				document.getElementById("map_zoom").value = map.getZoom();

				zoomLevel = map.getZoom(); 
				if (zoomLevel > 19) { 
					map.setZoom(19); 
				} else if (zoomLevel < 1) { 
					map.setZoom(1); 
				}
			});

			setupEvents();
			centerChanged();
		}

		function setupEvents() {
			reverseGeocodedLast = new Date();
			centerChangedLast = new Date();

			setInterval(function() {
				if((new Date()).getSeconds() - centerChangedLast.getSeconds() > 1) {
					if(reverseGeocodedLast.getTime() < centerChangedLast.getTime())
					reverseGeocode();
				}
			}, 1000);

			google.maps.event.addListener(map, 'center_changed', centerChanged);

			google.maps.event.addDomListener(document.getElementById('crosshair'),'dblclick', function() {
				map.setZoom(map.getZoom() + 1);
			});
		}

		function getCenterLatLngText() {

			var nn = 1000000;
			var tmpLat = Math.round(map.getCenter().lat()*nn)/nn;
			var tmpLng = Math.round(map.getCenter().lng()*nn)/nn;

			document.getElementById("map_lat").value = tmpLat;
			document.getElementById("map_lng").value = tmpLng;

			return tmpLat +', '+ tmpLng;
		}

		function centerChanged() {
			centerChangedLast = new Date();
			var latlng = getCenterLatLngText();
			var loc = latlng.split(',');	
			geocoder.geocode({latLng:map.getCenter()},reverseGeocodeResult);
			document.getElementById('lat').innerHTML = loc[0];
			document.getElementById('lng').innerHTML = loc[1];
			document.getElementById('formatedAddress').innerHTML = '';
			currentReverseGeocodeResponse = null;
		}

		function reverseGeocode() {
			reverseGeocodedLast = new Date();
			geocoder.geocode({latLng:map.getCenter()},reverseGeocodeResult);
		}

		function reverseGeocodeResult(results, status) {
			currentReverseGeocodeResponse = results;
			if(status == 'OK') {
				if(results.length == 0) {
					document.getElementById('formatedAddress').innerHTML = '';
				} else {
					document.getElementById('formatedAddress').innerHTML = results[0].formatted_address;
				}
			} else {
				document.getElementById('formatedAddress').innerHTML = '';
			}
		}

		function geocode() {
			var address = document.getElementById("address").value;
			geocoder.geocode({'address': address}, geocodeResult);
		}

		function geocodeResult(results, status) {
			if (status == 'OK' && results.length > 0) {
				map.fitBounds(results[0].geometry.viewport);
			} else {
				alert("Info : " + status);
			}
		}
	</script>

	<form id="fmap" name="fmap">
		<table class="table f-de mb-0">
		<tbody>
		<tr style="display:none;">
			<th class="nw-4 text-center">위치</th>
			<td>
				<p class="form-control-plaintext f-de pt-0">
					<span id="formatedAddress">서울특별시청</span>
					(<span id="lat"></span>, <span id="lng"></span>, Zoom <span id="zoom_level"><?php echo $zoom; ?></span>)
				</p>
			</td>
		</tr>
		<tr>
		<th class="text-center">장소</th>
		<td> 
			<div class="input-group">
				<input type="text" id="address" class="form-control" placeholder="Search for..." onKeyDown="if(event.keyCode==13){geocode();}">
				<div class="input-group-append">
					<button type="button" class="btn btn-primary" onclick="geocode()">
						<i class="fa fa-search"></i>
						<span class="sr-only">검색하기</span>
					</button>
				</div>
			</div>
			<input type="hidden" id="map_lat" value="<?php echo $lat; ?>">
			<input type="hidden" id="map_lng" value="<?php echo $lng; ?>">
			<input type="hidden" id="map_zoom" value="<?php echo $zoom;?>">
		</td>
		</tr>
		<tr>
			<th class="text-center">마커</th>
			<td>
				<div class="input-group">
					<input type="text" id="map_marker" class="form-control" value="<?php echo $marker; ?>">
					<div class="input-group-append">
						<button type="button" class="btn btn-primary" onclick="map_submit()">
							<i class="fa fa-code" aria-hidden="true"></i>
							코드 생성
						</button>
					</div>
				</div>
			</td>
		</tr>
		</tbody>
		</table>
	</form>

	<div class="na-mapwrap mb-0">
		<div id="map" class="na-map">
			<div id="map_canvas" class="na-canvas"></div>
			<div id="crosshair"></div>
		</div>
	</div>

	<script>
	addLoadEvent(function() {
		initialize();
	});
	</script>
<?php } ?>

<script>
function map_submit() {
	var code_lat = document.getElementById("map_lat").value;
	var code_lng = document.getElementById("map_lng").value;
	var code_zoom = document.getElementById("map_zoom").value;
	var code_marker = document.getElementById("map_marker").value;
	var code_place = document.getElementById("address").value;

	var code_geo = " geo=\"" + code_lat + "," + code_lng + "," + code_zoom + "\"";

	if(code_marker) 
		code_marker = " m=\"" + code_marker + "\"";

	if(code_place) 
		code_place = " p=\"" + code_place + "\"";

	var map_code = "{map: " + code_geo + code_marker + code_place + " }";

	<?php if($is_clip) { ?>
		$("#txtClip").val(map_code);
		$('#clipModal').modal('show');
	<?php } else { ?>
		parent.document.getElementById("wr_content").value += map_code;
		window.parent.closeClipModal();
	<?php } ?>
}

function map_size() {
	var w = $("#clipModalSize", parent.document).width();
	var h = $("#clipModalSize", parent.document).height() - $("#fmap").height() - $("#topNav").height();
	var s = (h/w) * 100;

	$('#map').css('padding-bottom', s + '%');
}

$(window).on('load', function () {
	na_nav('topNav', 'topHeight', 'fixed-top');
});

$(document).ready(function() {
	<?php if($is_clip) { ?>
		var clipboard = new ClipboardJS('.btn-clip');
		clipboard.on('success', function(e) {
			alert("복사가 되었으니 Ctrl + V 를 눌러 붙여넣기해 주세요.");
			$('#clipModal').modal('hide');
			window.parent.closeClipModal();
		});
		clipboard.on('error', function(e) {
			alert("복사가 안되었으니 Ctrl + C 를 눌러 복사해 주세요.");
		});
	<?php } ?>
	$('.clip-close').click(function() {
		window.parent.closeClipModal();
	});

	// 지도크기
	map_size();

	$(window).resize(function(e) {
		map_size();
	});
});
</script>
<?php 
include_once(G5_THEME_PATH.'/tail.sub.php');