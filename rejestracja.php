<?php
	// CO POKAZAĆ? NIC //

	// Definiuję podstawowe zmienne tekstowe
	$nazwa_aplikacji = 'Spójrz mnie na to';
	$rozkaz_menu = 'Zarejestruj nowe konto, aby rozpocząć zabawę';
	$tekst_powiadomienia_blad = 'Wprowadzone dane są już zajęte! Jeżeli posiadasz już konto, zaloguj się';
	$tekst_powiadomienia_sukces = 'Zostałeś poprawnie zarejestrowany! Poczekaj na akceptację przez administratora.';
	$tekst_stopki = 'Wszelkie prawa zastrzeżone. Autorem projektu jest Bartłomiej Romanek';
	
	// Startuję sesję potrzebną
	session_start();
	$szablon = file_get_contents('szablon/rejestracja.html');

	// Sprawdzam, czy użytkownik jest zalogowany
	if (isset($_SESSION['id'])) {
		header("Location: kanciapa.php");
	}
	
	// Wyświetlam stronę z szablonu
	else {
		$szablon = preg_replace('/{NazwaAplikacji}/', $nazwa_aplikacji, $szablon);
		$szablon = preg_replace('/{RozkazMenu}/', $rozkaz_menu, $szablon);
		
		if (isset($_GET['failure'])) {
			$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '<div class="message error centered">', $szablon);
			$szablon = preg_replace('/{TekstPowiadomienia}/', $tekst_powiadomienia_blad, $szablon);
			$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '</div>', $szablon);
			
			$szablon = preg_replace('/{BLOK:FORMULARZ}/', '', $szablon);
			$szablon = preg_replace('/{\/BLOK:FORMULARZ}/', '', $szablon);
		}
		
		else if (isset($_GET['success'])) {
			$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '<div class="message success centered">', $szablon);
			$szablon = preg_replace('/{TekstPowiadomienia}/', $tekst_powiadomienia_sukces, $szablon);
			$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '</div>', $szablon);
			
			$szablon = preg_replace('/{BLOK:FORMULARZ}/', '<div class="hided">', $szablon);
			$szablon = preg_replace('/{\/BLOK:FORMULARZ}/', '</div>', $szablon);
		}
		
		else {
			$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '', $szablon);
			$szablon = preg_replace('/{TekstPowiadomienia}/', '', $szablon);
			$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '', $szablon);
			
			$szablon = preg_replace('/{BLOK:FORMULARZ}/', '', $szablon);
			$szablon = preg_replace('/{\/BLOK:FORMULARZ}/', '', $szablon);
		}
		
		$szablon = preg_replace('/{TekstStopki}/', $tekst_stopki, $szablon);
		echo $szablon;
	}
?>