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
        $connect->query('INSERT INTO `kontakt`(`Id_kontakt`, `Data_kontaktu`, `Id_klient`, `Id_pracownik`, `Id_kat`) VALUES (Null,"'.date('Y-m-d').'","'.$_POST['klient'].'","'.$_SESSION['id_pracownik'].'","'.$_POST['kategoria'].'")');
        if($_POST['kategoria']==2){
            $connect->query('INSERT INTO `zamowienia`(`Id_zam`, `Id_produkt`, `Id_klient`, `Data_zam`, `Zysk`, `Id_pracownik`) VALUES (Null,"'.$_POST['produkt'].'","'.$_POST['klient'].'","'.date('Y-m-d').'","'.$_POST['zysk'].'","'.$_SESSION['id_pracownik'].'")');
        }

    $connect->close();
    }
    $_SESSION['sukces']="Dodano wpis o obsłudze klienta";
    header('Location: kontakt.php');
    ob_end_flush();
?>