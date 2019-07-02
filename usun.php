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
        $connect->query('DELETE FROM pracownicy WHERE Id_pracownik="'.$_POST['id'].'"');

    $connect->close();
    }
    $_SESSION['sukces']="Usunięto pracownika";
    header('Location: pracownicy.php');
    ob_end_flush();
?>