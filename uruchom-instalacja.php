﻿<?php

	// Utwórz połączenie
	$baza_polaczenie = mysqli_connect($_POST['baza_serwer'], $_POST['baza_uzytkownik'], $_POST['baza_haslo'], $_POST['baza_nazwa']);
	mysqli_set_charset($baza_polaczenie, "utf8");
	
	// Testuj połączenie
	if (!$baza_polaczenie) {
		header("Location: instalacja.php?status=failure");
		exit();
	}
	
	else {

		// Utwórz tabele w bazie danych
		mysqli_query($baza_polaczenie, "CREATE TABLE uzytkownik (uzytkownik_id VARCHAR(32) PRIMARY KEY,uzytkownik_email VARCHAR(100) NOT NULL,uzytkownik_login VARCHAR(100) NOT NULL,uzytkownik_haslo CHAR(40) NOT NULL,uzytkownik_ranga INT(8) NOT NULL,uzytkownik_opis VARCHAR(200) NOT NULL,logowanie_status INT(8) NOT NULL,logowanie_data DATETIME NOT NULL, awatar MEDIUMBLOB NOT NULL) DEFAULT CHARACTER SET UTF8,COLLATE utf8_unicode_ci,ENGINE=InnoDB;");
		mysqli_query($baza_polaczenie, "CREATE TABLE komputer (komputer_id VARCHAR(32) PRIMARY KEY,komputer_adres VARCHAR(15) NOT NULL,komputer_opis VARCHAR(200) NOT NULL,komputer_status INT(8) NOT NULL,komputer_data DATETIME NOT NULL) DEFAULT CHARACTER SET UTF8,COLLATE utf8_unicode_ci,ENGINE=InnoDB;");
		mysqli_query($baza_polaczenie, "CREATE TABLE hiperlink (hiperlink_id VARCHAR(32) PRIMARY KEY,uzytkownik_id VARCHAR(32),hiperlink_adres VARCHAR(500) NOT NULL,hiperlink_cel VARCHAR(10) NOT NULL,hiperlink_status INT(8) NOT NULL,hiperlink_klikniecia INT(32) NOT NULL,hiperlink_data DATETIME NOT NULL, FOREIGN KEY (`uzytkownik_id`) REFERENCES uzytkownik (`uzytkownik_id`) ON DELETE CASCADE ON UPDATE CASCADE) DEFAULT CHARACTER SET UTF8,COLLATE utf8_unicode_ci,ENGINE=InnoDB;");
		mysqli_query($baza_polaczenie, "CREATE TABLE uzytkownik_komputer(uzytkownik_komputer_id1 VARCHAR(32) NOT NULL,uzytkownik_komputer_id2 VARCHAR(32) NOT NULL,FOREIGN KEY (`uzytkownik_komputer_id1`) REFERENCES uzytkownik (`uzytkownik_id`) ON DELETE CASCADE ON UPDATE CASCADE,FOREIGN KEY (`uzytkownik_komputer_id2`) REFERENCES komputer (`komputer_id`) ON DELETE CASCADE ON UPDATE CASCADE) DEFAULT CHARACTER SET UTF8,COLLATE utf8_unicode_ci,ENGINE=InnoDB;");
		
		// Wypełnij każdą z tabel rekordami
		mysqli_query($baza_polaczenie, "INSERT INTO uzytkownik (`uzytkownik_id`, `uzytkownik_email`, `uzytkownik_login`, `uzytkownik_haslo`, `uzytkownik_ranga`, `uzytkownik_opis`, `logowanie_status`, `logowanie_data`) VALUES (md5('admin'),'example@mail.com','admin',sha1('admin'),1,'Najlepszy admin',1,NOW());");
		mysqli_query($baza_polaczenie, "INSERT INTO hiperlink (`hiperlink_id`, `uzytkownik_id`,`hiperlink_adres`, `hiperlink_cel`, `hiperlink_status`, `hiperlink_klikniecia`, `hiperlink_data`) VALUES (md5('m52v0'),md5('admin'),'http://rombarte.pl','m52v0',1,0,NOW());");
		mysqli_query($baza_polaczenie, "INSERT INTO hiperlink (`hiperlink_id`,  `uzytkownik_id`, `hiperlink_adres`, `hiperlink_cel`, `hiperlink_status`, `hiperlink_klikniecia`, `hiperlink_data`) VALUES (md5('b7z2n'),md5('admin'),'http://pg.gda.pl','b7z2n',1,0,NOW());");
			
		// Utworz widoki
		mysqli_query($baza_polaczenie, "CREATE VIEW widok_logowanie AS SELECT uzytkownik_id, uzytkownik_login, uzytkownik_haslo, logowanie_status, awatar FROM uzytkownik");
		
		// Utwórz procedury
		mysqli_query($baza_polaczenie, "DELIMITER ;;");
		mysqli_query($baza_polaczenie, "DROP PROCEDURE IF EXISTS panic_start;; ");
		mysqli_query($baza_polaczenie, "CREATE PROCEDURE panic_start() BEGIN UPDATE komputer SET komputer_status=0 WHERE 1; UPDATE uzytkownik SET logowanie_status=3 WHERE uzytkownik_ranga<>1; END;;");
		mysqli_query($baza_polaczenie, "DELIMITER ;");
		
		// Utwórz wyzwalacze
		mysqli_query($baza_polaczenie, "DELIMITER ;;");
		mysqli_query($baza_polaczenie, "CREATE TRIGGER check_komputer_status BEFORE INSERT ON komputer FOR EACH ROW BEGIN IF NEW.komputer_status > 7 THEN SET NEW.komputer_status=7; END IF; END;;");
		mysqli_query($baza_polaczenie, "DELIMITER ;");

		mysqli_close($baza_polaczenie);
		
		// Zapisz plik konfiguracyjny na serwerze
		$plik_konfiguracja = fopen("konfiguracja.php", "w") or die("Nie można zapisać pliku konfiguracyjnego.");
		$plik_zawartosc = '<?php
			$baza_serwer = "'.$_POST['baza_serwer'].'";
			$baza_uzytkownik = "'.$_POST['baza_uzytkownik'].'";
			$baza_haslo = "'.$_POST['baza_haslo'].'";
			$baza_nazwa = "'.$_POST['baza_nazwa'].'";
			$nazwa_aplikacji = "'.$_POST['strona_nazwa'].'";
?>';
		fwrite($plik_konfiguracja, $plik_zawartosc);
		fclose($plik_konfiguracja);
		
		// Usuń pliki umożliwiające instalację skryptu
		//unlink('instalacja.php');
		//unlink('szablon/instalacja.html');
		//unlink('uruchom-instalacja.php');
		
		header("Location: instalacja.php?status=success");
	}
	
?>