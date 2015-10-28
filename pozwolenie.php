<?php
	require "konfiguracja.dat";
	
	// Definiuję podstawowe zmienne tekstowe
	$zawartosc_menu = '<a href="mailto:poczta@rombarte.pl" class="bar-button">Kontakt</a>';
	$rozkaz_menu = 'Brak dostępu do żądanego zasobu';
	$tekst_powiadomienia = 'Błąd bazy danych, brak żądanego zasobu lub nie masz uprawnień do jego przeglądania';
	$tekst_stopki = 'Copyright &copy; 2015 Bartłomiej Romanek';
	
	$szablon = file_get_contents('szablon/pozwolenie.html');

	// Wyświetlam stronę z szablonu
	$szablon = preg_replace('/{NazwaAplikacji}/', $nazwa_aplikacji, $szablon);
	$szablon = preg_replace('/{ZawartoscMenu}/', $zawartosc_menu, $szablon);
	$szablon = preg_replace('/{RozkazMenu}/', $rozkaz_menu, $szablon);
	
	$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '<div class="message failure centered">', $szablon);
	$szablon = preg_replace('/{TekstPowiadomienia}/', $tekst_powiadomienia, $szablon);
	$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '</div>', $szablon);
	
	$szablon = preg_replace('/{BLOK:FORMULARZ}/', '', $szablon);
	$szablon = preg_replace('/{\/BLOK:FORMULARZ}/', '', $szablon);

	$szablon = preg_replace('/{TekstStopki}/', $tekst_stopki, $szablon);
	echo $szablon;

?>