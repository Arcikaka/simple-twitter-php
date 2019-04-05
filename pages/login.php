<?php
//ta podstrona dostepna jest do nie zalogowanych
echo 'logowanie';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once __DIR__ . '/../src/User.php';
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    //szukamy uzytkownika po adresie email w bazie.
    $user = User::loadUserByEmail($conn,$email); //to zwraca obiekt user lub null
    //jesli istnieje to sprawdzamy czy haslo jest prawidlowe
    if (!is_null($user)) {
        if (password_verify($password, $user->getHashPass())) {
            $_SESSION['isLogged'] = true;
            $_SESSION['user'] = ['id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail()];
            ob_end_clean();
            header('Location: index.php');
        }
    }
    //jesli tak ustawiamy dane do sejsi aby fukncja sprawdzajaco zwracala nam true


}
?>

<form action="" method="post">
    <input type="email" name="email" placeholder="Email użytkownika">
    <br>
    <input type="password" name="password" placeholder="Hasło">
    <br>
    <br>
    <button>Zaloguj</button>
</form>


