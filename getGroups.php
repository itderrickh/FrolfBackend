<?php
require_once './config.php';
require_once './dbclasses/group.php';
require_once './verify.php';

$AJAX_FORM = json_decode(file_get_contents('php://input'), true);

$groupDAO = new GroupDAO($config);
$token = $_SERVER['HTTP_AUTHORIZE'];

if(verifyToken($token, $config)) {
    $result = $groupDAO->getAvailableGroups();
    echo json_encode($result);
} else {
    header('HTTP/1.1 401 Unauthorized');
}
?>