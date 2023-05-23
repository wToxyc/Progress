<?php

session_start();

if (isset($_GET['logout'])) {
    if (isset($_SESSION['email'])) {
        session_destroy();
    }
    header('Location: /');
    exit();
}

?>