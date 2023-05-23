<?php

session_start();

$email = null;
$password = null;

$a = "Sign in";
$href = "accounts/signin";

if (isset($_SESSION['email'])) {
    $a = "Profile";
    $href = "accounts/profile";
}

if (isset($_POST['submit'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    include('util/errors.php');

    if (!empty($email) AND filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if (!empty($password)) {
            $db = new PDO('mysql:host=localhost;dbname=progress;charset=utf8', 'root', '');

            $req = "SELECT * FROM users WHERE email = '$email' ";
            $res = $db->prepare($req);
            $res->execute();

            if ($res->rowCount() == 0) {
                $password = password_hash($password, PASSWORD_DEFAULT);

                $req = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
                $res = $db->prepare($req);
                $res->execute();

                if ($res->rowCount() == 1) {
                    $_SESSION['email'] = $email;
                    $a = "Profile";
                    $href = "accounts/profile";
                } else {
                    echo $email_error;
                    echo $password_error;
                }
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
    <link rel="stylesheet" href="public/css/index.css">
    <title>Home - Progress</title>
</head>

<body>
    <header>
        <nav>
            <a class="nav-btn" href="about">About</a>
            <a class="nav-btn" href="contact">Contact</a>
            <a class="nav-btn" href="<?= $href; ?>"><?= $a; ?></a>
        </nav>
    </header>

    <section class="body">
        <h1>Hello!</h1>
        <h2>I am <span class="green">Toxy</span>, a junior fullstack developer and I made this app to improve my web skills. <i>(like design for example)</i></h2>
        <a class="learn-more" href="/about">Learn more</a>
    </section>

    <div class="container">
        <?php

        if (!isset($_SESSION['email'])) {
            ?>

            <h3>Signup</h3>

            <form action="" method="post" autocomplete="off">
                <input type="text" class="text-fields" name="email" id="email" placeholder="E-mail" value="<?= $email; ?>">
                <input type="password" class="text-fields" name="password" id="password" placeholder="Password" value="<?= $password; ?>">
                <input type="submit" class="submit-btn" name="submit" id="submit" value="Submit">
            </form>

            <?php
        } else {
            ?>

            <h3>Logged in!</h3>
            <h4>You are now successfully logged in as <span class="email"><?= $_SESSION['email']; ?></span>!</h4>
            <form action="accounts/logout" method="get">
                <input type="submit" value="Logout" class="submit-btn" name="logout">
            </form>

            <style>

                h4 {
                    color: #8378D1;
                    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                    cursor: default;
                    font-size: 30px;
                    text-align: center;
                    margin: 80px 20px 20px 20px;
                }

                .email {
                    color: #B8D7FF;
                }
            </style>

            <?php
        }

        ?>
    </div>

    <footer>
        <section class="social-medias">
            <a class="social" href="https://twitter.com/wToxyc"><img src="public/img/twitter.png" alt="Twitter"></a>
            <a class="social" href="https://instagram.com/wToxyc"><img src="public/img/instagram.png" alt="Instagram"></a>
            <a href="https://discord.gg/"><img src="public/img/discord.png" alt="Discord"></a>
            <a class="social" href="https://github.com/wToxyc"><img src="public/img/github.png" alt="GitHub"></a>
            <a class="social" href="contact"><img src="public/img/mail.png" alt="Mail"></a>
        </section>
    </footer>
</body>

</html>