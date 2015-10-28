<?php
	
	require "../konfiguracja.dat";

	// Utwórz połączenie
	$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);
	mysqli_set_charset($baza_polaczenie, "utf8");
	
	// Testuj połączenie
	if (!$baza_polaczenie) {
		header('Location: ../pozwolenie.php');
	}
	
	// Wyślij zapytanie o poprawność adresu komputera
	$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT komputer_status FROM komputer WHERE komputer_adres='".$_SERVER['REMOTE_ADDR']."'");
	
	$zapytanie_wiersz = mysqli_fetch_row($zapytanie_rezultat);
		
	if (count($zapytanie_wiersz) == 1) {
		if ($zapytanie_wiersz[0] == 2 || $zapytanie_wiersz[0] == 3 || $zapytanie_wiersz[0] == 6 || $zapytanie_wiersz[0] == 7);
		else {
			header('Location: ../pozwolenie.php');
			mysqli_close($baza_polaczenie);
			exit();
		}
	}
		
	$uzytkownik_nazwa = mysqli_real_escape_string($baza_polaczenie, $_POST['uzytkownik_nazwa']);
	$uzytkownik_haslo = mysqli_real_escape_string($baza_polaczenie, $_POST['uzytkownik_haslo']);
	$uzytkownik_mail = mysqli_real_escape_string($baza_polaczenie, $_POST['uzytkownik_mail']);
	
	// Wyślij zapytanie o istniejące dane
	$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT uzytkownik_email FROM uzytkownik WHERE uzytkownik_login='".$uzytkownik_nazwa."' or uzytkownik_email='".$uzytkownik_mail."'");
	
	$zapytanie_wiersz = mysqli_fetch_row($zapytanie_rezultat);
	if (count($zapytanie_wiersz) > 0) {
		header("Location: rejestracja.php?failure");
	}
	else {
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "INSERT INTO uzytkownik (uzytkownik_id, uzytkownik_login, uzytkownik_haslo, uzytkownik_email, uzytkownik_ranga, uzytkownik_opis) VALUES (md5('".$uzytkownik_nazwa."'),'".$uzytkownik_nazwa."',sha1('".$uzytkownik_haslo."'),'".$uzytkownik_mail."',2,'Zwykły, szary użytkownik');");
		
		/* Poinformuj użytkownika o rejestracji mailem */
		$naglowek = "Content-Type: text/html; charset=UTF-8";
		$wiadomosc = "Szanowny odbiorco wiadomości,
		Właśnie, czyli o godzinie ".date("Y-m-d H:i:s")." został zarejestrowany w serwisie $nazwa_aplikacji użytkownik $uzytkownik_nazwa powołując się na twój e-mail. Jeżeli to pomyłka, prosimy o kontakt.";
		mail($uzytkownik_mail, "Rejestracja konta w serwisie $nazwa_aplikacji", $wiadomosc, $naglowek);
		
		header("Location: rejestracja.php?success");
	}
	mysqli_close($baza_polaczenie);
	
?>