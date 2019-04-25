<?php

require_once __DIR__ . '/../src/Tweet.php';
require_once __DIR__ . '/../src/User.php';
require_once __DIR__ . '/../src/Comment.php';


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
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $singleTweet = Tweet::loadTweetById($conn, $id);
    $userId = $singleTweet->getUserId();
    $tweetUser = User::loadUserById($conn, $userId);
    $allComments = Comment::loadAllCommentsByPostId($conn, $id);
}

?>
<div>
    <h2>Tweet o id: <?php $singleTweet->getId() ?> </h2>
    <div>
        <?php
        echo '<div>' . $singleTweet->getTweet() . '</div></br><div>Data utworzenia: ' . $singleTweet->getCreationDate() . '<br><div>Przez: <b><a href="index.php?page=allUserTweets&id=' . $singleTweet->getUserId() . '">' . $tweetUser->getUsername() . '</a></b></div><hr>';
        foreach ($allComments as $value => $singleComment) {
            $userCommentId = $singleComment->getUserId();
            echo '<div>Komentarze : <br>
                    <h5><div>Komentarz o id : ' . $singleComment->getId() . '<br></div>
                    <div>' . $singleComment->getText() . '</div><br>
                    Stworzony przez :' . $tweetUser->getUsername() . ' dnia: ' . $singleComment->getCreationDate() . '<hr></h5>';
        }
        echo '<div>
                <form action="" method="post">
                <h6>Dodaj nowy komentarz:</h6>
                <input type="text" name="comment" placeholder = "What you Think about this?">
                <input type="hidden" name="postId" value="' . $id . '">
                <input type="submit" name="send" value="Comment">
                </form>
                </div>';
        ?>
    </div>
</div>