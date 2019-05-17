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
    echo '<hr><div>Message sent by: ' . $userSend->getUsername() . ' , date: ' . $message->getCreationDate() . '</div><br>
            <div>' . $message->getMessage() . '</div><br>';
    if($userSession['id'] != $message->getSendBy()) {
        echo '<form action="index.php?page=messageCreate" method="post"><input name="create" type="submit" value="Reply">
                <input type="hidden" name="id" value="'. $userSend->getId() .'"></form>';
    }

    //after user is entering into new Message we change read flag to '1'
    if($userSession['id'] === $message->getSendBy()) {
        $message->setSeen('1');
        $message->saveToDB($conn);
    }
}
