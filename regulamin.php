﻿<?php
	session_start();
	
	// Definiuję podstawowe zmienne tekstowe
	$nazwa_aplikacji = 'Spójrz mnie na to!';
	if (isset($_SESSION['id'])) {
		$zawartosc_menu = '<p class="bar-paragraph">Zalogowano jako: {NazwaUzytkownika} (IP: {AdresIP})</p>
			<a href="uruchom-wylogowanie.php" class="bar-button">Wyloguj</a>
			<a href="edycja.php" class="bar-button">Profil</a>
			<a href="kanciapa.php" class="bar-button">Kanciapa</a>';
		$zawartosc_menu = preg_replace('/{NazwaUzytkownika}/', $_SESSION['username'], $zawartosc_menu);
		$zawartosc_menu = preg_replace('/{AdresIP}/', $_SERVER['REMOTE_ADDR'], $zawartosc_menu);
	}
	else {
		$zawartosc_menu = '<a href="rejestracja.php" class="bar-button">Rejestracja</a>
			<a href="logowanie.php" class="bar-button">Logowanie</a>';
	}
	$rozkaz_menu = 'Przeczytaj regulamin korzystania z programu';
	$tekst_regulaminu = '<h1>Użytkownikowi nie wolno:</h1>
    <li>Próbować robić niedozwolone rzeczy</li>
    <li>Próbować łamać zabezpieczenia tego serwisu</li>
    <li>Śmiać się z niedoskonałości tego portalu</li>
    <h1>Użytkownikowi ma prawo:</h1>
    <li>Korzystać z tego serwisu w należyty sposób</li>
    <li>Cieszyć się każdą chwilą obcowania z tym produktem</li>
    <li>Liczyć na to, że jego komputer nie wybuchnie</li>';
	$tekst_stopki = 'Copyright &copy; 2015 Bartłomiej Romanek';
	
	$szablon = file_get_contents('szablon/regulamin.html');
	
	// Wyświetlam stronę z szablonu
	$szablon = preg_replace('/{NazwaAplikacji}/', $nazwa_aplikacji, $szablon);
    $szablon = preg_replace('/{ZawartoscMenu}/', $zawartosc_menu, $szablon);
	$szablon = preg_replace('/{RozkazMenu}/', $rozkaz_menu, $szablon);
	
	$szablon = preg_replace('/{BLOK:REGULAMIN}/', '<div>', $szablon);
	$szablon = preg_replace('/{TekstRegulaminu}/', $tekst_regulaminu, $szablon);
	$szablon = preg_replace('/{\/BLOK:REGULAMIN}/', '</div>', $szablon);

	$szablon = preg_replace('/{TekstStopki}/', $tekst_stopki, $szablon);
	echo $szablon;
?>