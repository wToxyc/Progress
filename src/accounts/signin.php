<?php

session_start();

if (!isset($_SESSION['email'])) {
    $email = null;
    $password = null;

    if (isset($_POST['submit'])) {
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        include('../util/errors.php');

        if (!empty($email) AND filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (!empty($password)) {
                $db = new PDO('mysql:host=localhost;dbname=progress;charset=utf8', 'root', '');

                $req = "SELECT * FROM users WHERE email = '$email' ";
                $res = $db->prepare($req);
                $res->execute();

                if ($res->rowCount() == 1) {
                    $data = $res->fetchAll()[0];
                    if (password_verify($password, $data['password'])) {
                        $_SESSION['email'] = $email;
                        header('Location: profile');
                        exit();
                    } else {
                        echo $password_error;
                    }
                } else {
                    echo $email_error;
                }
            } else {
                echo $password_error;
            }
        } else {
            echo $email_error;
        }
    }

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="#" type="image/x-icon">
        <link rel="stylesheet" href="../public/css/index.css">
        <link rel="stylesheet" href="../public/css/signin.css">
        <title>Sign in - Progress</title>
    </head>
    <body>
        <header>
            <nav class="navbar">
                <a href="/" class="nav-btn">Home</a>
                <a href="/about" class="nav-btn">About</a>
                <a href="/contact" class="nav-btn">Contact</a>
            </nav>
        </header>

        <img src="../public/img/login-illustration.png" alt="" class="illustration">

        <div class="container">
            <h3>Sign in</h1>
            <form action="" method="post" autocomplete="off">
                <input type="text" name="email" id="email" class="text-fields" placeholder="E-mail" value="<?= $email; ?>">
                <input type="password" name="password" id="password" class="text-fields" placeholder="Password" value="<?= $password; ?>">
                <input type="submit" name="submit" value="Submit" class="submit-btn" id="submit">
            </form>
        </div>

        <footer>
            <section class="social-medias">
                <a class="social" href="https://twitter.com/wToxyc"><img src="../public/img/twitter.png" alt="Twitter"></a>
                <a class="social" href="https://instagram.com/wToxyc"><img src="../public/img/instagram.png" alt="Instagram"></a>
                <a href="https://discord.gg/"><img src="../public/img/discord.png" alt="Discord"></a>
                <a class="social" href="https://github.com/wToxyc"><img src="../public/img/github.png" alt="GitHub"></a>
                <a class="social" href="/contact.png"><img src="../public/img/mail.png" alt="Mail"></a>
            </section>
        </footer>
    </body>
    </html>

    <?php
} else {
    header('Location: profile');
    exit();
}

?>