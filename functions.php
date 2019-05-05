<?php

function isLogged()
{
    return isset($_SESSION['isLogged'], $_SESSION['user']) &&
        $_SESSION['isLogged'] === true;
}

function checkLoginOrRedirect()
{
    //sprawdzamy fakt zalogowania użytkownika i przekerowujemy
    if (!isLogged()) {
        //przekierowujemy
        ob_end_clean();//czyszczenie bufor
        header('Location: index.php?page=login');
        exit;
    }
}

function addComment()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $text = trim($_POST['comment']);
        if ($text < 160) {
            $userId = $userSession['id'];
            $postId = $_POST['postId'];
            $newComment = new Comment();
            $newComment->setCreationDate();
            $newComment->setText($text);
            $newComment->setUserId($userId);
            $newComment->setPostId($postId);
            $newComment->saveToDB($conn);

        }
        $singleTweet = Tweet::loadTweetById($conn, $postId);
        $userId = $singleTweet->getUserId();
        $tweetUser = User::loadUserById($conn, $userId);
        $allComments = Comment::loadAllCommentsByPostId($conn, $postId);
    }
}