<?php
	require "../konfiguracja.dat";
	
	// Definiuję podstawowe zmienne tekstowe
	$zawartosc_menu = '<p class="bar-paragraph">Zalogowano jako: {NazwaUzytkownika} (IP: {AdresIP})</p>
			<a href="uruchom-wylogowanie.php" class="bar-button">Wyloguj</a>
			<a href="edycja.php" class="bar-button">Profil</a>
			<a href="regulamin.php" class="bar-button">Regulamin</a>';
	$rozkaz_menu = 'Witaj w swojej profesjonalnej kanciapie';
	$naglowek_linki = 'Wyświetl listę utworzonych przez siebie hiperlinków';
	$tekst_powiadomienia = '';
	$tekst_stopki = 'Copyright &copy; 2015 Bartłomiej Romanek';
	
	// Startuję sesję potrzebną do sprawdzenia ilości błędnych prób logowania
	session_start();
	
	if (isset($_SESSION["powiadomienie"])) {
		$tekst_powiadomienia = $_SESSION["powiadomienie"];
		unset($_SESSION["powiadomienie"]);
	}
	
	$szablon = file_get_contents('szablon/kanciapa.html');
	
	// Sprawdzam, czy użytkownik jest zalogowany
	if (!isset($_SESSION['id'])) {
		header("Location: logowanie.php");
	}
	
	// Wyświetlam stronę z szablonu
	$szablon = preg_replace('/{NazwaAplikacji}/', $nazwa_aplikacji, $szablon);
	$zawartosc_menu = preg_replace('/{NazwaUzytkownika}/', $_SESSION['username'], $zawartosc_menu);
	$zawartosc_menu = preg_replace('/{AdresIP}/', $_SERVER['REMOTE_ADDR'], $zawartosc_menu);
	$szablon = preg_replace('/{ZawartoscMenu}/', $zawartosc_menu, $szablon);
	$szablon = preg_replace('/{RozkazMenu}/', $rozkaz_menu, $szablon);
	
	if ($tekst_powiadomienia != '') {
		$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '<div class="message success centered">', $szablon);
		$szablon = preg_replace('/{TekstPowiadomienia}/', $tekst_powiadomienia, $szablon);
		$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '</div>', $szablon);
	}
	
	else {
		$szablon = preg_replace('/{BLOK:POWIADOMIENIE}/', '', $szablon);
		$szablon = preg_replace('/{TekstPowiadomienia}/', '', $szablon);
		$szablon = preg_replace('/{\/BLOK:POWIADOMIENIE}/', '', $szablon);
	}
		
	$szablon = preg_replace('/{NaglowekLinki}/', $naglowek_linki, $szablon);
	$szablon = preg_replace('/{BLOK:LINKI}/', '', $szablon);
	$szablon = preg_replace('/{\/BLOK:LINKI}/', '', $szablon);
	
	$szablon = preg_replace('/{BLOK:KOMPUTERY}/', '', $szablon);
	$szablon = preg_replace('/{\/BLOK:KOMPUTERY}/', '', $szablon);
	
	// Utwórz połączenie z bazą danych
	$baza_polaczenie = mysqli_connect($baza_serwer, $baza_uzytkownik, $baza_haslo, $baza_nazwa);
	mysqli_set_charset($baza_polaczenie, "utf8");

	// Sprawdź połączenie z bazą danych
	if (!$baza_polaczenie) {
		header('Location: ../pozwolenie.php');
	}
	
	// Pobieram listę linków należących do użytkownika
	$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT hiperlink_id,hiperlink_cel,hiperlink_adres,hiperlink_klikniecia FROM hiperlink WHERE uzytkownik_id=md5('".$_SESSION['username']."');");
	$zapytanie_wiersz = mysqli_fetch_all($zapytanie_rezultat);
	
	// Wypełniam listę linków linkami z bazy danych
	$lista_linkow='<table>
				<tr>
					<td>Numer</td>
					<td>Link skrócony</td> 
					<td>Link oryginalny</td>
					<td>Ilość kliknięć</td>
					<td>Opcje</td>
			    </tr>';
	
	$katalog = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	$pozycja = strpos($katalog, '?');
	if ($pozycja > 0) $katalog = substr($katalog, 0, $pozycja);
	$pozycja = strpos($katalog, 'kanciapa.php');
	if ($pozycja > 0) $katalog = substr($katalog, 0, $pozycja);
	$katalog = substr($katalog, 0, -9);
	
	//echo $katalog; die();
	
	
	
	for ($i = 0; $i < count($zapytanie_wiersz); $i++) {
		$lista_linkow = $lista_linkow."<tr><td>".$i."</td><td>http://".$katalog.$zapytanie_wiersz[$i][1]."</td><td>".$zapytanie_wiersz[$i][2]."</td><td>".$zapytanie_wiersz[$i][3]."</td><td><form action='uruchom-kanciapa-hiperlink.php' method='post'><input type='hidden' name='usun' value='".$zapytanie_wiersz[$i][0]."' /><input type='submit' value='Usuń' /></form></td></tr>\n";
	}
	
	$lista_linkow=$lista_linkow.'</table> 
			<table class="noborder">
				<form action="uruchom-kanciapa-hiperlink.php" method="post" accept-charset="UTF-8">
					<tr>
						<td>
							<p>Dodaj link do strony:</p>
							<input type="text" name="hiperlink" required>
						</td>
						<td>
							<p>Własny link (opcjonalnie):</p>
							<input type="text" name="obciety">
						</td> 
						<td>
							<input type="submit" value="Dodaj hiperlink">
						</td>
						<td>
							<input type="button" value="Eksportuj listę" onclick="location.href = \'zestawienie.php?type=hiperlink\';">
						</td>
				    </tr>
			   </form>
			</table>';
	
	if ($_SESSION['username']=='admin'){
		
		$zapytanie_rezultat = mysqli_query($baza_polaczenie, "SELECT * FROM komputer WHERE (SELECT uzytkownik_ranga FROM uzytkownik WHERE uzytkownik_id=md5('".$_SESSION['username']."'))=1");
		$zapytanie_wiersz = mysqli_fetch_all($zapytanie_rezultat);
		
		// Wypełniam listę komputerami z bazy danych
		$info_przyciski=array();
		for ($i = 0; $i < count($zapytanie_wiersz); $i++) {
			$info_przyciski[$i] = $zapytanie_wiersz[$i][3];
		}
		
		$szablon = preg_replace('/{NaglowekKomputery}/', 'Wyświetl listę moderowanych komputerów', $szablon);
		
		$lista_komputerow='<table>
					<tr>
						<td>Numer</td>
						<td>Adres komputera</td> 
						<td>Własny opis</td>
						<td>Status</td>
						<td>Widziany</td>
						<td>Logowanie</td>
						<td>Rejestracja</td>
						<td>Wyświetlanie</td>
						<td>Opcje</td>
					</tr>';
		for ($i = 0; $i < count($zapytanie_wiersz); $i++) {
			$lista_komputerow = $lista_komputerow."<tr><td>".$i."</td><td>".$zapytanie_wiersz[$i][1]."</td><td>".$zapytanie_wiersz[$i][2]."</td><td>".$zapytanie_wiersz[$i][3]."</td><td>".$zapytanie_wiersz[$i][4]."</td>
				
				<td><form action='uruchom-kanciapa-komputer.php' method='post'><input type='hidden' name='zmien' value='".$zapytanie_wiersz[$i][0]."' />
				<input type='hidden' name='opcja' value='1' /><input type='submit' value='Zmień' />
				</form></td>
				
				<td><form action='uruchom-kanciapa-komputer.php' method='post'>
				<input type='hidden' name='zmien' value='".$zapytanie_wiersz[$i][0]."' />
				<input type='hidden' name='opcja' value='2' />
				<input type='submit' value='Zmień' />
				</form></td>
				
				<td><form action='uruchom-kanciapa-komputer.php' method='post'>
				<input type='hidden' name='zmien' value='".$zapytanie_wiersz[$i][0]."' />
				<input type='hidden' name='opcja' value='4' /><input type='submit' value='Zmień' />
				</form></td>
				
				<td><form action='uruchom-kanciapa-komputer.php' method='post'>
				<input type='hidden' name='zmien' value='".$zapytanie_wiersz[$i][0]."' />
				<input type='hidden' name='opcja' value='5' /><input type='submit' value='Usuń' />
				</form></td>
				
				</tr>\n";
		}
		
		$lista_komputerow=$lista_komputerow.'</table> 
				<table class="noborder">
					<form action="uruchom-kanciapa-komputer.php" method="post" accept-charset="UTF-8">
						<tr>
							<td>
								<p>Dodaj adres komputera do listy:</p>
								<input type="text" name="dodaj" required>
							</td>
							<td>
								<p>Własny opis (opcjonalnie):</p>
								<input type="text" name="opis">
							</td> 
							<td>
								<input type="submit" value="Dodaj komputer">
							</td>
							<td>
								<input type="button" value="Eksportuj listę" onclick="location.href = \'zestawienie.php?type=komputer\';">
							</td>
					    </tr>
				   	</form>
				</table>
				<form action="uruchom-kanciapa-komputer.php" method="post" class=\'panic-form\'>
					<input type="hidden" name="panic">
					<input type="submit" value="PANIC BUTTON" id="panic" class="panic" onmouseover="alert(\'Klikając ten przycisk zablokujesz wszystkie widoczne komputery i użytkowników nie będących adminami.\')">
			   	</form>';
	}
	
	// Jeżeli nie jestem adminem, nie wyświetlaj listy komputerów
	else {
		$szablon = preg_replace('/{NaglowekKomputery}/', '', $szablon);
		$lista_komputerow='';
	}
	
	mysqli_close($baza_polaczenie);
		
	$szablon = preg_replace('/{ZawartoscLinki}/', $lista_linkow, $szablon);
	$szablon = preg_replace('/{ZawartoscKomputery}/', $lista_komputerow, $szablon);

	$szablon = preg_replace('/{TekstStopki}/', $tekst_stopki, $szablon);
	echo $szablon;
	
?>