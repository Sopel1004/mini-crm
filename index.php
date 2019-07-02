<!DOCTYPE html>
<?php
    session_start();
?>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>CRM</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet">
        <link href="favicon2.ico" rel="icon" type="image/x-icon" />
</head>
<body>
    <main class="con">
        <section class="main">
            <h1>Logowanie do systemu</h1>
            <form action="logowanie.php" method="post">
            <input type="text" class="log_input" name="login" placeholder="Login"><br>
            <input type="password" class="log_input" name="haslo" placeholder="Hasło"><br>
            <button type="submit" class="log_btn">Zaloguj</button>
            </form>
            <?php
            if(isset($_SESSION['blad_login'])){
                echo "<span id='blad'>".$_SESSION['blad_login']."</span>";
                unset($_SESSION['blad_login']);
            }
            ?>
        </section>
    </main>
    <script>
    </script>
</body>
</html>