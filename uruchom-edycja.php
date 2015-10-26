<?php
	
	require "konfiguracja.dat";
	
	session_start();
	
	// Utwórz połączenie
	$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);
	mysqli_set_charset($baza_polaczenie, "utf8");
	
	// Testuj połączenie
	if (!$baza_polaczenie) {
		header("Location: pozwolenie.php");
	}
	
	else {
		mysqli_query($baza_polaczenie, 'UPDATE uzytkownik SET uzytkownik_email = "'.$_POST['uzytkownik_mail'].'" WHERE uzytkownik_login="'.$_SESSION['username'].'"');
		mysqli_query($baza_polaczenie, 'UPDATE uzytkownik SET uzytkownik_password = sha1("'.$_POST['uzytkownik_haslo'].'") WHERE uzytkownik_login="'.$_SESSION['username'].'"');
		
		
		
	header("Location: edycja.php?success");
	}
?>