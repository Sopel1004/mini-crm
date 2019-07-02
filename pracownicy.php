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
        $wynik=$connect->query('SELECT Id_pracownik,Nazwisko,Imie,Login,Haslo,Email,Telefon,Dostep,Id_dostep FROM pracownicy inner join dostep using (Id_dostep)');
        if($wynik->num_rows > 0){
            $dane=mysqli_fetch_all($wynik, MYSQLI_NUM);
        }
        else{
            echo "Nie pobrano danych z bazy danych.";
        }
        $wynik2=$connect->query('SELECT * FROM dostep');
         if($wynik2->num_rows > 0){
            $dostep=mysqli_fetch_all($wynik2, MYSQLI_NUM);
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
            <h2>Pracownicy</h2>
            <?php
            if(isset($_SESSION['sukces'])){
                echo "<span class='sukces'>".$_SESSION['sukces']."</span>";
                unset($_SESSION['sukces']);
            }
            ?>
            <nav class="nav2">
                <button class="dodaj">Dodaj</button>
                <button class="usun">Usuń</button>
                <button class="edytuj">Edytuj</button>
            </nav>
            <?php
                echo '<table class="pr_tab">';
                echo '<tr><th>ID</th><th>Nazwisko</th><th>Imię</th><th>Login</th><th>Hasło</th><th>E-mail</th><th>Telefon</th><th>Uprawnienia</th></tr>';
                $i=0;
                while($i<$wynik->num_rows){
                    echo '<tr><td>'.$dane[$i][0].'</td><td>'.$dane[$i][1].'</td><td>'.$dane[$i][2].'</td><td>'.$dane[$i][3].'</td><td>'.$dane[$i][4].'</td><td>'.$dane[$i][5].'</td><td>'.$dane[$i][6].'</td><td>'.$dane[$i][7].'</td><td><input type="radio" name="id_p" class="id_p" data-i="'.$i.'" data-id="'.$dane[$i][0].'"></td></tr>';
                    $i++;
                }
                echo '</table>';
            ?>
            
            <form class="dodaj_form ukryty" action="dodaj.php" method="post">
                <p id="zamknij" class="zamknij">X</p>
                <h2>Dodaj pracownika</h2>
                <input type="text" name="nazwisko" class="dodaj_input" placeholder="Nazwisko"><br>
                <input type="text" name="imie" class="dodaj_input" placeholder="Imię"><br>
                <input type="text" name="login" class="dodaj_input" placeholder="Login"><br>
                <input type="text" name="haslo" class="dodaj_input" placeholder="Hasło"><br>
                <input type="email" name="email" class="dodaj_input" placeholder="Email"><br>
                <input type="text" name="telefon" class="dodaj_input" placeholder="Telefon" maxlength="9"><br>
                <select name="dostep" class="dodaj_input" placeholder="Uprawnienia">
                    <?php
                        for($i=0;$i<$wynik2->num_rows;$i++){
                            echo '<option value="'.$dostep[$i][0].'">'.$dostep[$i][1].'</option>';
                        }
                    ?>
                </select><br>
                <button type="submit" class="dodaj_btn">Dodaj</button>
            </form>
            
            <form class="dodaj_form ukryty usun_form" action="usun.php" method="post">
                <p class="zamknij zamknij3">X</p>
                <h2>Usuń pracownika</h2>
                <input type="text" name="id" class="id_prac2" hidden>
                <span>Wybrany pracownik zostanie usunięty. Kontynuować?</span><br>
                <button type="submit" class="dodaj_btn">Usuń</button>
            </form>
            
            <form class="dodaj_form ukryty edytuj_form" action="edytuj.php" method="post">
                <p class="zamknij zamknij2">X</p>
                <h2>Edytuj dane pracownika</h2>
                <input type="text" name="id" class="id_prac" hidden>
                <input type="text" name="nazwisko" class="dodaj_input nazwisko" placeholder="Nazwisko"><br>
                <input type="text" name="imie" class="dodaj_input imie" placeholder="Imię"><br>
                <input type="text" name="login" class="dodaj_input login" placeholder="Login"><br>
                <input type="text" name="haslo" class="dodaj_input haslo" placeholder="Hasło"><br>
                <input type="email" name="email" class="dodaj_input email" placeholder="Email"><br>
                <input type="text" name="telefon" class="dodaj_input telefon" placeholder="Telefon" maxlength="9"><br>
                <select name="dostep" class="dodaj_input dostep" placeholder="Uprawnienia">
                    <?php
                        for($i=0;$i<$wynik2->num_rows;$i++){
                            echo '<option value="'.$dostep[$i][0].'">'.$dostep[$i][1].'</option>';
                        }
                    ?>
                </select><br>
                <button type="submit" class="dodaj_btn">Edytuj</button>
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
        <?php
            if(isset($_GET['id'])){
                $i_id=$_GET['i_id'];
                $id=$_GET['id']; 
                echo '
                    x=document.querySelectorAll(".id_p");
                    for(i=0;i<x.length;i++){
                        if(x[i].getAttribute("data-id")=='.$id.') x[i].checked=true;
                    }';
            }
        ?>
        
        document.querySelector(".dodaj").addEventListener("click", function(){
            document.querySelector(".dodaj_form").classList.remove('ukryty');
            document.querySelector(".dodaj_form").classList.add('pokaz');
        });
        
        document.querySelector(".zamknij").addEventListener("click", function(){
            document.querySelector(".dodaj_form").classList.remove('pokaz');
            document.querySelector(".dodaj_form").classList.add('ukryty');
        });
        
        document.addEventListener("click", function(event){
            if(event.target.classList.contains('id_p')){
                window.location.href="pracownicy.php?id="+event.target.getAttribute('data-id')+"&i_id="+event.target.getAttribute('data-i');
            }
            
        });
        
        
        document.querySelector(".usun").addEventListener("click", function(){
            <?php
                if(isset($id)){
                
                echo '
                    document.querySelector(".id_prac2").value='.$id.';
                    ';
                }
            ?>
    
            document.querySelector(".usun_form").classList.remove('ukryty');
            document.querySelector(".usun_form").classList.add('pokaz');
        });
        
        document.querySelector(".zamknij3").addEventListener("click", function(){
            document.querySelector(".usun_form").classList.remove('pokaz');
            document.querySelector(".usun_form").classList.add('ukryty');
        });
        
        
        
        document.querySelector(".edytuj").addEventListener("click", function(){
            <?php
                if(isset($id)){
                
                echo '
                
                    document.querySelector(".id_prac").value='.$id.';

                    document.querySelector(".nazwisko").value="'.$dane[$i_id][1].'";
                    document.querySelector(".imie").value="'.$dane[$i_id][2].'";
                    document.querySelector(".login").value="'.$dane[$i_id][3].'";
                    document.querySelector(".haslo").value="'.$dane[$i_id][4].'";
                    document.querySelector(".email").value="'.$dane[$i_id][5].'";
                    document.querySelector(".telefon").value="'.$dane[$i_id][6].'";
                    document.querySelector(".dostep").value="'.$dane[$i_id][8].'";

               
                    ';
                }
            ?>
            document.querySelector(".edytuj_form").classList.remove("ukryty");
            document.querySelector(".edytuj_form").classList.add("pokaz");
        });
            
        
        document.querySelector(".zamknij2").addEventListener("click", function(){
            document.querySelector(".edytuj_form").classList.remove('pokaz');
            document.querySelector(".edytuj_form").classList.add('ukryty');
        });
        
        
    </script>
</body>
</html>