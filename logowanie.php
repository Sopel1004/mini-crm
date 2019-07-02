<?php
ob_start();
    session_start();
    if(!isset($_POST['login'])){
        header('Location: index.php');
        exit;
  	}

    require_once "connect.php";

    $connect=@new mysqli($host,$db_user,$db_password,$db_name);
    if($connect->connect_errno !=0){
        echo "Błąd połączenia";
    } else{
        $wynik=$connect->query('SELECT Id_pracownik,Nazwisko,Imie,Dostep FROM pracownicy inner join dostep using(Id_dostep) WHERE Login="'.$_POST['login'].'" AND haslo="'.$_POST['haslo'].'"');
        if($wynik->num_rows==1){
            $dane=mysqli_fetch_assoc($wynik);
            $_SESSION['login']=$dane['Nazwisko']." ".$dane['Imie'];
            $_SESSION['zalogowany']=true;
            $_SESSION['id_pracownik']=$dane['Id_pracownik'];
            $_SESSION['dostep']=$dane['Dostep'];
            header('Location: main.php');
            
        }
        
        else{
            $_SESSION['blad_login']="Zły login lub hasło.";
            header('Location: index.php');
            exit;
        }

    $connect->close();
    }
    ob_end_flush();
?>