<?php
header('Content-Type: application/json');
if (empty($global['systemRootPath'])) {
    $global['systemRootPath'] = '../';
}
require_once $global['systemRootPath'].'videos/configuration.php';
require_once $global['systemRootPath'] . 'objects/Channel.php';
require_once $global['systemRootPath'] . 'objects/video.php';
require_once $global['systemRootPath'] . 'objects/video_statistic.php';

$previewsMonth = date("Y-m-d 00:00:00", strtotime("-30 days"));
$previewsWeek = date("Y-m-d 00:00:00", strtotime("-7 days"));
$today = date('Y-m-d 23:59:59');

// list all channels
$channels = Channel::getChannels();

$rows = array();
foreach ($channels as $key => $value) {
    // list all videos on that channel
    $videos = Video::getAllVideos("a", $value['id']);
    $identification = User::getNameIdentificationById($value['id']);
    $views = 0;
    foreach ($videos as $key2 => $value2) {
        $views+=VideoStatistic::getStatisticTotalViews($value2['id'], false, $previewsMonth, $today);
    }
    $item = array(
        'views'=>$views,
        'channel'=>"<a href='{$global['webSiteRootURL']}channel/{$value['id']}'>{$identification}</a>"

    );
    $row[] = $item;
}

$obj = new stdClass();

$obj->data = $row;

echo json_encode($obj);

