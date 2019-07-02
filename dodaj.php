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
        $connect->query('INSERT INTO pracownicy (`Id_pracownik`, `Nazwisko`, `Imie`, `Login`, `Haslo`, `Email`, `Telefon`, `Id_dostep`) VALUES (Null,"'.$_POST['nazwisko'].'","'.$_POST['imie'].'","'.$_POST['login'].'","'.$_POST['haslo'].'","'.$_POST['email'].'","'.$_POST['telefon'].'","'.$_POST['dostep'].'")');

    $connect->close();
    }
    $_SESSION['sukces']="Dodano pracownika";
    header('Location: pracownicy.php');
    ob_end_flush();
?>