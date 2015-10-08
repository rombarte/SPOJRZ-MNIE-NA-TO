<?php
	// CO POKAZAĆ? SELECT, UPDATE //

	if (!isset($_GET['p'])) {
		header("Location: logowanie.php");
	}
	
	// Wyświetlam stronę z szablonu
	else {
		require "konfiguracja.dat";
		
		// Utwórz połączenie z bazą danych
		$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);

		// Sprawdź połączenie z bazą danych
		if (!$baza_polaczenie) {
			header('Location: logowanie.php');
		}
		
		// Wyślij zapytanie o poprawność adresu komputera
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT komputer_status FROM komputer WHERE komputer_adres='".$_SERVER['REMOTE_ADDR']."'");
		
		$zapytanie_wiersz = mysqli_fetch_row($zapytanie_rezultat);
		
		echo $_SERVER['REMOTE_ADDR'];
		
		// Weryfikuj poprawność komputera
		if (count($zapytanie_wiersz) == 1) {
			if ($zapytanie_wiersz[0] == 4 || $zapytanie_wiersz[0] == 5 || $zapytanie_wiersz[0] == 6 || $zapytanie_wiersz[0] == 7);
			else {
				header('Location: logowanie.php?failure&adress');
				mysqli_close($baza_polaczenie);
				exit();
			}
		}
		
		// Pobieram link z bazy danych
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT hiperlink_adres FROM hiperlink WHERE hiperlink_cel='".$_GET['p']."';");
	
		$zapytanie_wiersz = mysqli_fetch_row($zapytanie_rezultat);

		if (count($zapytanie_wiersz) == 0)
			header("Location: logowanie.php?failure&database");
		
		else {
			$zapytanie_rezultat = mysqli_query($baza_polaczenie, "UPDATE hiperlink SET hiperlink_klikniecia=hiperlink_klikniecia+1 WHERE hiperlink_cel='".$_GET['p']."'");
			header("Location: ".$zapytanie_wiersz[0]."");
		}
	}
	
	mysqli_close($baza_polaczenie);
?>
