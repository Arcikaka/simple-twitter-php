<?php

require_once __DIR__ . '/../src/Tweet.php';
require_once __DIR__ . '/../src/User.php';
require_once  __DIR__ .'/../src/Comment.php';
require_once  __DIR__ .'/../src/Message.php';
require_once  __DIR__ .'/../functions.php';

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $messageId = $_GET['id'];
    $message = MESSAGE::loadMessageById($conn,$messageId);

    $userSend = USER::loadUserById($conn,$message->getSendBy());
    echo '<div>Message sent by: ' . $userSend->getUsername() . ' , date: ' . $message->getCreationDate() . '</div><br>
            <div>' . $message->getMessage() . '</div><br>';}
