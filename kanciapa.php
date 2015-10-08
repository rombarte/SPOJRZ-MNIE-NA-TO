<?php
	// CO POKAZAĆ? NIC //
	
	// Definiuję podstawowe zmienne tekstowe
	$nazwa_aplikacji = 'Spójrz mnie na to!';
	$rozkaz_menu = 'Witaj w swojej kanciapie';
	$tekst_stopki = 'Wszelkie prawa zastrzeżone. Autorem projektu jest Bartłomiej Romanek';
	
	// Startuję sesję potrzebną do sprawdzenia ilości błędnych prób logowania
	session_start();
	
	$szablon = file_get_contents('szablon/kanciapa.html');

	// Sprawdzam, czy użytkownik jest zalogowany
	if (!isset($_SESSION['id'])) {
		header("Location: logowanie.php?failure");
	}
	
	// Wyświetlam stronę z szablonu
	$szablon = preg_replace('/{NazwaAplikacji}/', $nazwa_aplikacji, $szablon);
	$szablon = preg_replace('/{RozkazMenu}/', $rozkaz_menu, $szablon);
	$szablon = preg_replace('/{NazwaUzytkownika}/', $_SESSION['username'], $szablon);
	$szablon = preg_replace('/{AdresIP}/', $_SERVER['REMOTE_ADDR'], $szablon);
		
	require "konfiguracja.dat";
	
	// Utwórz połączenie z bazą danych
	$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);
	mysqli_set_charset($baza_polaczenie, "utf8");

	// Sprawdź połączenie z bazą danych
	if (!$baza_polaczenie) {
		header('Location: logowanie.php?failure');
	}
	
	// Pobieram listę linków należących do użytkownika
	$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT hiperlink_id,hiperlink_cel,hiperlink_adres,hiperlink_klikniecia FROM hiperlink WHERE uzytkownik_id=md5('".$_SESSION['username']."');");
	$zapytanie_wiersz = mysqli_fetch_all($zapytanie_rezultat);
	
	// Wypełniam listę linków linkami z bazy danych
	$szablon = preg_replace('/{BLOK:LINKLISTA}/', '', $szablon);
	$lista_linkow='';
	for ($i = 0; $i < count($zapytanie_wiersz); $i++) {
		$lista_linkow = $lista_linkow."<tr><td>".$i."</td><td>http://".$_SERVER['SERVER_NAME']."/?p=".$zapytanie_wiersz[$i][1]."</td><td>".$zapytanie_wiersz[$i][2]."</td><td>".$zapytanie_wiersz[$i][3]."</td><td><form action='uruchom-kanciapa-hiperlink.php' method='post'><input type='hidden' name='usun' value='".$zapytanie_wiersz[$i][0]."' /><input type='submit' value='Usuń' /></form></td></tr>\n";
	}
	
	if ($_SESSION['username']=='rombarte'){
		// Pobieram listę komputerów znajdujących się w bazie
		$szablon = preg_replace('/{BLOK:KOMPUTERLISTA}/', '', $szablon);
		$szablon = preg_replace('/{\/BLOK:KOMPUTERLISTA}/', '', $szablon);
		
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT * FROM komputer WHERE (SELECT uzytkownik_ranga FROM uzytkownik WHERE uzytkownik_id=md5('".$_SESSION['username']."'))=1");
		$zapytanie_wiersz = mysqli_fetch_all($zapytanie_rezultat);
		
		// Wypełniam listę komputerami z bazy danych
		$info_przyciski=array();
		for ($i = 0; $i < count($zapytanie_wiersz); $i++) {
			$info_przyciski[$i] = $zapytanie_wiersz[$i][3];
		}
		
		$lista_komputerow='';
		for ($i = 0; $i < count($zapytanie_wiersz); $i++) {
			$lista_komputerow = $lista_komputerow."<tr><td>".$i."</td><td>".$zapytanie_wiersz[$i][1]."</td><td>".$zapytanie_wiersz[$i][2]."</td><td>".$zapytanie_wiersz[$i][3]."</td><td>".$zapytanie_wiersz[$i][4]."</td><td><form action='uruchom-kanciapa-komputer.php' method='post'><input type='hidden' name='zmien' value='".$zapytanie_wiersz[$i][0]."' /><input type='hidden' name='opcja' value='1' /><input type='submit' value='Zmień' /></form></td><td><form action='uruchom-kanciapa-komputer.php' method='post'><input type='hidden' name='zmien' value='".$zapytanie_wiersz[$i][0]."' /><input type='hidden' name='opcja' value='2' /><input type='submit' value='Zmień' /></form></td><td><form action='uruchom-kanciapa-komputer.php' method='post'><input type='hidden' name='zmien' value='".$zapytanie_wiersz[$i][0]."' /><input type='hidden' name='opcja' value='4' /><input type='submit' value='Zmień' /></form></td></tr>\n";
		}
	}
	
	else {
		$szablon = preg_replace('/{BLOK:KOMPUTERLISTA}/', '<div style="display: none">', $szablon);
		$lista_komputerow='';
		$szablon = preg_replace('/{ListaKomputerow}/', $lista_komputerow, $szablon);
		$szablon = preg_replace('/{\/BLOK:KOMPUTERLISTA}/', '</div>', $szablon);
	}
	
	mysqli_close($baza_polaczenie);
		
	$szablon = preg_replace('/{ListaLinkow}/', $lista_linkow, $szablon);
	$szablon = preg_replace('/{ListaKomputerow}/', $lista_komputerow, $szablon);
	$szablon = preg_replace('/{\/BLOK:LINKLISTA}/', '', $szablon);

	$szablon = preg_replace('/{TekstStopki}/', $tekst_stopki, $szablon);
	echo $szablon;
?>