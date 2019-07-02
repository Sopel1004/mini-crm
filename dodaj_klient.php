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
        $connect->query('INSERT INTO `adres`(`Id_adres`, `Ulica`, `Ulica_nr`, `Miasto`, `Kod_pocz`, `Id_typAdr`) VALUES (Null,"'.$_POST['ulica'].'","'.$_POST['ulica_nr'].'","'.$_POST['miasto'].'","'.$_POST['kod'].'","'.$_POST['typ'].'")');
        $wynik=$connect->query('SELECT Id_adres FROM adres ORDER BY Id_adres DESC LIMIT 1');
        $dane=mysqli_fetch_assoc($wynik);
        if(isset($_POST['czy_firma'])){
            if(isset($_POST['czy_adres'])){
                $connect->query('INSERT INTO `firma`(`Id_firma`, `Id_adres`, `NIP`, `Telefon`, `Email`) VALUES (Null,"'.$dane['Id_adres'].'","'.$_POST['nip'].'","'.$_POST['telefon_firm'].'","'.$_POST['email_firm'].'")');
                
            }
            else{
                $connect->query('INSERT INTO `adres`(`Id_adres`, `Ulica`, `Ulica_nr`, `Miasto`, `Kod_pocz`, `Id_typAdr`) VALUES (Null,"'.$_POST['ulica_firm'].'","'.$_POST['ulica_nr_firm'].'","'.$_POST['miasto_firm'].'","'.$_POST['kod_firm'].'","'.$_POST['typ_firm'].'")');
                $wynik2=$connect->query('SELECT Id_adres FROM adres ORDER BY Id_adres DESC LIMIT 1');
                $dane2=mysqli_fetch_assoc($wynik2);
                $connect->query('INSERT INTO `firma`(`Id_firma`, `Id_adres`, `NIP`, `Telefon`, `Email`) VALUES (Null,"'.$dane2['Id_adres'].'","'.$_POST['nip'].'","'.$_POST['telefon_firm'].'","'.$_POST['email_firm'].'")');
                
            }
            $wynik3=$connect->query('SELECT Id_firma FROM firma ORDER BY Id_firma DESC LIMIT 1');
            $dane3=mysqli_fetch_assoc($wynik3);
            $connect->query('INSERT INTO `klient`(`Id_klient`, `Imie`, `Nazwisko`, `Telefon`, `Email`, `Id_adres`, `Id_firma`, `Id_sk`) VALUES (Null,"'.$_POST['imie'].'","'.$_POST['nazwisko'].'","'.$_POST['telefon'].'","'.$_POST['email'].'","'.$dane['Id_adres'].'","'.$dane3["Id_firma"].'","0")');
            
        }
        else{
            $connect->query('INSERT INTO `klient`(`Id_klient`, `Imie`, `Nazwisko`, `Telefon`, `Email`, `Id_adres`, `Id_firma`, `Id_sk`) VALUES (Null,"'.$_POST['imie'].'","'.$_POST['nazwisko'].'","'.$_POST['telefon'].'","'.$_POST['email'].'","'.$dane['Id_adres'].'",Null,"0")');
        }
        
        
        $wynik4=$connect->query('SELECT Id_klient FROM klient ORDER BY Id_klient DESC LIMIT 1');
        $dane4=mysqli_fetch_assoc($wynik4);
        $connect->query('INSERT INTO `portfel_klientow`(`Id_pk`, `Id_pracownik`, `Id_klient`, `Data_pk`) VALUES (Null,"'.$_SESSION['id_pracownik'].'","'.$dane4['Id_klient'].'","'.date('Y-m-d').'")');
        
        $connect->query('INSERT INTO `status_klienta`(`Id_sk`, `Id_nsk`, `Id_pracownik`, `Id_klient`, `Data_zmiany`) VALUES (Null,"'.$_POST['status'].'","'.$_SESSION['id_pracownik'].'","'.$dane4['Id_klient'].'","'.date('Y-m-d').'")');
        
        $wynik5=$connect->query('SELECT Id_sk FROM status_klienta ORDER BY Id_sk DESC LIMIT 1');
        $dane5=mysqli_fetch_assoc($wynik5);
        
        $connect->query('UPDATE `klient` SET `Id_sk`="'.$dane5['Id_sk'].'" WHERE Id_klient="'.$dane4['Id_klient'].'"');

    $connect->close();
    }
    $_SESSION['sukces']="Dodano klienta";
    header('Location: dodawanie_klienta.php');
    ob_end_flush();
?>