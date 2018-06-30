<?php
require_once '../../videos/configuration.php';
require_once $global['systemRootPath'] . 'objects/plugin.php';
require_once $global['systemRootPath'] . 'plugin/CloneSite/CloneSite.php';
session_write_close();
header('Content-Type: application/json');

$resp = new stdClass();
$resp->error = true;
$resp->msg = "";

if(!User::isAdmin()){
    $resp->msg = "You cant do this";
    die(json_encode($resp));
}

$obj = YouPHPTubePlugin::getObjectDataIfEnabled("CloneSite");
if(empty($obj->cloneSiteURL)){
    $resp->msg = "Your Clone Site URL is empty, please click on the Edit parameters buttons and place an YouPHPTube URL";
    die(json_encode($resp));
}

$clonesDir = $global['systemRootPath']."videos/cache/clones/";

if (!file_exists($clonesDir)) {
    mkdir($clonesDir, 0777, true);
    file_put_contents($clonesDir."index.html", '');
}

// check if it respond
$content = url_get_contents($obj->cloneSiteURL."plugin/CloneSite/cloneIt.php?url=".urlencode($global['webSiteRootURL'])."&key={$obj->myKey}");
//var_dump($content);
$json = json_decode($content);

// get dump file
$cmd = "wget -O {$clonesDir}{$json->sqlFile} {$obj->cloneSiteURL}videos/cache/clones/{$json->sqlFile}";
exec($cmd);

// restore dump
$cmd = "mysql -u {$mysqlUser} -p{$mysqlPass} --host {$mysqlHost} {$mysqlDatabase} youPHPTube < {$clonesDir}{$json->sqlFile}";
exec($cmd);

// get files
$cmd = "wget -O {$clonesDir}{$json->videosFile} {$obj->cloneSiteURL}videos/cache/clones/{$json->videosFile}";
exec($cmd);

// overwrite filesfiles
$cmd = "tar -xf {$clonesDir}{$json->videosFile} -C {$global['systemRootPath']}videos/";
exec($cmd);

// remove sql 

//remove tar


// restore clone plugin configuration
$plugin = new CloneSite();
$p = new Plugin(0);
$p->loadFromUUID($plugin->getUUID());
$p->setObject_data(json_encode($obj, JSON_UNESCAPED_UNICODE ));
$p->save();

echo json_encode($json);