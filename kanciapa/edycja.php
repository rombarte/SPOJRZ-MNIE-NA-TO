<?php
	require "../konfiguracja.dat";
	
	// Definiuję podstawowe zmienne tekstowe
	$zawartosc_menu = '<p class="bar-paragraph">Zalogowano jako: {NazwaUzytkownika} (IP: {AdresIP})</p>
			<a href="uruchom-wylogowanie.php" class="bar-button">Wyloguj</a>
			<a href="kanciapa.php" class="bar-button">Kanciapa</a>
			<a href="regulamin.php" class="bar-button">Regulamin</a>';
	$rozkaz_menu = 'Edytuj swoje konto, jeżeli tego potrzebujesz';
	$tekst_powiadomienia_blad = 'Wprowadzone dane są już zajęte!';
	$tekst_powiadomienia_sukces = 'Dane zostały zmienione poprawnie.';
	$tekst_stopki = 'Copyright &copy; 2015 Bartłomiej Romanek';
	
	// Startuję sesję potrzebną
	session_start();
	
	// Sprawdzam, czy użytkownik jest zalogowany
	if (!isset($_SESSION['id'])) {
		header("Location: logowanie.php");
	}
	
	$zawartosc_menu = preg_replace('/{NazwaUzytkownika}/', $_SESSION['username'], $zawartosc_menu);
	$zawartosc_menu = preg_replace('/{AdresIP}/', $_SERVER['REMOTE_ADDR'], $zawartosc_menu);
		
	$szablon = file_get_contents('szablon/edycja.html');
	$zawartosc_formularz = '<form action="uruchom-edycja.php" method="post" class="centered">
				<p>Nazwa użytkownika</p>
				<input type="text" name="uzytkownik_nazwa" placeholder="' . $_SESSION['username'] . '" disabled>
				<p title="Podaj nowe hasło do konta">Hasło użytkownika</p>
				<input type="password" name="uzytkownik_haslo">
				<p title="Podaj nowy adres e-mail">Adres e-mail</p>
				<input type="text" name="uzytkownik_mail">
				<br>
				<input type="checkbox" name="uzytkownik_zgoda" checked disabled> Akceptuję regulamin korzystania z serwisu<br>
				<input type="submit" value="Edytuj konto">
			</form>';

	// Wyświetlam stronę z szablonu
	$szablon = preg_replace('/{NazwaAplikacji}/', $nazwa_aplikacji, $szablon);
	$szablon = preg_replace('/{ZawartoscMenu}/', $zawartosc_menu, $szablon);
	$szablon = preg_replace('/{RozkazMenu}/', $rozkaz_menu, $szablon);
	$szablon = preg_replace('/{ZawartoscFormularz}/', $zawartosc_formularz, $szablon);
	
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
		
?>