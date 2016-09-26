<?php
require_once './config.php';
require_once './dbclasses/usergroup.php';
require_once './verify.php';

$userGroupDAO = new UserGroupDAO($config);
$groupId = $_POST["groupId"];
$userId = $_POST["userId"];
$token = $_SERVER['HTTP_AUTHORIZE'];

if(verifyToken($token)) {
    $userGroupDAO->addUserToGroup($groupId, $userId);
} else {
    header('HTTP/1.1 401 Unauthorized');
}
?>