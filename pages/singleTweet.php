<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/../functions.php';

addComment();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $singleTweet = Tweet::loadTweetById($conn, $id);
    $userId = $singleTweet->getUserId();
    $tweetUser = User::loadUserById($conn, $userId);
    $allComments = Comment::loadAllCommentsByPostId($conn, $id);
}

?>
<div>
    <h2>Tweet id: <?php $singleTweet->getId() ?> </h2>
    <div>
        <?php
        echo '<div>' . $singleTweet->getTweet() . '</div></br><div>Creation date ' . $singleTweet->getCreationDate() . '<br><div>By: <b>
            <a href="index.php?page=allUserTweets&id=' . $singleTweet->getUserId() . '">' . $tweetUser->getUsername() . '</a></b></div><hr>';
        foreach ($allComments as $value => $singleComment) {
            $userCommentId = $singleComment->getUserId();
            echo '<div>Comments : <br>
                    <h5><div>Comment id : ' . $singleComment->getId() . '<br></div>
                    <div>' . $singleComment->getText() . '</div><br>
                    Created by :' . $tweetUser->getUsername() . ' on: ' . $singleComment->getCreationDate() . '<hr></h5>';
        }
        echo '<div>
                <form action="" method="post">
                <h6>Add new comment!:</h6>
                <input type="text" name="comment" placeholder = "What you Think about this?">
                <input type="hidden" name="postId" value="' . $id . '">
                <input type="submit" name="send" value="Comment">
                </form>
                </div>';
        ?>
    </div>
</div>