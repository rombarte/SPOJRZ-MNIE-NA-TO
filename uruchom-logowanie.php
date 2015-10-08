<?php
	// CO POKAZAĆ? SELECT FROM VIEW, UPDATE VIEW + REAL ESCAPE STRING //

	require "konfiguracja.dat";
	session_start();

	// Utwórz połączenie z bazą danych
	$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);

	// Sprawdź połączenie z bazą danych
	if (!$baza_polaczenie) {
		header('Location: logowanie.php?failure');
	}
	
	// Dane pobrane poprzez POST
	$uzytkownik_login = mysqli_real_escape_string($baza_polaczenie, $_POST['uzytkownik_nazwa']);
	$uzytkownik_haslo = mysqli_real_escape_string($baza_polaczenie, $_POST['uzytkownik_haslo']);
	
	// Wyślij zapytanie o poprawność adresu komputera
	$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT komputer_status FROM komputer WHERE komputer_adres='".$_SERVER['REMOTE_ADDR']."'");
	$zapytanie_wiersz = mysqli_fetch_row($zapytanie_rezultat);
	mysqli_query($baza_polaczenie, "UPDATE `komputer` SET komputer_data=NOW() WHERE komputer_adres='".$_SERVER['REMOTE_ADDR']."'");
	
	if (count($zapytanie_wiersz) == 1) {
		if ($zapytanie_wiersz[0] == 1 || $zapytanie_wiersz[0] == 3 || $zapytanie_wiersz[0] == 5 || $zapytanie_wiersz[0] == 7);
		else {
			header('Location: logowanie.php?failure&adress');
			mysqli_close($baza_polaczenie);
			exit();
		}
	}
	
	// Wyślij zapytanie o poprawność danych logowania
	$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT uzytkownik_id FROM widok_logowanie WHERE uzytkownik_login='".$uzytkownik_login."' and uzytkownik_haslo=sha1('".$uzytkownik_haslo."')");
	$zapytanie_wiersz = mysqli_fetch_row($zapytanie_rezultat);
	
	if (count($zapytanie_wiersz) == 1) {
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT uzytkownik_id, logowanie_status FROM widok_logowanie WHERE uzytkownik_login='".$uzytkownik_login."'");
		$zapytanie_wiersz = mysqli_fetch_row($zapytanie_rezultat);
		
		if ($zapytanie_wiersz[1] > 2) {
			header('Location: logowanie.php?failure');
		}
		else {
			$_SESSION['id'] = $zapytanie_wiersz[0];
			$_SESSION['username'] = $uzytkownik_login;
			mysqli_query($baza_polaczenie, "UPDATE widok_logowanie SET logowanie_status=0 WHERE uzytkownik_login='".$uzytkownik_login."'");
			mysqli_query($baza_polaczenie, "INSERT INTO uzytkownik_komputer (uzytkownik_komputer_id1, uzytkownik_komputer_id2) VALUES((SELECT uzytkownik_id FROM uzytkownik WHERE uzytkownik_login='".$uzytkownik_login."'),(SELECT komputer_id FROM komputer WHERE komputer_adres='".$_SERVER['REMOTE_ADDR']."'))");
			header('Location: kanciapa.php');
		}
	}
	else {
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT uzytkownik_id FROM widok_logowanie WHERE uzytkownik_login='".$uzytkownik_login."'");
		$zapytanie_wiersz = mysqli_fetch_row($zapytanie_rezultat);
		if (count($zapytanie_wiersz) == 1) {
			$zapytanie_rezultat = mysqli_query($baza_polaczenie, "UPDATE widok_logowanie SET logowanie_status=logowanie_status+1 WHERE uzytkownik_login='".$uzytkownik_login."'");
			header('Location: logowanie.php?failure');
		}
		else {
			header('Location: logowanie.php?failure');
		}
	}
	mysqli_close($baza_polaczenie);
?>