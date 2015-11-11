<?php

	// Sprawdzam, czy poprawne zapytanie
	if (!isset($_GET['p'])) {
		header("Location: pozwolenie.php");
	}
	
	// Wyświetlam stronę z szablonu
	else {
		require "konfiguracja.php";
				
		// Utwórz połączenie z bazą danych
		$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);
		mysqli_set_charset($baza_polaczenie, "utf8");
		
		// Sprawdź połączenie z bazą danych
		if (!$baza_polaczenie) {
			header('Location: pozwolenie.php');
			exit();
		}
		
		$adres_komputera = mysqli_real_escape_string($baza_polaczenie, $_SERVER['REMOTE_ADDR']);
		
		// Wyślij zapytanie o pozwolenie dla komputera
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT komputer_status FROM komputer WHERE komputer_adres='".$adres_komputera."'");
		$zapytanie_wiersz = mysqli_fetch_row($zapytanie_rezultat);
		
		// Weryfikuj pozwolenie dla komputera
		if (count($zapytanie_wiersz) == 1) {
			if ($zapytanie_wiersz[0] == 4 || $zapytanie_wiersz[0] == 5 || $zapytanie_wiersz[0] == 6 || $zapytanie_wiersz[0] == 7);
			else {
				header('Location: pozwolenie.php');
				mysqli_close($baza_polaczenie);
				exit();
			}
		}
		
		// Pobieram link z bazy danych
		$hiperlink = mysqli_real_escape_string($baza_polaczenie, $_GET['p']);
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT hiperlink_adres FROM hiperlink WHERE hiperlink_cel='".$hiperlink."';");
		$zapytanie_wiersz = mysqli_fetch_row($zapytanie_rezultat);

		if (count($zapytanie_wiersz) == 0)
			header("Location: pozwolenie.php");
		
		else {
			$zapytanie_rezultat = mysqli_query($baza_polaczenie, "UPDATE hiperlink SET hiperlink_klikniecia=hiperlink_klikniecia+1 WHERE hiperlink_cel='".$hiperlink."'");
			header("Location: ".$zapytanie_wiersz[0]."");
		}
		
		mysqli_close($baza_polaczenie);
	}
	
?>