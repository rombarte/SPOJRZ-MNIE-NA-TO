<?php
	
	require "../konfiguracja.dat";
	
	session_start();
	
	// Weryfikacja sesji
	if ($_SESSION['sid'] != session_id()) header("Location: uruchom-wylogowanie.php");
	
	// Utwórz połączenie
	$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);
	mysqli_set_charset($baza_polaczenie, "utf8");
	
	// Testuj połączenie
	if (!$baza_polaczenie) {
		header("Location: ../pozwolenie.php");
	}
	
	else {
		if ($_POST['uzytkownik_mail'] != ''){
			$uzytkownik_mail = mysqli_real_escape_string($baza_polaczenie, $_POST['uzytkownik_mail']);
			mysqli_query($baza_polaczenie, 'UPDATE uzytkownik SET uzytkownik_email = "'.$uzytkownik_mail.'" WHERE uzytkownik_login="'.$_SESSION['username'].'"');

		}
		
		if ($_POST['uzytkownik_haslo'] != ''){
			$uzytkownik_haslo = mysqli_real_escape_string($baza_polaczenie, $_POST['uzytkownik_haslo']);
			mysqli_query($baza_polaczenie, 'UPDATE uzytkownik SET uzytkownik_password = sha1("'.$uzytkownik_haslo.'") WHERE uzytkownik_login="'.$_SESSION['username'].'"');
	
		}
		
		header("Location: edycja.php?success");
	}
	
?>