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
        $wynik=$connect->query('SELECT * FROM nazwa_statusu');
        if($wynik->num_rows > 0){
            $dane=mysqli_fetch_all($wynik, MYSQLI_NUM);
        }
        else{
            echo "Nie pobrano danych z bazy danych.";
        }
        $wynik2=$connect->query('SELECT * FROM typ_adres');
         if($wynik2->num_rows > 0){
            $typ=mysqli_fetch_all($wynik2, MYSQLI_NUM);
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
            
            <form action="dodaj_klient.php" method="post">
                <h2>Dodaj klienta</h2>
                <?php
                        if(isset($_SESSION['sukces'])){
                            echo "<span class='sukces'>".$_SESSION['sukces']."</span>";
                            unset($_SESSION['sukces']);
                        }
                ?>
                <p>Dane klienta:</p>
                <input type="text" name="nazwisko" class="dodaj_input" placeholder="Nazwisko" required><br>
                <input type="text" name="imie" class="dodaj_input" placeholder="Imię" required><br>
                <input type="text" name="telefon" class="dodaj_input" placeholder="Telefon" required maxlength="9"><br>
                <input type="email" name="email" class="dodaj_input" placeholder="Email" required><br>
                <p>Adres:</p>
                <input type="text" name="ulica" class="dodaj_input adres_priv" placeholder="Ulica" required><br>
                <input type="number" name="ulica_nr" class="dodaj_input adres_priv" placeholder="Numer ulicy" min=1 step=1 required><br>
                <input type="text" name="miasto" class="dodaj_input adres_priv" placeholder="Miasto" required><br>
                <input type="text" name="kod" pattern="(\d{2}([\-]\d{3})?)" class="dodaj_input adres_priv" placeholder="Kod pocztowy" required><br>
                <select name="typ" class="dodaj_input adres_priv" placeholder="Typ adresu" required>
                    <?php
                        for($i=0;$i<$wynik2->num_rows;$i++){
                            echo '<option value="'.$typ[$i][0].'">'.$typ[$i][1].'</option>';
                        }
                    ?>
                </select><br><br>
                <select name="status" class="dodaj_input" placeholder="Status klienta" required>
                    <?php
                        for($i=0;$i<$wynik->num_rows;$i++){
                            echo '<option value="'.$dane[$i][0].'">'.$dane[$i][1].'</option>';
                        }
                    ?>
                </select><br><br>
                <label><input type="checkbox" name="czy_firma" class="czy_firma_check">Klient firmowy</label><br>
                <div class="form_firm ukryty">
                <p>Firma:</p>
                <input type="text" name="nip" class="dodaj_input" placeholder="NIP" maxlength="10"><br>
                <input type="text" name="telefon_firm" class="dodaj_input" placeholder="Telefon" maxlength="9"><br>
                <input type="email" name="email_firm" class="dodaj_input" placeholder="Email"><br>
                <p>Adres firmowy:</p>
                <label><input type="checkbox" class="adres_check" name="czy_adres">Taki sam adres?</label><br>
                <div class="firm_adres">
                <input type="text" name="ulica_firm" class="dodaj_input adres_firm" placeholder="Ulica"><br>
                <input type="number" name="ulica_nr_firm" class="dodaj_input adres_firm" placeholder="Numer ulicy"><br>
                <input type="text" name="miasto_firm" class="dodaj_input adres_firm" placeholder="Miasto"><br>
                <input type="text" name="kod_firm" pattern="(\d{2}([\-]\d{3})?)" class="dodaj_input adres_firm" placeholder="Kod pocztowy"><br>
                <select name="typ_firm" class="dodaj_input adres_firm" placeholder="Typ adresu">
                    <?php
                        for($i=0;$i<$wynik2->num_rows;$i++){
                            echo '<option value="'.$typ[$i][0].'">'.$typ[$i][1].'</option>';
                        }
                    ?>
                </select><br>
                </div>
                </div>
                <button type="submit" class="dodaj_btn">Dodaj</button>
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
        document.querySelector('.adres_check').addEventListener("click", function(){
            let x=document.querySelectorAll('.adres_priv');
            let y=document.querySelectorAll('.adres_firm');
            if(this.checked){
                for(let i=0;i<x.length;i++){
                    y[i].value=x[i].value;
                }
            }
            else{
                for(let i=0;i<y.length;i++){
                y[i].value="";
            }
            }
            
        });
        
        document.querySelector('.czy_firma_check').addEventListener("click", function(){
            if(this.checked){
                document.querySelector('.form_firm').classList.remove('ukryty');
            }
            else{
                document.querySelector('.form_firm').classList.add('ukryty');
            }
            
        });
        
        document.querySelector('.adres_check').addEventListener("click", function(){
            if(this.checked){
                document.querySelector('.firm_adres').classList.add('ukryty');
            }
            else{
                document.querySelector('.firm_adres').classList.remove('ukryty');
            }
            
        });
    </script>
</body>
</html>