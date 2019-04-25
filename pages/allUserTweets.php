<?php
//shows all user tweets depending on user ID

require_once __DIR__ . '/../src/Tweet.php';
require_once __DIR__ . '/../src/User.php';

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $userID = $_GET['id'];
    $user = User::loadUserById($conn,$userID);
    $tweet = Tweet::loadAllTweetsByUserId($conn,$userID);
}

?>

<div>
    <h2>Tweety użytkownika: <?php echo $user->getUsername() ?> </h2>
    <div>
        <?php
        foreach ($tweet as $value => $singleTweet) {
            echo '<div>' . $singleTweet->getTweet() . '</div></br><div>Data utworzenia: ' . $singleTweet->getCreationDate() . '<br><hr>';
        }
        ?>
    </div>
</div>


