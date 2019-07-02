<!DOCTYPE html>
<?php
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
        $wynik=$connect->query('SELECT zamowienia.Id_zam,produkt.Nazwa,concat(klient.Nazwisko," ",klient.Imie),zamowienia.Data_zam,zamowienia.Zysk,adres.Ulica,adres.Ulica_nr,adres.Kod_pocz,adres.Miasto from zamowienia inner join produkt using (Id_produkt) inner join klient using (Id_klient) inner join adres using (Id_adres) order by zamowienia.Data_zam desc');
        if($wynik->num_rows > 0){
            $zam=mysqli_fetch_all($wynik, MYSQLI_NUM);
        }
        else{
            echo "Nie pobrano danych z bazy danych.";
        }

    $connect->close();
    }

?>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <?php
        echo '<title>Zalogowany: '.$_SESSION['login'].'</title>';
    ?>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet">
    <link href="favicon.ico" rel="icon" type="image/x-icon" />
</head>
<body>
    
    <main class="con2">
        <nav class="nav">
            <a href="main.php" class="el">Strona główna</a>
            <a href="pracownicy.php" class="el pr">Pracownicy</a>
            <div class="m_roz el">
                <span>Klienci</span>
                <div class="roz">
                    <a href="dodawanie_klienta.php" class="roz_el">Dodaj klienta</a>
                    <a href="wsz_klienci.php" class="roz_el wszk">Wszyscy klienci</a>
                    <a href="moi_klienci.php" class="roz_el">Moi klienci</a>
                </div>
            </div>
            <div class="m_roz el">
                <span>Usługi</span>
                <div class="roz">
                    <a href="kontakt.php" class="roz_el">Kontakt z klientem</a>
                    <a href="historia.php" class="roz_el">Historia</a>
                    <a href="wsz_zamowienie.php" class="roz_el wszz">Wszystkie zamówienia</a>
                    <a href="zamowienie.php" class="roz_el">Zamówienia</a>
                </div>
            </div>
            <a href="wyloguj.php" class="el">Wyloguj</a>
        </nav>
        <section class="main">
            <h1>Wszystkie zamówienia</h1>
            <?php
                echo '<table class="kl_tab2">';
                echo '<tr><th>ID</th><th>Produkt</th><th>Klient</th><th>Data zamówienia</th><th>Zysk</th><th>Adres dostawy</th></tr>';
                for($i=0;$i<$wynik->num_rows;$i++){
                    echo '<tr><td>'.$zam[$i][0].'</td><td>'.$zam[$i][1].'</td><td>'.$zam[$i][2].'</td><td>'.$zam[$i][3].'</td><td>'.$zam[$i][4].'</td><td>Ul.'.$zam[$i][5].' '.$zam[$i][6].'<br>'.$zam[$i][7].' '.$zam[$i][8].'</td></tr>';
            
                }
                echo '</table>';
            ?>
        </section>
        
        
    </main>
    <script>
        let dostep="<?php echo $_SESSION['dostep']; ?>";
        if(dostep=="user"){
            document.querySelector('.wszk').classList.add('ukryty');
            document.querySelector('.wszz').classList.add('ukryty');
            document.querySelector('.pr').classList.add('ukryty');
        }
    </script>
</body>
</html>