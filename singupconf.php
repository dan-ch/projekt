<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gablota.pl - profil</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <header class="header">
    <nav class="header-wrap">
      <div class="header__nav">
        <a href="index.php" class="header__title link-reset ">Gablota.pl</a>
        <img class="header__burger" src="assets/icon/bars-solid.svg" alt="Burger icon">
        <div class="header__nav-menu">
                    <ul class="list-reset header__nav-list">
                        <li><a class="header__nav-link link-reset" href="addoffer.php">Dodaj ogłosznie</a></li>
                        <li><a class="header__nav-link link-reset" href="profile.php">Moje konto</a></li>
                        <?php 
                        if (!empty($_SESSION['user'])) echo '<li><a class="header__nav-link link-reset" href="singin.php">Wyloguj</a></li>';
                        else
                        {
                             echo '<li><a class="header__nav-link link-reset" href="singin.php">Zaloguj</a></li>';
                             session_destroy();
                        }
                        ?>
                    </ul>
                </div>
      </div>
      <div class="header__mobile">
        <ul class="list-reset header__mobile-list">
          <li><a class="header__mobile-link link-reset" href="addoffer.php">Dodaj ogłosznie</a></li>
          <li><a class="header__mobile-link link-reset" href="profile.php">Moje konto</a></li>
          <li><a class="header__mobile-link link-reset" href="singin.php">Zaloguj/Wyloguj</a></li>
        </ul>
      </div>
    </nav>
  </header>
  <main class="main">
    <section class="singup">
      <h2 class="singup__logo">Gablota.pl</h2>
      <?php
        try {
          $baza = new PDO("mysql:host=localhost;dbname=Gablota", "root");
          $emaillist = $baza->query("SELECT email FROM uzytkownicy")->fetchAll();
        } catch (PDOException $e) {
          echo 'Błąd połączenia' . $e->getMessage();
        }
        
        $flag=true;
        foreach ($emaillist as $email) {
          if($email["email"] == $_POST["email"]) 
          {
            echo "Taki email juz istnieje";
            $flag = false;
          }
        }

        if($_POST['haslo'] != $_POST['haslo2'])
        {
          $flag=false;
          echo "Podane hasła nie są identyczne";
        }
        
        if($flag)
        {
          $dodaj = $baza -> prepare("INSERT INTO uzytkownicy(imie, nazwisko, telefon, miejscowosc, email, haslo) VALUES (:imie, :nazwisko, :telefon, :miejscowosc, :email, :haslo)");
          $dodaj -> bindValue(':imie',$_POST['imie'] , PDO::PARAM_STR);
          $dodaj -> bindValue(':nazwisko',$_POST['nazwisko'] , PDO::PARAM_STR);
          $dodaj -> bindValue(':telefon',$_POST['telefon'] , PDO::PARAM_INT);
          if(empty($_POST['miejscowosc'])) $dodaj -> bindValue(':miejscowosc',null, PDO::PARAM_NULL);
          else $dodaj -> bindValue(':miejscowosc',$_POST['miejscowosc'] , PDO::PARAM_STR);
          $dodaj -> bindValue(':email',$_POST['email'] , PDO::PARAM_STR);
          $dodaj -> bindValue(':haslo', hash('sha256', $_POST['haslo']), PDO::PARAM_STR);
          $flag = $dodaj->execute();
          if($flag) echo "Udało się utworzyć konto";
          else echo "Cos poszło nie tak";
        }
      ?>
    </section>
  </main>
  <footer class="footer-wrap">
    <div class="footer">
      <div class="footer__social">
        <a href="index.php" class="footer__title link-reset">Gablota.pl</a>
        <div class="footer__icons">
          <a href="https://www.facebook.com/" target="_blank" class="link-reset footer__icons-icon"><img
              src="assets/icon/facebook-square-brands.svg" alt="Facebook link icon"></a>
          <a href="https://twitter.com/" target="_blank" class="link-reset footer__icons-icon"><img
              src="assets/icon/twitter-square-brands.svg" alt="Twitter link icon"></a>
          <a href="https://www.youtube.com/" target="_blank" class="link-reset footer__icons-icon"><img
              src="assets/icon/youtube-square-brands.svg" alt="Youtube link icon"></a>
        </div>
      </div>
      <div class="footer__nav">
        <ul class="list-reset footer__nav-ul">
          <li><a class="footer__nav-link link-reset" href="addoffer.php">Dodaj ogłosznie</a></li>
          <li><a class="footer__nav-link link-reset" href="profile.php">Moje konto</a></li>
          <li><a class="footer__nav-link link-reset" href="profile.php">Moje ogłosznia</a></li>
          <li><a class="footer__nav-link link-reset" href="singin.php">Zaloguj/Wyloguj</a></li>
        </ul>
      </div>
    </div>
    <a href="https://lokeshdhakar.com/projects/lightbox2/" class="lightbox link-reset">LokeshDhakar - Lightbox</a>
  </footer>
</body>

</html>