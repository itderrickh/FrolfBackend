<?php
require_once './config.php';
require_once './dbclasses/friend.php';
require_once './verify.php';

$AJAX_FORM = json_decode(file_get_contents('php://input'), true);

$friendsDAO = new FriendsDAO($config);
$friendId = $AJAX_FORM["friendId"];
$token = $_SERVER['HTTP_AUTHORIZE'];

if(verifyToken($token, $config)) {
    $tokenInfo = getTokenInfo($token);
    $user = $userDAO->getUser($tokenInfo['email']);
    $friendsDAO->addFriend($user['id'], $friendId);
} else {
    header('HTTP/1.1 401 Unauthorized');
}
?>