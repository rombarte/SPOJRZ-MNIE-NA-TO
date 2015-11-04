<?php
	require "../konfiguracja.dat";
	
	// Definiuję podstawowe zmienne tekstowe
	$zawartosc_menu = '<a href="rejestracja.php" class="bar-button">Rejestracja</a>
			<a href="regulamin.php" class="bar-button">Regulamin</a>';
	$rozkaz_menu = 'Zaloguj się na swoje konto użytkownika, aby rozpocząć';
	$tekst_powiadomienia_blad = 'Wprowadź poprawne dane logowania! Jeżeli nie masz jeszcze konta, załóż je teraz';
	$tekst_powiadomienia_sukces = 'Zostałeś poprawnie wylogowany! Aby zalogować się ponownie, wpisz dane logowania ponownie';
	$tekst_stopki = 'Copyright &copy; 2015 Bartłomiej Romanek';
	
	// Startuję sesję potrzebną do sprawdzenia ilości błędnych prób logowania
	session_start();
	
	$szablon = file_get_contents('szablon/logowanie.html');
	$zawartosc_formularz = '<form action="uruchom-logowanie.php" method="post" class="centered">
				<p>Nazwa użytkownika</p>
				<input type="text" name="uzytkownik_nazwa" required>
				<p>Hasło użytkownika</p>
				<input type="password" name="uzytkownik_haslo" required><br>
				<input type="submit" value="Zaloguj się">
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
		
		if (isset($_GET['failure'])) {
			$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '<div class="message failure centered">', $szablon);
			$szablon = preg_replace('/{TekstPowiadomienia}/', $tekst_powiadomienia_blad, $szablon);
			$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '</div>', $szablon);
			
			$szablon = preg_replace('/{BLOK:FORMULARZ}/', '', $szablon);
			$szablon = preg_replace('/{\/BLOK:FORMULARZ}/', '', $szablon);
		}
		
		else if (isset($_GET['success'])) {
			$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '<div class="message success centered">', $szablon);
			$szablon = preg_replace('/{TekstPowiadomienia}/', $tekst_powiadomienia_sukces, $szablon);
			$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '</div>', $szablon);
			
			$szablon = preg_replace('/{BLOK:FORMULARZ}/', '', $szablon);
			$szablon = preg_replace('/{\/BLOK:FORMULARZ}/', '', $szablon);
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