<?php
	
	require "konfiguracja.dat";

	// Utwórz połączenie
	$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);
	mysqli_set_charset($baza_polaczenie, "utf8");
	
	// Testuj połączenie
	if (!$baza_polaczenie) {
		die("Błąd połączenia z serwerem: ".mysqli_connect_error());
	}
	
	// Wyślij zapytanie o poprawność adresu komputera
	$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT komputer_status FROM komputer WHERE komputer_adres='".$_SERVER['REMOTE_ADDR']."'");
	
	$zapytanie_wiersz = mysqli_fetch_row($zapytanie_rezultat);
		
	if (count($zapytanie_wiersz) == 1) {
		if ($zapytanie_wiersz[0] == 2 || $zapytanie_wiersz[0] == 3 || $zapytanie_wiersz[0] == 6 || $zapytanie_wiersz[0] == 7);
		else {
			header('Location: logowanie.php?failure&adress');
			mysqli_close($baza_polaczenie);
			exit();
		}
	}
		
	// Wyślij zapytanie o istniejące dane
	$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT uzytkownik_email FROM uzytkownik WHERE uzytkownik_login='".$_POST['uzytkownik_nazwa']."' or uzytkownik_email='".$_POST['uzytkownik_mail']."'");
	
	$zapytanie_wiersz = mysqli_fetch_row($zapytanie_rezultat);
	if (count($zapytanie_wiersz) > 0) {
	
		header("Location: rejestracja.php?mistake");
	}
	else {
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "INSERT INTO uzytkownik (uzytkownik_id, uzytkownik_login, uzytkownik_haslo, uzytkownik_email, uzytkownik_ranga, uzytkownik_opis) VALUES (md5('".$_POST['uzytkownik_nazwa']."'),'".$_POST['uzytkownik_nazwa']."',sha1('".$_POST['uzytkownik_haslo']."'),'".$_POST['uzytkownik_mail']."',2,'Zwykły, szary użytkownik');");
		header("Location: rejestracja.php?success");
	}
	mysqli_close($baza_polaczenie);
?>