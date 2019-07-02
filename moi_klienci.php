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
        //$wynik=$connect->query('SELECT klient.Id_klient,Imie,Nazwisko,Telefon,Email,Ulica,Ulica_nr,Kod_pocz,Miasto,Typ,Id_firma,Status,Data_pk FROM klient inner join adres using (Id_adres) inner join status_klienta using (Id_sk) inner join nazwa_statusu using (Id_nsk) inner join portfel_klientow on klient.Id_klient=portfel_klientow.Id_klient');
        $wynik=$connect->query('SELECT klient.Id_klient,klient.Imie,klient.Nazwisko,klient.Telefon,klient.Email,adres.Ulica,adres.Ulica_nr,adres.Kod_pocz,adres.Miasto,typ_adres.Typ,klient.Id_firma,Status,Concat(pracownicy.Nazwisko," ",pracownicy.Imie)as Pracownik,Data_pk FROM `portfel_klientow` inner join klient using (Id_klient) inner join pracownicy using (Id_pracownik) inner join adres using (Id_adres) inner join status_klienta using (Id_sk) inner join nazwa_statusu using (Id_nsk) inner join typ_adres using (Id_typAdr) where portfel_klientow.Id_pracownik='.$_SESSION['id_pracownik'].'');
        if($wynik->num_rows > 0){
            $klient=mysqli_fetch_all($wynik, MYSQLI_NUM);
        }
        else{
            echo "Nie pobrano danych z bazy danych.";
        }
        $wynik2=$connect->query('SELECT Id_firma,NIP,Telefon,Email,Ulica,Ulica_nr,Kod_pocz,Miasto,typ_adres.Typ FROM `firma` inner join adres using (Id_adres) inner join typ_adres using (Id_typAdr)');
        if($wynik2->num_rows > 0){
            $firma=mysqli_fetch_all($wynik2, MYSQLI_NUM);
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
            <h1>Moi klienci</h1>
            <?php
                echo '<table class="kl_tab">';
                echo '<tr><th>ID</th><th>Imię</th><th>Nazwisko</th><th>Telefon</th><th>Email</th><th>Adres</th><th>Firma-NIP</th><th>Firma-Telefon</th><th>Firma-Email</th><th>Firma-adres</th><th>Status klienta</th><th>Pracownik</th><th>Data dodania</th></tr>';
                for($i=0;$i<$wynik->num_rows;$i++){
                    $jest_firma=false;
                    echo '<tr><td>'.$klient[$i][0].'</td><td>'.$klient[$i][1].'</td><td>'.$klient[$i][2].'</td><td>'.$klient[$i][3].'</td><td>'.$klient[$i][4].'</td><td>Ul.'.$klient[$i][5].' '.$klient[$i][6].'<br>'.$klient[$i][7].' '.$klient[$i][8].'<br>'.$klient[$i][9].'</td>';
                    for($j=0;$j<$wynik2->num_rows;$j++){
                        if($firma[$j][0]==$klient[$i][10]){
                            $jest_firma=true;
                            echo '<td>'.$firma[$j][1].'</td><td>'.$firma[$j][2].'</td><td>'.$firma[$j][3].'</td><td>Ul.'.$firma[$j][4].' '.$firma[$j][5].'<br>'.$firma[$j][6].' '.$firma[$j][7].'<br>'.$firma[$j][8].'</td>';
                        }
                    }
                    if($jest_firma==false){
                        echo '<td>-</td><td>-</td><td>-</td><td>-</td>';
                    }
                    echo '<td>'.$klient[$i][11].'</td><td>'.$klient[$i][12].'</td><td>'.$klient[$i][13].'</td></tr>';
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