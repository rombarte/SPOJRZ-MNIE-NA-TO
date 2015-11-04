<?php
	
	require "../konfiguracja.dat";
	
	// Utwórz połączenie z bazą danych
	$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);
	mysqli_set_charset($baza_polaczenie, "utf8");
	
	// Sprawdź połączenie z bazą danych
	if (!$baza_polaczenie) {
		header('Location: ../pozwolenie.php');
		exit();
	}
	
	session_start();
	
	// Weryfikacja sesji
	if ($_SESSION['sid'] != session_id()) header("Location: uruchom-wylogowanie.php");
	
	// Wykonaj operacje
	if (isset($_POST['zmien'])){
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT komputer_status FROM komputer WHERE komputer_id='".$_POST['zmien']."';");
		$zapytanie_wiersz = mysqli_fetch_array($zapytanie_rezultat);

		if ($zapytanie_wiersz[0] > 7) {
			$zapytanie_rezultat = mysqli_query($baza_polaczenie, "UPDATE `komputer` SET komputer_status=7 WHERE komputer_id='".$_POST['zmien']."'");
			$zapytanie_wiersz[0] = 7;
		}
		
		if (isset($_POST['opcja'])) {
			if ($_POST['opcja'] == 1) {
				if ($zapytanie_wiersz[0] == 1 || $zapytanie_wiersz[0] == 3 || $zapytanie_wiersz[0] == 5 || $zapytanie_wiersz[0] == 7) {
						$zapytanie_rezultat = mysqli_query($baza_polaczenie, "UPDATE `komputer` SET komputer_status=komputer_status-1 WHERE komputer_id='".$_POST['zmien']."'");
				}
				else $zapytanie_rezultat = mysqli_query($baza_polaczenie, "UPDATE `komputer` SET komputer_status=komputer_status+1 WHERE komputer_id='".$_POST['zmien']."'");
			}
			
			if ($_POST['opcja'] == 2) {
				if ($zapytanie_wiersz[0] == 2 || $zapytanie_wiersz[0] == 3 || $zapytanie_wiersz[0] == 6 || $zapytanie_wiersz[0] == 7) {
						$zapytanie_rezultat = mysqli_query($baza_polaczenie, "UPDATE `komputer` SET komputer_status=komputer_status-2 WHERE komputer_id='".$_POST['zmien']."'");
				}
				else $zapytanie_rezultat = mysqli_query($baza_polaczenie, "UPDATE `komputer` SET komputer_status=komputer_status+2 WHERE komputer_id='".$_POST['zmien']."'");
			}

			if ($_POST['opcja'] == 4) {
				if ($zapytanie_wiersz[0] == 4 || $zapytanie_wiersz[0] == 5 || $zapytanie_wiersz[0] == 6 || $zapytanie_wiersz[0] == 7) {
						$zapytanie_rezultat = mysqli_query($baza_polaczenie, "UPDATE `komputer` SET komputer_status=komputer_status-4 WHERE komputer_id='".$_POST['zmien']."'");
				}
				else $zapytanie_rezultat = mysqli_query($baza_polaczenie, "UPDATE `komputer` SET komputer_status=komputer_status+4 WHERE komputer_id='".$_POST['zmien']."'");
			}
			
			if ($_POST['opcja'] == 5) {
				$zapytanie_rezultat = mysqli_query($baza_polaczenie, "DELETE FROM `komputer` WHERE komputer_id='".$_POST['zmien']."'");
			}

		}
			
	}

	if (isset($_POST['dodaj'])) {
		if (!$_POST['opis']=="") {
			$zapytanie_rezultat = mysqli_query($baza_polaczenie, "INSERT INTO `komputer`(`komputer_id`, `komputer_adres`, `komputer_status`, `komputer_opis`, `komputer_data`) VALUES (md5('".$_POST['dodaj']."'), '".$_POST['dodaj']."', 7, '".$_POST['opis']."', NOW())");
		}
		else {
			$zapytanie_rezultat = mysqli_query($baza_polaczenie, "INSERT INTO `komputer`(`komputer_id`, `komputer_adres`, `komputer_status`, `komputer_opis`, `komputer_data`) VALUES (md5('".$_POST['dodaj']."'), '".$_POST['dodaj']."', 7, 'BRAK OPISU', NOW())");
		}
	}
	
	if (isset($_POST['panic'])) {
		mysqli_query($baza_polaczenie, "CALL panic_start()");
		mysqli_close($baza_polaczenie);
		$_SESSION["powiadomienie"] = 'POPRAWNE WYKONANIE PROCEDURY PANIC';
		header('Location: kanciapa.php?success');
		exit();
	}
	
	
	if (mysqli_error($baza_polaczenie) != '') {
		mysqli_close($baza_polaczenie);
		$_SESSION["powiadomienie"] = "Nie można wykonać tej operacji";
		header('Location: kanciapa.php?failure');
		exit();
	}
	
	else {
		mysqli_close($baza_polaczenie);
		$_SESSION["powiadomienie"] = 'Operacja wykonała się poprawnie';
		header('Location: kanciapa.php?success');
		exit();
	}
	
?>