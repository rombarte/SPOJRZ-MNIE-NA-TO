<?php
	// CO POKAZAĆ? NIC //

	// Definiuję podstawowe zmienne tekstowe
	$nazwa_aplikacji = 'Spójrz mnie na to';
	$rozkaz_menu = 'Przeczytaj regulamin korzystania z programu';
	$tekst_regulaminu = '<h3>Użytkownikowi nie wolno:</h3><li>Próbować robić niedozwolone rzeczy</li><li>Próbować hackować w każdym tego słowa znaczeniu</li><h3>Użytkownikowi wolno:</h3><li>Używać skryptu w sposób w pełni zgodny z obowiązującym prawem</li><li>Cieszyć się każdą chwilą obcowania z tym cudem nauki</li>';
	$tekst_stopki = 'Wszelkie prawa zastrzeżone. Autorem projektu jest Bartłomiej Romanek';
	
	$szablon = file_get_contents('szablon/regulamin.html');
	
	// Wyświetlam stronę z szablonu
	$szablon = preg_replace('/{NazwaAplikacji}/', $nazwa_aplikacji, $szablon);
	$szablon = preg_replace('/{RozkazMenu}/', $rozkaz_menu, $szablon);
	
	$szablon = preg_replace('/{BLOK:REGULAMIN}/', '<div>', $szablon);
	$szablon = preg_replace('/{TekstRegulaminu}/', $tekst_regulaminu, $szablon);
	$szablon = preg_replace('/{\/BLOK:REGULAMIN}/', '</div>', $szablon);

	$szablon = preg_replace('/{TekstStopki}/', $tekst_stopki, $szablon);
	echo $szablon;
?>