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
        $wynik=$connect->query('DELETE FROM zadania WHERE Id_zad="'.$_POST['id'].'"');

    $connect->close();
    }
    $_SESSION['sukces']="Usunięto zadanie";
    header('Location: main.php');
    ob_end_flush();
?>