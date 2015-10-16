<?php

	require "konfiguracja.dat";
	
	// Utwórz połączenie z bazą danych
	$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);
	mysqli_set_charset($baza_polaczenie, "utf8");
	
	// Sprawdź połączenie z bazą danych
	if (!$baza_polaczenie) {
		header('Location: kanciapa.php?failure');
	}
	
	session_start();
	
	// Wykonaj operacje
	if (isset($_POST['usun'])) $zapytanie_rezultat = mysqli_query($baza_polaczenie, "DELETE FROM hiperlink WHERE hiperlink_id='".$_POST['usun']."';");
	if (isset($_POST['hiperlink'])) {
		if (!$_POST['obciety']=="") {
			$zapytanie_rezultat = mysqli_query($baza_polaczenie, "INSERT INTO `hiperlink`(`hiperlink_id`, `uzytkownik_id`, `hiperlink_adres`, `hiperlink_cel`, `hiperlink_status`, `hiperlink_klikniecia`, `hiperlink_data`) VALUES (md5('".$_POST['hiperlink']."'),md5('".$_SESSION['username']."'),CONCAT('http://',TRIM(LEADING 'http://' FROM TRIM(LEADING 'https://' FROM '".$_POST['hiperlink']."'))),'".$_POST['obciety']."',0,0,NOW())");
		}
		else {
			$zapytanie_rezultat = mysqli_query($baza_polaczenie, "INSERT INTO `hiperlink`(`hiperlink_id`, `uzytkownik_id`, `hiperlink_adres`, `hiperlink_cel`, `hiperlink_status`, `hiperlink_klikniecia`, `hiperlink_data`) VALUES (md5('".$_POST['hiperlink']."'),md5('".$_SESSION['username']."'),CONCAT('http://',TRIM(LEADING 'http://' FROM TRIM(LEADING 'https://' FROM '".$_POST['hiperlink']."'))),SUBSTR(CONV(FLOOR(RAND() * 99999999999999), 20, 36), 8),0,0,NOW())");
		}
	}
	mysqli_close($baza_polaczenie);
	
	header('Location: kanciapa.php');
?>