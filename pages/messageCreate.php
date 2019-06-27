<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST['send'] === 'Send') {
        $id = $_POST['id'];
        $text = $_POST['message'];
        $userId = $userSession['id'];
        $message = new Message();
        $message->setMessage($text);
        $message->setSendBy($userId);
        $message->setSendTo($id);
        $message->setCreationDate();
        $message->setSeen(0);
        $message->saveToDB($conn);
    }
}

?>
<?php if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    echo '
<div>
    <form action="" method="post">
        <input type="text" placeholder="Message" name="message" width="400" height="200">
        <input type="submit" value="Send" name="send">
        <input type="hidden" name="id" value="' . $id . '">
    </form>
</div>';
}
