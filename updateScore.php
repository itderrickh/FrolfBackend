<?php
require_once './config.php';
require_once './dbclasses/score.php';
require_once './verify.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$AJAX_FORM = json_decode(file_get_contents('php://input'), true);

$scoreDAO = new ScoreDAO($config);
$score = $AJAX_FORM["score"];
$scoreId = $AJAX_FORM["scoreId"];
$token = $_SERVER['HTTP_AUTHORIZE'];

if(verifyToken($token, $config)) {
    $scoreDAO->updateScore($score, $scoreId);
} else {
    header('HTTP/1.1 401 Unauthorized');
}
?>