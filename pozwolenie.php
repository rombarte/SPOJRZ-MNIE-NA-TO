<?php

	// Definiuję podstawowe zmienne tekstowe
	$nazwa_aplikacji = 'Spójrz mnie na to';
	$rozkaz_menu = 'Brak dostępu';
	$tekst_powiadomienia = 'Błąd bazy danych, brak strony lub nie masz uprawnień do jej przeglądania';	
	
	$szablon = file_get_contents('szablon/pozwolenie.html');

	// Wyświetlam stronę z szablonu
	$szablon = preg_replace('/{NazwaAplikacji}/', $nazwa_aplikacji, $szablon);
	$szablon = preg_replace('/{RozkazMenu}/', $rozkaz_menu, $szablon);
	
	$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '<div class="message failure centered">', $szablon);
	$szablon = preg_replace('/{TekstPowiadomienia}/', $tekst_powiadomienia, $szablon);
	$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '</div>', $szablon);
	
	$szablon = preg_replace('/{BLOK:FORMULARZ}/', '', $szablon);
	$szablon = preg_replace('/{\/BLOK:FORMULARZ}/', '', $szablon);

	$szablon = preg_replace('/{TekstStopki}/', $tekst_stopki, $szablon);
	echo $szablon;

?>