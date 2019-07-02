<?php
ob_start();
    session_start();
    if(!isset($_SESSION['zalogowany'])){
        header('Location: index.php');
        exit;
  	}

    require_once "connect.php";

    $connect=@new mysqli($host,$db_user,$db_password,$db_name);
    if($connect->connect_errno !=0){
        echo "Błąd połączenia";
    } else{
        $connect->query('INSERT INTO `zadania`(`Id_zad`, `Tytul`, `Data`, `Tresc`, `Id_pracownik`) VALUES (Null,"'.$_POST['tytul'].'","'.$_POST['data'].'","'.$_POST['tresc'].'","'.$_POST['kto'].'")');

    $connect->close();
    }
    $_SESSION['sukces']="Dodano nowe zadanie";
    header('Location: main.php');
    ob_end_flush();
?>