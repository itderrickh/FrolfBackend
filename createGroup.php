<?php
require_once './config.php';
require_once './dbclasses/group.php';

$groupDAO = new GroupDAO($config);
$groupName = $_POST["groupName"];

$groupDAO->createGroup($groupName);
?>