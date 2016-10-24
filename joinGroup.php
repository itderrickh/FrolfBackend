<?php
require_once './config.php';
require_once './dbclasses/group.php';
require_once './dbclasses/user.php';
require_once './verify.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$AJAX_FORM = json_decode(file_get_contents('php://input'), true);

$groupDAO = new GroupDAO($config);
$userDAO = new UserDAO($config);
$groupId = $AJAX_FORM["groupId"];
$token = $_SERVER['HTTP_AUTHORIZE'];

if(verifyToken($token, $config)) {
    $tokenInfo = getTokenInfo($token);
    $user = $userDAO->getUser($tokenInfo['email']);
    if(!$groupDAO->isInGroup($groupId, $user['id'])) {
        $groupDAO->joinGroup($groupId, $user['id']);
    }
} else {
    header('HTTP/1.1 401 Unauthorized');
}
?>