<?php
require_once './config.php';
require_once './dbclasses/group.php';
require_once './verify.php';

$groupDAO = new GroupDAO($config);
$groupName = $_POST["groupName"];
$token = $_SERVER['HTTP_AUTHORIZE'];

if(verifyToken($token)) {
    $groupDAO->createGroup($groupName);
} else {
    header('HTTP/1.1 401 Unauthorized');
}
?>