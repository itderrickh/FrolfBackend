<?php
require_once './config.php';
require_once './dbclasses/group.php';
require_once './verify.php';

$groupDAO = new GroupDAO($config);
$groupName = $_POST["groupName"];
$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];
$token = $_SERVER['HTTP_AUTHORIZE'];

if(verifyToken($token)) {
    $groupDAO->createGroup($groupName, $latitude, $longitude);
} else {
    header('HTTP/1.1 401 Unauthorized');
}
?>