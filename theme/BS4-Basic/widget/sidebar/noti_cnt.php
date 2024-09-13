<?php
include_once('./_common.php');

$member['as_noti'] = isset($member['as_noti']) ? $member['as_noti'] : 0;
$member['mb_memo_cnt'] = isset($member['mb_memo_cnt']) ? $member['mb_memo_cnt'] : 0;

$noti_cnt = $member['as_noti'] + $member['mb_memo_cnt'];

echo '{ "count": "' . $noti_cnt . '" }';
exit;