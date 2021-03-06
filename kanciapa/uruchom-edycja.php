﻿<?php
	
	require "../konfiguracja.php";
	
	session_start();
	
	// Weryfikacja sesji
	if ($_SESSION['sid'] != session_id()) {
		header("Location: uruchom-wylogowanie.php");
		exit();
	}
	
	// Utwórz połączenie
	$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);
	mysqli_set_charset($baza_polaczenie, "utf8");
	
	// Testuj połączenie
	if (!$baza_polaczenie) {
		header("Location: ../pozwolenie.php");
		exit();
	}
	
	else {
		if ($_POST['uzytkownik_mail'] != ''){
			$uzytkownik_mail = mysqli_real_escape_string($baza_polaczenie, $_POST['uzytkownik_mail']);
			mysqli_query($baza_polaczenie, 'UPDATE uzytkownik SET uzytkownik_email = "'.$uzytkownik_mail.'" WHERE uzytkownik_login="'.$_SESSION['username'].'"');
		}
		
		if ($_POST['uzytkownik_haslo'] != ''){
			$uzytkownik_haslo = mysqli_real_escape_string($baza_polaczenie, $_POST['uzytkownik_haslo']);
			mysqli_query($baza_polaczenie, 'UPDATE uzytkownik SET uzytkownik_password = sha1("'.$uzytkownik_haslo.'") WHERE uzytkownik_login="'.$_SESSION['username'].'"');
	
		}

		if ($_FILES['awatar']['tmp_name'] != ''){
			list($width, $height, $type, $attr) = getimagesize($_FILES['awatar']['tmp_name']);
			
			$uchwyt = fopen($_FILES['awatar']['tmp_name'], "r");
			$rozmiar = $_FILES['awatar']['size'];
			$zawartosc = base64_encode(fread($uchwyt, $rozmiar));

			fclose($uchwyt);
			
			if ($width <= 500 && $height <= 500) {
				$_SESSION['awatar'] = $zawartosc;
				mysqli_query($baza_polaczenie, "UPDATE uzytkownik SET awatar = '" . $zawartosc . "' WHERE uzytkownik_login='" . $_SESSION['username'] . "'");
			}
			else {
				header("Location: edycja.php?status=size");
				exit();
			}
		}
		
		if (mysqli_error($baza_polaczenie)) {
			mysqli_close($baza_polaczenie);
			header("Location: edycja.php?status=failure");
		}
		
		else {
			mysqli_close($baza_polaczenie);
			header("Location: edycja.php?status=success");
		}
		
	}
	
?>