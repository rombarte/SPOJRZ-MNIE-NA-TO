<?php

	require "../konfiguracja.dat";
	
	// Utwórz połączenie z bazą danych
	$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);
	mysqli_set_charset($baza_polaczenie, "utf8");
	
	// Sprawdź połączenie z bazą danych
	if (!$baza_polaczenie) {
		header('Location: ../pozwolenie.php');
	}
	
	session_start();
	
	// Wykonaj operacje
	
	if (isset($_POST['odblokuj'])) {
		$uzytkownik_id = mysqli_real_escape_string($baza_polaczenie, $_POST['odblokuj']);
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "UPDATE uzytkownik SET logowanie_status=0 WHERE uzytkownik_id='".$uzytkownik_id."'");
	}
	
	if (isset($_POST['zablokuj'])) {
		$uzytkownik_id = mysqli_real_escape_string($baza_polaczenie, $_POST['zablokuj']);
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "UPDATE uzytkownik SET logowanie_status=3 WHERE uzytkownik_id='".$uzytkownik_id."'");

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