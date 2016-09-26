<?php
require_once './config.php';
require_once './dbclasses/usergroup.php';
require_once './verify.php';

$AJAX_FORM = json_decode(file_get_contents('php://input'), true);

$userGroupDAO = new UserGroupDAO($config);
$groupId = $AJAX_FORM["groupId"];
$userId = $AJAX_FORM["userId"];
$token = $_SERVER['HTTP_AUTHORIZE'];

if(verifyToken($token, $config)) {
    $userGroupDAO->addUserToGroup($groupId, $userId);
} else {
    header('HTTP/1.1 401 Unauthorized');
}
?>