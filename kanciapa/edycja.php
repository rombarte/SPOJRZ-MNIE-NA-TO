<?php
	require "../konfiguracja.php";
	
	// Definiuję podstawowe zmienne tekstowe
	$zawartosc_menu = '<img src="data:image/jpg;base64,{Awatar}" />
			<p class="bar-paragraph">Zalogowano jako: {NazwaUzytkownika} (IP: {AdresIP})</p>
			<a href="uruchom-wylogowanie.php" class="bar-button">Wyloguj</a>
			<a href="kanciapa.php" class="bar-button">Kanciapa</a>
			<a href="regulamin.php" class="bar-button">Regulamin</a>';
	$rozkaz_menu = 'Edytuj swoje konto, jeżeli tego potrzebujesz';
	$tekst_powiadomienia_blad = 'Wprowadzone dane są już zajęte!';
	$tekst_powiadomienia_rozmiar = 'Obrazek jest zbyt duży!';
	$tekst_powiadomienia_sukces = 'Dane zostały zmienione poprawnie.';
	$zawartosc_stopki = '<p>Copyright &copy; 2015 Bartłomiej Romanek<p>
			<div>
				<a href="#" onclick="tekstPomniejsz();">- Tekst</a>
				<a href="#" onclick="tekstPrzywroc();">Tekst</a>
				<a href="#" onclick="tekstPowieksz();">Tekst+ </a>
			</div>';
	
	session_start();
	
	// Weryfikacja sesji
	if ($_SESSION['sid'] != session_id()) {
		header("Location: uruchom-wylogowanie.php");
		exit();
	}
	
	// Sprawdzam, czy użytkownik jest zalogowany
	if (!isset($_SESSION['id'])) {
		header("Location: logowanie.php");
		exit();
	}
	
	if ($_SESSION['awatar'] != '') $zawartosc_menu = preg_replace('/{Awatar}/', $_SESSION['awatar'], $zawartosc_menu);
	else $zawartosc_menu = preg_replace('/{Awatar}/', 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNg+M9QDwADgQF/e5IkGQAAAABJRU5ErkJggg==', $zawartosc_menu);
	
	$zawartosc_menu = preg_replace('/{NazwaUzytkownika}/', $_SESSION['username'], $zawartosc_menu);
	$zawartosc_menu = preg_replace('/{AdresIP}/', $_SERVER['REMOTE_ADDR'], $zawartosc_menu);
		
	$szablon = file_get_contents('szablon/edycja.html');
	$zawartosc_formularz = '<form action="uruchom-edycja.php" enctype="multipart/form-data" method="post" class="centered">
				<p>Nazwa użytkownika</p>
				<input type="text" name="uzytkownik_nazwa" placeholder="' . $_SESSION['username'] . '" disabled>
				<p title="Podaj nowe hasło do konta">Hasło użytkownika</p>
				<input type="password" name="uzytkownik_haslo">
				<p title="Podaj nowy adres e-mail">Adres e-mail</p>
				<input type="text" name="uzytkownik_mail">
				<p title="Maksymalny rozmiar obrazka: 500x500">Plik graficzny awatara</p>
				<input type="file" name="awatar">
				<br>
				<input type="checkbox" name="uzytkownik_zgoda" checked disabled> Akceptuję regulamin korzystania z serwisu<br>
				<input type="submit" value="Edytuj konto">
			</form>';

	// Wyświetlam stronę z szablonu
	$szablon = preg_replace('/{NazwaAplikacji}/', $nazwa_aplikacji, $szablon);
	$szablon = preg_replace('/{ZawartoscMenu}/', $zawartosc_menu, $szablon);
	$szablon = preg_replace('/{RozkazMenu}/', $rozkaz_menu, $szablon);
	
	if (isset($_GET['status'])) {
		if ($_GET['status'] == 'failure') {
			$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '<div class="message failure centered">', $szablon);
			$szablon = preg_replace('/{TekstPowiadomienia}/', $tekst_powiadomienia_blad, $szablon);
			$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '</div>', $szablon);
			
			$szablon = preg_replace('/{BLOK:FORMULARZ}/', '', $szablon);
			$szablon = preg_replace('/{ZawartoscFormularz}/', $zawartosc_formularz, $szablon);
			$szablon = preg_replace('/{\/BLOK:FORMULARZ}/', '', $szablon);
		}
		
		else if ($_GET['status'] == 'success') {
			$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '<div class="message success centered">', $szablon);
			$szablon = preg_replace('/{TekstPowiadomienia}/', $tekst_powiadomienia_sukces, $szablon);
			$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '</div>', $szablon);
			
			$szablon = preg_replace('/{BLOK:FORMULARZ}/', '', $szablon);
			$szablon = preg_replace('/{ZawartoscFormularz}/', '', $szablon);
			$szablon = preg_replace('/{\/BLOK:FORMULARZ}/', '', $szablon);
		}
		
		else if ($_GET['status'] == 'size') {
			$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '<div class="message failure centered">', $szablon);
			$szablon = preg_replace('/{TekstPowiadomienia}/', $tekst_powiadomienia_rozmiar, $szablon);
			$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '</div>', $szablon);
			
			$szablon = preg_replace('/{BLOK:FORMULARZ}/', '', $szablon);
			$szablon = preg_replace('/{ZawartoscFormularz}/', $zawartosc_formularz, $szablon);
			$szablon = preg_replace('/{\/BLOK:FORMULARZ}/', '', $szablon);
		}
	}
	
	else {
		$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '', $szablon);
		$szablon = preg_replace('/{TekstPowiadomienia}/', '', $szablon);
		$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '', $szablon);
		
		$szablon = preg_replace('/{BLOK:FORMULARZ}/', '', $szablon);
		$szablon = preg_replace('/{ZawartoscFormularz}/', $zawartosc_formularz, $szablon);
		$szablon = preg_replace('/{\/BLOK:FORMULARZ}/', '', $szablon);
	}
	
	$szablon = preg_replace('/{ZawartoscStopki}/', $zawartosc_stopki, $szablon);
	echo $szablon;
		
?>