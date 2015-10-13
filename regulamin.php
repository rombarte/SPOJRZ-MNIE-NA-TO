<?php

	// Definiuję podstawowe zmienne tekstowe
	$nazwa_aplikacji = 'Spójrz mnie na to';
    $zawartosc_menu = '<a href="rejestracja.php" class="bar-button">Rejestracja</a>
			<a href="logowanie.php" class="bar-button">Logowanie</a>';
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