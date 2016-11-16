<?php
require_once './config.php';
require_once './dbclasses/userimages.php';
require_once './dbclasses/user.php';
require_once './verify.php';


$userDAO = new UserDAO($config);
$userImagesDAO = new UserImagesDAO($config);
$token = $_SERVER['HTTP_AUTHORIZE'];

$allow = array("jpg", "jpeg", "gif", "png");

$todir = 'uploads/';

if ( !!$_FILES['file']['tmp_name'] ) // is the file uploaded yet?
{
    $info = explode('.', strtolower( $_FILES['file']['name']) ); // whats the extension of the file

    if ( in_array( end($info), $allow) ) // is this file allowed
    {
        if ( move_uploaded_file( $_FILES['file']['tmp_name'], $todir . basename($_FILES['file']['name'] ) ) )
        {
            if(verifyToken($token, $config)) {
                $tokenInfo = getTokenInfo($token);
                $user = $userDAO->getUser($tokenInfo['email']);
                $userImagesDAO->addDBImage($user['id'], $_FILES['file']['name']);
            } else {
                header('HTTP/1.1 401 Unauthorized');
            }
        }
    }
    else
    {
        header('HTTP/1.1 401 Unauthorized');
    }
}
?>