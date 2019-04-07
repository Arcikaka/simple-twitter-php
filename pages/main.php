<?php
//dostepna dla zalogowanych
checkLoginOrRedirect();
require_once __DIR__ . '/../src/Tweet.php';

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

    var_dump($tweets);

    foreach ($tweets as $value => $tweet) {

        //  object(Tweet)[6]
        //      private 'id' => string '2' (length=1)
        //      private 'userId' => string '2' (length=1)
        //      private 'tweet' => string 'Nah, that's cool' (length=16)
        //      private 'creationDate' => string '2019-04-07 13:05:35' (length=19)

        echo '<div><h1>Użytkownik : ' . $tweet['username'] . ' </div></h1><br>
                <div>' . $tweet->getTweet() . '</div></br><div>Data utworzenia: ' . $tweet->getCreationDate() . '<br><hr>';
    }


    ?>
</div>
