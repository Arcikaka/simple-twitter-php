<?php
//dostepna dla zalogowanych
checkLoginOrRedirect();
require_once __DIR__ . '/../src/Tweet.php';
require_once __DIR__ . '/../src/User.php';

?>
<form action="" method="post">
    <h2>Nowy Tweet :</h2>
    <br>
    <input type="text" name="tweet" placeholder="O czym myślisz?">
    <br>
    <button>Wyślij!</button>
</form>

<div>
    <h3>Najnowsze Tweety: </h3>

    <?php
    $tweet = new Tweet();
    $tweets = $tweet::loadAllTweets($conn);


    foreach ($tweets as $value => $tweet) {
        $userId = $tweet->getUserId();
        $newUser = new User();
        $user = $newUser::loadUserById($conn,$userId);
        echo '<div><h1>Użytkownik : <a href="index.php?page=allUserTweets&id=' . $tweet->getUserId() . '">' . $user->getUsername() . ' </a></div></h1><br>
                <div>' . $tweet->getTweet() . '</div></br><div>Data utworzenia: ' . $tweet->getCreationDate() . '<br><hr>';
    }


    ?>
</div>
