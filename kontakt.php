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
        $wynik=$connect->query('SELECT klient.Id_klient,klient.Nazwisko,klient.Imie FROM portfel_klientow inner join klient using (Id_klient) where portfel_klientow.Id_pracownik='.$_SESSION['id_pracownik'].'');
        if($wynik->num_rows > 0){
            $klient=mysqli_fetch_all($wynik, MYSQLI_NUM);
        }
        else{
            echo "Nie pobrano danych z bazy danych.";
        }
        $wynik2=$connect->query('Select * from kategoria');
        if($wynik2->num_rows > 0){
            $kategoria=mysqli_fetch_all($wynik2, MYSQLI_NUM);
        }
        else{
            echo "Nie pobrano danych z bazy danych.";
        }
        $wynik3=$connect->query('Select * from produkt');
        if($wynik3->num_rows > 0){
            $produkt=mysqli_fetch_all($wynik3, MYSQLI_NUM);
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
            <h1>Kontakt z klientem</h1>
            <?php
                        if(isset($_SESSION['sukces'])){
                            echo "<span class='sukces'>".$_SESSION['sukces']."</span>";
                            unset($_SESSION['sukces']);
                        }
            ?>
            <form action="kontakt_skrypt.php" method="post">
                <label>Data:<br><input type="text" class="data dodaj_input" readonly></label><br>
                <label>Klient:<br><select name="klient" class="klient dodaj_input" required>
                    <?php
                        for($i=0;$i<$wynik->num_rows;$i++){
                            echo '<option value="'.$klient[$i][0].'">'.$klient[$i][1].' '.$klient[$i][2].'</option>';
                        }
                    echo '<option value="nowyklient">Nowy klient</option>'
                    ?>
                </select></label><br>
                <label>Kategoria:<br><select name="kategoria" class="kategoria dodaj_input" required>
                    <?php
                        for($i=0;$i<$wynik2->num_rows;$i++){
                            echo '<option value="'.$kategoria[$i][0].'">'.$kategoria[$i][1].'</option>';
                        }
                    
                    ?>
                </select></label><br>
                <div class="sprzedaz ukryty">
                    <h3>Sprzedaż</h3>
                    <label>Produkt:<br><select name="produkt" class="produkt dodaj_input">
                    <?php
                        for($i=0;$i<$wynik3->num_rows;$i++){
                            echo '<option value="'.$produkt[$i][0].'" data-cena="'.$produkt[$i][2].'">'.$produkt[$i][1].' '.$produkt[$i][2].'zł</option>';
                        }
                    ?>
                    </select></label><br>
                    <input type="number" class="dodaj_input ilosc" placeholder="Ilość" min=0 step=1><br>
                    <input type="text" name="zysk" class="dodaj_input zysk" placeholder="Zysk" readonly>
                </div>
                <button type="submit" class="dodaj_btn">Wyślij</button>
            </form>
        </section>
        
        
    </main>
    <script>
        let dostep="<?php echo $_SESSION['dostep']; ?>";
        if(dostep=="user"){
            document.querySelector('.wszk').classList.add('ukryty');
            document.querySelector('.wszz').classList.add('ukryty');
            document.querySelector('.pr').classList.add('ukryty');
        }
        let data = new Date();
        document.querySelector('.data').value=data.getDate()+"."+(data.getMonth()+1)+"."+data.getFullYear();
        
        
        document.querySelector('.klient').addEventListener("change", function(){
            if(this.value=="nowyklient") window.location="dodawanie_klienta.php";
        });
        
        document.querySelector('.kategoria').addEventListener("change", function(){
            if(this.value==2){
                document.querySelector('.sprzedaz').classList.remove('ukryty');
            }
            else{
                document.querySelector('.sprzedaz').classList.add('ukryty');
            }
        });
        
        document.querySelector('.sprzedaz').addEventListener("change", function(){
            console.log(document.querySelector('.produkt').querySelector(':checked').getAttribute('data-cena'));
            document.querySelector('.zysk').value=document.querySelector('.ilosc').value*document.querySelector('.produkt').querySelector(':checked').getAttribute('data-cena');
        });
    </script>
</body>
</html>