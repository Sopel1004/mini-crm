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
        $connect->query('UPDATE `pracownicy` SET `Nazwisko`="'.$_POST['nazwisko'].'",`Imie`="'.$_POST['imie'].'",`Login`="'.$_POST['login'].'",`Haslo`="'.$_POST['haslo'].'",`Email`="'.$_POST['email'].'",`Telefon`="'.$_POST['telefon'].'",`Id_dostep`="'.$_POST['dostep'].'" WHERE Id_pracownik='.$_POST['id'].'');

    $connect->close();
    }
    $_SESSION['sukces']="Dane zostały zaktualizowane";
    header('Location: pracownicy.php');
    ob_end_flush();
?>