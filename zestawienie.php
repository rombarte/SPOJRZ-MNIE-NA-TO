<?php
	// CO POKAZAĆ? SELECT IN SELECT + UNION + INNER JOIN //
	
	// Startuję sesję potrzebną do sprawdzenia ilości błędnych prób logowania
	session_start();

	// Sprawdzam, czy użytkownik jest zalogowany
	if (!isset($_SESSION['id'])) {
		header("Location: logowanie.php");
	}
	
	require "konfiguracja.dat";
		
	// Utwórz połączenie z bazą danych
	$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);
	mysqli_set_charset($baza_polaczenie, "utf8");
	
	// Sprawdź połączenie z bazą danych
	if (!$baza_polaczenie) {
		header('Location: logowanie.php?failure');
	}
	
	echo "<html><head><meta charset='utf-8'><style>table, th, td {    border: 1px solid black;}</style></head><body>";
	
	if (isset($_GET['type']) && ($_GET['type']) == 'hiperlink') {
		echo "<h1>Zestawienie hiperlink</h1><table style=''>";
	
		// Pobieram listę linków
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT * FROM hiperlink WHERE uzytkownik_id=md5('".$_SESSION['username']."');");
		$zapytanie_wiersz = mysqli_fetch_all($zapytanie_rezultat);
		
		$lista_linkow = '';
		for ($i = 0; $i < count($zapytanie_wiersz); $i++) {
			$lista_linkow = $lista_linkow."<tr><td>".$i."</td><td>http://".$_SERVER['SERVER_NAME']."/?p=".$zapytanie_wiersz[$i][3]."</td><td>".$zapytanie_wiersz[$i][2]."</td></tr>\n";
		}
		echo $lista_linkow;
	}
	else if (isset($_GET['type']) && ($_GET['type']) == 'komputer') {
		echo "<h1>Zestawienie komputer</h1><table style=''>";
	
		// Pobieram listę linków
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT * FROM komputer WHERE (SELECT uzytkownik_ranga FROM uzytkownik WHERE uzytkownik_id=md5('".$_SESSION['username']."'))=1;");
		$zapytanie_wiersz = mysqli_fetch_all($zapytanie_rezultat);
		
		$lista_linkow = '';
		for ($i = 0; $i < count($zapytanie_wiersz); $i++) {
			$lista_linkow = $lista_linkow."<tr><td>".$i."</td><td>".$zapytanie_wiersz[$i][1]."</td><td>".$zapytanie_wiersz[$i][2]."</td><td>".$zapytanie_wiersz[$i][3]."</td></tr>\n";
		}
		echo $lista_linkow;
	}
	else {
		echo "<h1>Zestawienie hiperlink + komputer</h1><table style=''>";
	
		// Pobieram listę linków
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "(SELECT hiperlink_adres, uzytkownik_login FROM hiperlink INNER JOIN uzytkownik ON hiperlink.uzytkownik_id=uzytkownik.uzytkownik_id) UNION (SELECT komputer_adres, komputer_opis FROM komputer WHERE (SELECT uzytkownik_ranga FROM uzytkownik WHERE uzytkownik_id=md5('".$_SESSION['username']."'))=1)");
		$zapytanie_wiersz = mysqli_fetch_all($zapytanie_rezultat);
		
		$lista_linkow = '';
		for ($i = 0; $i < count($zapytanie_wiersz); $i++) {
			$lista_linkow = $lista_linkow."<tr><td>".$i."</td><td>".$zapytanie_wiersz[$i][0]."</td><td>".$zapytanie_wiersz[$i][1]."</td></tr>\n";
		}
		echo $lista_linkow;
	}
	
	echo "</table></body></html>"
?>