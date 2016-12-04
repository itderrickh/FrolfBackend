<?php
require_once './config.php';
require_once './dbclasses/score.php';
require_once './verify.php';

$AJAX_FORM = json_decode(file_get_contents('php://input'), true);

$scoreDAO = new ScoreDAO($config);
$token = $_SERVER['HTTP_AUTHORIZE'];

$userId = $AJAX_FORM['userId'];

if(verifyToken($token, $config)) {
    $stats = $scoreDAO->getStatistics($userId);
    echo json_encode($stats);
} else {
    header('HTTP/1.1 401 Unauthorized');
}
?>