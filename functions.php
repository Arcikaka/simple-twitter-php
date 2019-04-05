<?php

function isLogged()
{
    return isset($_SESSION['isLogged'], $_SESSION['user']) &&
        $_SESSION['isLogged'] === true;
}

function checkLoginOrRedirect()
{
    //sprawdzamy fakt zalogowania użytkownika i przekerowujemy
    if(!isLogged()) {
        //przekierowujemy
        ob_end_clean();//czyszczenie bufor
        header('Location: index.php?page=login');
        exit;
    }
}