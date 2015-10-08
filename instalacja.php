<?php

	// Definiuję podstawowe zmienne tekstowe
	$nazwa_aplikacji = 'Spójrz mnie na to';
	$zawartosc_menu = '<a href="mailto:poczta@rombarte.pl" class="bar-button">Kontakt</a>';
	$rozkaz_menu = 'Zainstaluj skrypt na swoim serwerze';
	$tekst_powiadomienia_niepowodzenie = 'Wprowadzone dane uniemożliwiają połączenie się z bazą danych';
	$tekst_powiadomienia_sukces = 'Skrypt został poprawnie zainstalowany na twoim serwerze. <a href="logowanie.php">Zaloguj się</a>';
	$tekst_stopki = 'Copyright &copy; 2015 Bartłomiej Romanek';
	
	// Wczytuję szablon z pliku
	$szablon = file_get_contents('szablon/instalacja.html');
	$zawartosc_formularz = '<form action="uruchom-instalacja.php" method="post" class="centered">
					<p>Nazwa serwera </p>
					<input type="text" name="baza_serwer" required>
					<p>Nazwa użytkownika</p>
					<input type="text" name="baza_uzytkownik" required>
					<p>Hasło użytkownika</p>
					<input type="text" name="baza_haslo" required>
					<p>Nazwa bazy danych</p>
					<input type="text" name="baza_nazwa" required><br>
					<input type="submit" value="Zainstaluj">
				</form>';
	
	// Wyświetlam stronę z szablonu
	$szablon = preg_replace('/{NazwaAplikacji}/', $nazwa_aplikacji, $szablon);
	$szablon = preg_replace('/{ZawartoscMenu}/', $zawartosc_menu, $szablon);
	$szablon = preg_replace('/{RozkazMenu}/', $rozkaz_menu, $szablon);
	$szablon = preg_replace('/{ZawartoscFormularz}/', $zawartosc_formularz, $szablon);
	
	if (isset($_GET['failure'])) {
		$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '<div class="message failure centered">', $szablon);
		$szablon = preg_replace('/{TekstPowiadomienia}/', $tekst_powiadomienia_niepowodzenie, $szablon);
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