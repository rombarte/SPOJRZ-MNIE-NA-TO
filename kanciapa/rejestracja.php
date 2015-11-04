<?php
	require "../konfiguracja.dat";
	
	// Definiuję podstawowe zmienne tekstowe
	$zawartosc_menu = '<a href="logowanie.php" class="bar-button">Logowanie</a>
			<a href="regulamin.php" class="bar-button">Regulamin</a>';
	$rozkaz_menu = 'Zarejestruj nowe konto, aby rozpocząć zabawę';
	$tekst_powiadomienia_blad = 'Wprowadzone dane są już zajęte! Jeżeli posiadasz już konto, zaloguj się';
	$tekst_powiadomienia_sukces = 'Zostałeś poprawnie zarejestrowany! Poczekaj na akceptację przez administratora.';
	$tekst_stopki = 'Copyright &copy; 2015 Bartłomiej Romanek';
	
	// Startuję sesję potrzebną
	session_start();
	
	$szablon = file_get_contents('szablon/rejestracja.html');
	$zawartosc_formularz = '<form action="uruchom-rejestracja.php" method="post" class="centered">
				<p>Nazwa użytkownika</p>
				<input type="text" name="uzytkownik_nazwa" required>
				<p>Hasło użytkownika</p>
				<input type="password" name="uzytkownik_haslo" required>
				<p>Adres e-mail</p>
				<input type="text" name="uzytkownik_mail" required><br>
				<input type="checkbox" name="uzytkownik_zgoda" required> Akceptuję regulamin korzystania z serwisu<br>
				<input type="submit" value="Załóż konto">
			</form>';

	// Sprawdzam, czy użytkownik jest zalogowany
	if (isset($_SESSION['id'])) {
		header("Location: kanciapa.php");
		exit();
	}
	
	// Wyświetlam stronę z szablonu
	else {
		$szablon = preg_replace('/{NazwaAplikacji}/', $nazwa_aplikacji, $szablon);
		$szablon = preg_replace('/{ZawartoscMenu}/', $zawartosc_menu, $szablon);
		$szablon = preg_replace('/{RozkazMenu}/', $rozkaz_menu, $szablon);
		$szablon = preg_replace('/{ZawartoscFormularz}/', $zawartosc_formularz, $szablon);
		
		if (isset($_GET['status'])) {
			if ($_GET['status'] == 'failure') {
				$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '<div class="message failure centered">', $szablon);
				$szablon = preg_replace('/{TekstPowiadomienia}/', $tekst_powiadomienia_blad, $szablon);
				$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '</div>', $szablon);
				
				$szablon = preg_replace('/{BLOK:FORMULARZ}/', '', $szablon);
				$szablon = preg_replace('/{\/BLOK:FORMULARZ}/', '', $szablon);
			}
			
			if ($_GET['status'] == 'success') {
				$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '<div class="message success centered">', $szablon);
				$szablon = preg_replace('/{TekstPowiadomienia}/', $tekst_powiadomienia_sukces, $szablon);
				$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '</div>', $szablon);
				
				$szablon = preg_replace('/{BLOK:FORMULARZ}/', '<div class="hided">', $szablon);
				$szablon = preg_replace('/{\/BLOK:FORMULARZ}/', '</div>', $szablon);
			}
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