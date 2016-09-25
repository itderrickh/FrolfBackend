<?php
require_once './config.php';
require_once './dbclasses/usergroup.php';

$userGroupDAO = new UserGroupDAO($config);
$groupId = $_POST["groupId"];
$userId = $_POST["userId"];

$userGroupDAO->addUserToGroup($groupId, $userId);
?>