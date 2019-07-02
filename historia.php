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
        $wynik=$connect->query('SELECT kontakt.Id_kontakt,kontakt.Data_kontaktu,concat(klient.Nazwisko," ",klient.Imie),concat(pracownicy.Nazwisko," ",pracownicy.Imie),kategoria.Kategoria from kontakt inner join klient using (Id_klient) inner join pracownicy using (Id_pracownik) inner join kategoria using (Id_kat) order by kontakt.Data_kontaktu desc');
        if($wynik->num_rows > 0){
            $kontakt=mysqli_fetch_all($wynik, MYSQLI_NUM);
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
            <h1>Historia</h1>
            <?php
                echo '<table class="kl_tab2">';
                echo '<tr><th>ID</th><th>Data kontaktu</th><th>Klient</th><th>Pracownik</th><th>Rodzaj obsługi</th></tr>';
                for($i=0;$i<$wynik->num_rows;$i++){
                    echo '<tr><td>'.$kontakt[$i][0].'</td><td>'.$kontakt[$i][1].'</td><td>'.$kontakt[$i][2].'</td><td>'.$kontakt[$i][3].'</td><td>'.$kontakt[$i][4].'</td></tr>';
            
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