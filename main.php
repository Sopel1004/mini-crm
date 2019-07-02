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
        $wynik=$connect->query('SELECT concat(Nazwisko," ",Imie) FROM pracownicy WHERE Id_pracownik="'.$_SESSION['id_pracownik'].'"');
        if($wynik->num_rows > 0){
            $dane=mysqli_fetch_all($wynik, MYSQLI_NUM);
        }
        else{
            echo "Nie pobrano danych z bazy danych.";
        }
        
        $wynik2=$connect->query('SELECT Id_pracownik,Nazwisko,Imie FROM pracownicy');
        if($wynik2->num_rows > 0){
            $dane2=mysqli_fetch_all($wynik2, MYSQLI_NUM);
        }
        else{
            echo "Nie pobrano danych z bazy danych.";
        }
        
        $wynik3=$connect->query('SELECT Id_zad,Data,Tytul,Tresc FROM zadania where Id_pracownik="'.$_SESSION['id_pracownik'].'" order by Data asc');
        if($wynik2->num_rows > 0){
            $zad=mysqli_fetch_all($wynik3, MYSQLI_NUM);
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
        <section class="mainTiled">
            
            <section class="mk">
                <section class="glowny kafel">
                <?php
                    echo '<span>Witaj, '.$dane[0][0].'</span>';
                ?>
                </section>
                <section class="todo kafel">
                    <span>Lista zadań</span><br>
                    <?php
                        if(isset($_SESSION['sukces'])){
                            echo "<span class='sukces'>".$_SESSION['sukces']."</span>";
                            unset($_SESSION['sukces']);
                        }
                    ?>
                    <button class="todo_dodaj">Dodaj zadanie</button>
                    <form action="todo_dodaj.php" method="post" class="todo_form ukryty">
                        <p class="zamknij">X</p>
                        <input type="text" name="tytul" class="todo_input" placeholder="Tytuł"><br>
                        <input type="date"  name="data" class="todo_input" placeholder="Data"><br>
                        <textarea name="tresc" placeholder="Treść" rows=6 cols=88></textarea><br>
                        <select name="kto" class="todo_input">
                            <?php
                            
                                if($_SESSION['dostep']=="admin"){
                                   for($i=0;$i<$wynik2->num_rows;$i++){
                                    if($dane2[$i][0]==$_SESSION['id_pracownik'])
                                        echo '<option value="'.$_SESSION['id_pracownik'].'" selected>Dla siebie</option>';
                                    else echo '<option value="'.$dane2[$i][0].'">'.$dane2[$i][1].' '.$dane2[$i][2].'</option>';
                                } 
                                }
                                else echo '<option value="'.$_SESSION['id_pracownik'].'" selected>Dla siebie</option>';
                                
                            ?>
                        </select><br>
                        <button type="submit" class="todo_btn">Dodaj</button>
                    </form>
                    <?php
                    for($i=0;$i<$wynik3->num_rows;$i++){
                        echo '<div class="todo_list">
                        <div class="todo_title open_list" data-nr="'.$i.'"><span class="open_list" data-nr="'.$i.'">'.$zad[$i][1].'</span><span class="open_list" data-nr="'.$i.'">'.$zad[$i][2].'</span><img src="icons8-waste-50.png" alt="Usuń" class="td_usun" data-td_id="'.$zad[$i][0].'"></div>
                        <div class="todo_cont ukryty" data-nr2="'.$i.'"><span>'.$zad[$i][3].'</span></div>
                        </div>';
                    }
                    
                       
                    ?>
                    
                    <form class="dodaj_form ukryty usun_form td_usunform" action="todo_usun.php" method="post">
                        <p class="zamknij2">X</p>
                        <input type="text" name="id" class="td_id_zad" hidden readonly>
                        <span>Czy chcesz usunąć to zadanie?</span><br>
                        <button type="submit" class="dodaj_btn">Usuń</button>
                    </form>
                </section>
                
            </section>
            
            <section class="gk">
                <section class="godzina kafel"><img class="symbol" alt="symbol"><span class="godz"></span></section>
                <section class="kalendarz kafel">
                    <div id="calendar"></div>   
                
                
                </section>
            </section>
            
            
            
        </section>
        
        
    </main>
    
    <script>
        let dostep="<?php echo $_SESSION['dostep']; ?>";
        if(dostep=="user"){
            document.querySelector('.wszk').classList.add('ukryty');
            document.querySelector('.wszz').classList.add('ukryty');
            document.querySelector('.pr').classList.add('ukryty');
        }
        
        function startTime() {
          let today = new Date();
          let h = today.getHours();
          let m = today.getMinutes();
          let s = today.getSeconds();
          m = checkTime(m);
          s = checkTime(s);
            let time=h + ":" + m + ":" + s;
            if(h>6 && h<20){
                document.querySelector('.symbol').src="https://img.icons8.com/ios/48/000000/sun.png";
            } 
            else {
                document.querySelector('.symbol').src="https://img.icons8.com/ios/48/000000/moon-symbol.png";
            }
            
            document.querySelector('.godz').innerHTML =time;    
          let t = setTimeout(startTime, 500);
        }
        function checkTime(i) {
          if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
          return i;
        }
        
        document.body.addEventListener('load', startTime());
        
        document.querySelector('.todo_dodaj').addEventListener("click", function(){
            document.querySelector('.todo_form').classList.remove('ukryty');
            document.querySelector('.todo_form').classList.add('pokaz');
        });
        
        document.querySelector('.zamknij').addEventListener("click", function(){
            document.querySelector('.todo_form').classList.remove('pokaz');
            document.querySelector('.todo_form').classList.add('ukryty');
        });
        
        document.addEventListener("click", function(event){
            if(event.target.classList.contains('open_list')){
                let nr=event.target.getAttribute('data-nr');
                let x=document.querySelectorAll('.todo_cont')
                if(x[nr].classList.contains('ukryty')){
                    x[nr].classList.remove('ukryty');
                    x[nr].classList.add('pokaz');
                }
                else{
                    x[nr].classList.remove('pokaz');
                    x[nr].classList.add('ukryty');
                }
                //window.location.href="pracownicy.php?id="+event.target.getAttribute('data-id')+"&i_id="+event.target.getAttribute('data-i');
            }
            
        });
        
        
        function Calendar(month, year) {
          var now = new Date();

          // labels for week days and months
          var days_labels = ['Pn', 'Wt', 'Śr', 'Czw', 'Pt', 'Sob', 'Ndz'],
              months_labels = ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'];

          // test if input date is correct, instead use current month
          this.month = (isNaN(month) || month == null) ? now.getMonth() + 1 : month;
          this.year = (isNaN(year) || year == null) ? now.getFullYear() : year;

          var logical_month = this.month - 1;

          // get first day of month and first week day
          var first_day = new Date(this.year, logical_month, 1),
              first_day_weekday = first_day.getDay() == 0 ? 7 : first_day.getDay();

          // find number of days in month
          var month_length = new Date(this.year, this.month, 0).getDate(),
              previous_month_length = new Date(this.year, logical_month, 0).getDate();

          // calendar header
          var html = '<div class="calendar_title">' + months_labels[logical_month] + ' ' + this.year + '</div>';

          // calendar content
          html += '<table class="calendar-table">';

          // week days labels row
          
          html += '<tr class="week-days">';
          for (var i = 0; i <= 6; i++) {
            html += '<th>';
            html += days_labels[i];
            html += '</th>';
          }
          html += '</tr>';
          

          // define default day variables
          var day  = 1, // current month days
              prev = 1, // previous month days
              next = 1; // next month days

          
          html += '<tr class="week">';
          // weeks loop (rows)
          for (var i = 0; i < 9; i++) {
            // weekdays loop (cells)
            for (var j = 1; j <= 7; j++) {
              if (day <= month_length && (i > 0 || j >= first_day_weekday)) {
                // current month
                   if(day==now.getDate()) html += '<td class="day current_day">';
                  else html += '<td class="day">';
                html += day;
                html += '</td>';
                 
                day++;
              } else {
                if (day <= month_length) {
                  // previous month
                  html += '<td class="day other-month">';
                  html += previous_month_length - first_day_weekday + prev + 1;
                  html += '</td>';
                  prev++;
                } else {
                  // next month
                  html += '<td class="day other-month">';
                  html += next;
                  html += '</td>';
                  next++;
                }
              }
            }

            // stop making rows if it's the end of month
            if (day > month_length) {
              html += '</tr>';
              break;
            } else {
              html += '</tr><tr class="week">';
            }
          }
          
          html += '</table>';

          return html;
        }

        // document.getElementById('calendar').innerHTML = Calendar(12, 2015); 
        document.getElementById('calendar').innerHTML = Calendar();
        
        document.addEventListener("click", function(event){
            if(event.target.classList.contains('td_usun')){
               let td_id=event.target.getAttribute("data-td_id");
                document.querySelector('.td_id_zad').value=td_id;
                document.querySelector('.td_usunform').classList.remove('ukryty');
                document.querySelector('.td_usunform').classList.add('pokaz');
            }
        });
        
        document.querySelector('.zamknij2').addEventListener("click", function(){
            document.querySelector('.td_usunform').classList.remove('pokaz');
            document.querySelector('.td_usunform').classList.add('ukryty');
        });
        
        
    </script>
</body>
</html>