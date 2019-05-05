<?php
//shows all user tweets depending on user ID

require_once __DIR__ . '/../src/Tweet.php';
require_once __DIR__ . '/../src/User.php';
require_once  __DIR__ .'/../src/Comment.php';
require_once  __DIR__ .'/../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userID = $_GET['id'];
    $user = User::loadUserById($conn, $userID);
    $tweet = Tweet::loadAllTweetsByUserId($conn, $userID);
}

addComment();


?>

<div>
    <h2>Tweety użytkownika: <?php echo $user->getUsername() ?> </h2>
    <div>
        <?php
        foreach ($tweet as $value => $singleTweet) {
            $postId = $singleTweet->getId();
            $allComments = Comment::loadAllCommentsByPostId($conn, $postId);
            $userId = $singleTweet->getUserId();
            $tweetUser = User::loadUserById($conn, $userId);
            echo '<div>' . $singleTweet->getTweet() . '</div></br><div>Data utworzenia: ' . $singleTweet->getCreationDate() . '<br><hr>';
            foreach ($allComments as $value => $singleComment) {
                $userCommentId = $singleComment->getUserId();
                $userCommenter = User::loadUserById($conn, $userCommentId);

                echo '<div>Komentarze : <br>
                    <h5><div>Komentarz o id : ' . $singleComment->getId() . '<br></div>
                    <div>' . $singleComment->getText() . '</div><br>
                    Stworzony przez :' . $userCommenter->getUsername() . ' dnia: ' . $singleComment->getCreationDate() . '<hr></h5>';
            }


            echo '<div>
                <form action="" method="post">
                <h6>Dodaj nowy komentarz:</h6>
                <input type="text" name="comment" placeholder = "What you Think about this?">
                <input type="hidden" name="postId" value="' . $singleTweet->getId() . '">
                <input type="submit" name="send" value="Comment">
                </form>
                </div>';
        }
        ?>


    </div>
</div>


