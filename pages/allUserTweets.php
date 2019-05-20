<?php
//shows all user tweets depending on user ID

require_once __DIR__ . '/../src/Tweet.php';
require_once __DIR__ . '/../src/User.php';
require_once __DIR__ . '/../src/Comment.php';
require_once __DIR__ . '/../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userID = $_GET['id'];
    $user = User::loadUserById($conn, $userID);
    $tweet = Tweet::loadAllTweetsByUserId($conn, $userID);


    addComment();


    echo '<div>
    <h2>User Tweets: ' . $user->getUsername();
    if ($userSession['id'] != $user->getId()) {
        echo '<form action="index.php?page=messageCreate" method="post"><input name="create" type="submit" value="Send Message">
                    <input type="hidden" name="id" value="' . $user->getId() . '"></form>';
    }
    echo '</h2>
    <div>';

    foreach ($tweet as $value => $singleTweet) {
        $tweetId = $singleTweet->getId();
        $allComments = Comment::loadAllCommentsByPostId($conn, $tweetId);
        $userId = $singleTweet->getUserId();
        $tweetUser = User::loadUserById($conn, $userId);
        echo '<div>' . $singleTweet->getTweet() . '</div></br><div>Creation date: ' . $singleTweet->getCreationDate() . '<br><hr>';
        foreach ($allComments as $value => $singleComment) {
            $userCommentId = $singleComment->getUserId();
            $userCommenter = User::loadUserById($conn, $userCommentId);

            echo '<div>Comments : <br>
                    <h5><div>Comment id : ' . $singleComment->getId() . '<br></div>
                    <div>' . $singleComment->getText() . '</div><br>
                    Created By:' . $userCommenter->getUsername() . ' on: ' . $singleComment->getCreationDate() . '<hr></h5>';
        }


        echo '<div>
                <form action="" method="post">
                <h6>Dodaj nowy komentarz:</h6>
                <input type="text" name="comment" placeholder = "What you Think about this?">
                <input type="hidden" name="tweetId" value="' . $singleTweet->getId() . '">
                <input type="submit" name="send" value="Comment">
                </form>
                </div>';
    }
}

?>



