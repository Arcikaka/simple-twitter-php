<?php
//dostepna dla zalogowanych
checkLoginOrRedirect();
require_once __DIR__ . '/../src/Tweet.php';
require_once __DIR__ . '/../src/User.php';
require_once __DIR__ . '/../src/Comment.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['send']) {
        case 'Tweet':

            $tweet = trim($_POST['tweet']);
            if ($tweet < 160) {
                $userId = $userSession['id'];
                $newTweet = new Tweet();
                $newTweet->setCreationDate();
                $newTweet->setTweet($tweet);
                $newTweet->setUserId($userId);
                $newTweet->saveToDBTweets($conn);
            }
            break;

        case 'Comment':

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
            break;

    }

}
?>
<form action="" method="post">
    <h2>New Tweet :</h2>
    <br>
    <input type="text" name="tweet" placeholder="What you Think?">
    <br>
    <input type="submit" name="send" value="Tweet">
</form>

<div>
    <h3>Najnowsze Tweety: </h3>

    <?php
    $tweets = Tweet::loadAllTweets($conn);


    foreach ($tweets as $value => $tweet) {
        $userId = $tweet->getUserId();
        $tweetId = $tweet->getId();
        $comment = Comment::loadAllCommentsByPostId($conn, $tweetId);
        $user = User::loadUserById($conn, $userId);

        echo '<div><h1>Użytkownik : <a href="index.php?page=allUserTweets&id=' . $tweet->getUserId() . '">' . $user->getUsername() . ' </a></div></h1><br>
                <div><a href="index.php?page=singleTweet&id=' . $tweet->getId() . '">Tweet:</a><br>' . $tweet->getTweet() . '</div></br><div>Data utworzenia: ' . $tweet->getCreationDate() . '<br><hr>';
        foreach ($comment as $key => $singleComment) {
            $userCommentId = $singleComment->getUserId();
            $userComment = new User();
            $userCommenter = $userComment::loadUserById($conn, $userCommentId);
            echo '<div>Komentarze : <br>
                    <h5><div>Komentarz o id : ' . $singleComment->getId() . '<br></div>
                    <div>' . $singleComment->getText() . '</div><br>
                    Stworzony przez :' . $userCommenter->getUsername() . ' dnia: ' . $singleComment->getCreationDate() . '<hr></h5>';
        }
        echo '<div>
                <form action="" method="post">
                <h6>Dodaj nowy komentarz:</h6>
                <input type="text" name="comment" placeholder = "What you Think about this?">
                <input type="hidden" name="postId" value="' . $tweet->getID() . '">
                <input type="submit" name="send" value="Comment">
                </form>
                </div>';
    }
    ?>
</div>
