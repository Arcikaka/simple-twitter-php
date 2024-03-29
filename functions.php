<?php

function isLogged()
{
    return isset($_SESSION['isLogged'], $_SESSION['user']) &&
        $_SESSION['isLogged'] === true;
}

function checkLoginOrRedirect()
{
    //we check if user is logged and we redirect him
    if (!isLogged()) {
        //redirect
        ob_end_clean();//buffer cleaning
        header('Location: index.php?page=login');
        exit;
    }
}

function addComment()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $text = trim($_POST['comment']);
        if (strlen($text) < 160) {
            $userId = $userSession['id'];
            $tweetId = $_POST['tweetId'];
            if(is_null(Tweet::loadTweetById($tweetId))){
                throw new Exception('Tweet doesnt exist');
            }
            try {
                $newComment = new Comment();
                $newComment->setCreationDate();
                $newComment->setText($text);
                $newComment->setUserId($userId);
                $newComment->setPostId($tweetId);
                $newComment->saveToDB($conn);
            }catch(Exception $e){
                echo $e->getMessage();
            }

        }
        $singleTweet = Tweet::loadTweetById($conn, $postId);
        $userId = $singleTweet->getUserId();
        $tweetUser = User::loadUserById($conn, $userId);
        $allComments = Comment::loadAllCommentsByPostId($conn, $postId);
    }
}