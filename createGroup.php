<?php
require_once './config.php';
require_once './dbclasses/group.php';
require_once './verify.php';

$AJAX_FORM = json_decode(file_get_contents('php://input'), true);

$groupDAO = new GroupDAO($config);
$groupName = $AJAX_FORM["groupName"];
$latitude = $AJAX_FORM["latitude"];
$longitude = $AJAX_FORM["longitude"];
$token = $_SERVER['HTTP_AUTHORIZE'];

if(verifyToken($token)) {
    $groupDAO->createGroup($groupName, $latitude, $longitude);
} else {
    header('HTTP/1.1 401 Unauthorized');
}
?>