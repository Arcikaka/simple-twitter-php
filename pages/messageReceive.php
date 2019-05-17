<?php
require_once __DIR__ . '/../src/Tweet.php';
require_once __DIR__ . '/../src/User.php';
require_once  __DIR__ .'/../src/Comment.php';
require_once  __DIR__ .'/../src/Message.php';
require_once  __DIR__ .'/../functions.php';

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_GET['id'];
    $allMessage = MESSAGE::loadAllMessageSendToUserId($conn,$userId);
}

foreach ($allMessage as $value => $message){
    $userSend = USER::loadUserById($conn,$message->getSendBy());
    echo '<hr><div>Message sent by: ' . $userSend->getUsername() . ' , date: ' . $message->getCreationDate() . '</div><br>
            <div><a href="index.php?page=message&id=' . $message->getId() . '">Click here for a message</a><br>';
}
?>


