<?php
require_once './config.php';
require_once './dbclasses/friends.php';
require_once './dbclasses/user.php';
require_once './verify.php';

$AJAX_FORM = json_decode(file_get_contents('php://input'), true);

$friendsDAO = new FriendsDAO($config);
$userDAO = new UserDAO($config);
$token = $_SERVER['HTTP_AUTHORIZE'];

if(verifyToken($token, $config)) {
    $tokenInfo = getTokenInfo($token);
    $user = $userDAO->getUser($tokenInfo['email']);
    echo json_encode($friendsDAO->getRecentGroupUsers($user['id']));
} else {
    header('HTTP/1.1 401 Unauthorized');
}
?>