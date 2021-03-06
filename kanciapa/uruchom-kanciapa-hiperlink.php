﻿<?php

	require "../konfiguracja.php";
	
	// Utwórz połączenie z bazą danych
	$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);
	mysqli_set_charset($baza_polaczenie, "utf8");
	
	// Sprawdź połączenie z bazą danych
	if (!$baza_polaczenie) {
		header('Location: ../pozwolenie.php');
	}
	
	session_start();
	
	// Weryfikacja sesji
	if ($_SESSION['sid'] != session_id()) {
		header("Location: uruchom-wylogowanie.php");
		exit();
	}
	
	// Wykonaj operacje
	if (isset($_POST['usun'])) $zapytanie_rezultat = mysqli_query($baza_polaczenie, "DELETE FROM hiperlink WHERE hiperlink_id='".$_POST['usun']."';");
	if (isset($_POST['hiperlink'])) {
		if (!$_POST['obciety']=="") {
			$zapytanie_rezultat = mysqli_query($baza_polaczenie, "INSERT INTO `hiperlink`(`hiperlink_id`, `uzytkownik_id`, `hiperlink_adres`, `hiperlink_cel`, `hiperlink_status`, `hiperlink_klikniecia`, `hiperlink_data`) VALUES (md5('".strtolower($_POST['obciety'])."'),md5('".$_SESSION['username']."'),CONCAT('http://',TRIM(LEADING 'http://' FROM TRIM(LEADING 'https://' FROM '".strtolower($_POST['hiperlink'])."'))),'".strtolower($_POST['obciety'])."',0,0,NOW())");
		}
		else {
			mysqli_query($baza_polaczenie, "SET @losowaLiczba = (SELECT RAND())");
			$zapytanie_rezultat = mysqli_query($baza_polaczenie, "INSERT INTO `hiperlink`(`hiperlink_id`, `uzytkownik_id`, `hiperlink_adres`, `hiperlink_cel`, `hiperlink_status`, `hiperlink_klikniecia`, `hiperlink_data`) VALUES (md5(LCASE(SUBSTR(CONV(FLOOR(@losowaLiczba * 99999999999999), 20, 36), 8))),md5('".$_SESSION['username']."'),CONCAT('http://',TRIM(LEADING 'http://' FROM TRIM(LEADING 'https://' FROM '".strtolower($_POST['hiperlink'])."'))),LCASE(SUBSTR(CONV(FLOOR(@losowaLiczba * 99999999999999), 20, 36), 8)),0,0,NOW())");
		}
	}
	
	if (mysqli_error($baza_polaczenie) != '') {
		mysqli_close($baza_polaczenie);
		$_SESSION["powiadomienie"] = "Nie można wykonać tej operacji";
		header('Location: kanciapa.php?failure');
	}
	
	else {
		mysqli_close($baza_polaczenie);
		$_SESSION["powiadomienie"] = 'Operacja wykonała się poprawnie';
		header('Location: kanciapa.php?success');
	}
	
?>