<!DOCTYPE html>
<!--
	- Praktikum DBWT. Autoren:
	- Paundra, Daran, 3290902
	- Alejandro, Schmeing, 3203949
-->
<html lang="de">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="index.css">
	<title>Ihre E-Mensa</title>
</head>
<body>

	<?php include "./Gerichte.php"?>

	<?php 
	// Zähler, wie oft Die Seite geladen wurde !! nicht IP abhängig !!
	$count;
	if (file_exists("countVisit.json")) { // Wenn Datei existiert bzw die Seite schonmal geladen wurde
		$count = json_decode(file_get_contents("countVisit.json"), true); // lesen, wie oft die Seite schon geladen wurde
	// sonst:
	} else $count = 0; 
	
	// Zähler erhöhen
	$count ++;
	// und speichern
	file_put_contents("countVisit.json", json_encode($count));
	unset($count);

	// Verbindungsaufbau mit der Datenbank
	$link= new mysqli("localhost", // Host der Datenbank
		"root",                 	  // Benutzername zur Anmeldung
		"root",    					  // Passwort
		"emensawerbeseite"      	  // Auswahl der Datenbanken (bzw. des Schemas)
									  // optional port der Datenbank
	);

	// Falls Verbindung Fehlschlägt: Fehler auswerfen und Skript beenden
	if ($link->connect_error) {
		echo "Verbindung fehlgeschlagen: ", mysqli_connect_error();
		exit();
	}
	?>

	<header>
																				<!-- Kopfzeile mit Links -->
		<div id="Logo">
			<p>E-Mensa Logo</p>
		</div>
		<!-- Links zu Seitenabschnitten -->
		<div id="menuzeile">
			<a href="#ankundigung">Ankündigung</a>
			<a href="#speisen">Speisen</a>
			<a href="#zahlen">Zahlen</a>
			<a href="#kontakt">Kontakt</a>
			<a href="#important">Wichtig für uns</a>
		</div>
																					<!-- Ende Kopfzeile -->
	</header>
	<main>
		<div id="empty_left1">  
																				<!-- Leere Spalte links -->
		</div>
		
																			<!-- Mittlere Spalte für Content -->
		<div id="content">
			<img src="top-pic.jpg" alt="Erstes Bild">

			<h2 id="ankundigung">Bald gibt es Essen auch online ;)</h2>
			<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum reprehenderit enim iste hic fugiat magnam blanditiis nihil eveniet officiis ipsum laborum, minus voluptate dolore atque repudiandae voluptatibus ullam? Beatae praesentium saepe aut repudiandae repellendus, at, reprehenderit recusandae non veniam neque, enim aliquam excepturi dolorem commodi? Accusamus dignissimos dolor perspiciatis quisquam nemo ratione provident voluptates quos odio ducimus quam, itaque voluptate rem aliquid vero harum est molestiae. <br> Quis, labore. Quia sapiente quos perspiciatis unde non officiis. Officia commodi a architecto, maxime accusantium ut dolorem nisi rem dolore, soluta incidunt eveniet perspiciatis harum. Deleniti sit iure est quidem eveniet hic nostrum assumenda deserunt explicabo beatae! Deleniti ipsum esse quisquam cum inventore autem facilis veniam nihil vero ab id amet totam in ratione cupiditate, placeat sed temporibus dolorem quibusdam! Impedit fuga natus, quae mollitia velit accusamus eos dolorum modi maxime deserunt illum sit cupiditate architecto et ullam rem quos recusandae sed quisquam cum!</p>
			
																				<!-- Anfang Speisen -->
			<h2 id="speisen">Köstlichkeiten, die Sie erwarten</h2>
			
			<?php
			if ($link->connect_error) { // Überprüfen, ob verbindung besteht
				echo "Verbindung fehlgeschlagen: ", mysqli_connect_error();
				exit();
			} else { // Wenn ja:
				// Abfrage (filtert ersten 5 Gerichte in var $result)
				$sql = "SELECT * FROM gericht LIMIT 5";

				// Query laufen lassen
				$result = $link->query($sql);
				if ($result->num_rows < 1) { // Überprüfen, ob die Abfrage Fehler hat
					echo "Fehler während der Abfrage:  ", mysqli_error($link);
					exit();
				}

				// Kopfzeile der Tabelle
				echo "<table><tr id='thead'>\n
				<th></th>\n
				<th>Preis intern</th>\n
				<th>Preis extern</th>\n
				<th>Allergene</th>\n
			</tr>\n";

			// Schleife läuft durch erste Dim. des 2d Array
			// "Für jede Zeile in der Ergebnistabelle"
			while ($row = $result->fetch_assoc()){
				// Query, die Allergene für das entsprechende Gericht filtert
				$temp = $row['id'];
				$sqlAllergens = "SELECT * FROM gericht_hat_allergen WHERE $temp = gericht_id";
				$resultAllergen = $link->query($sqlAllergens);

				// Tabellenelement des Gerichts
				echo "<tr>";
				echo "<td>" . $row['name'] . "</td>";
				echo "<td>" . $row['preis_intern'] . "</td>";
				echo "<td>" . $row['preis_extern'] . "</td>";
				echo "<td>";
				while($rowAllergen = $resultAllergen->fetch_assoc()){ // "Für jedes Allergen in dem Gericht"
					echo $rowAllergen['code'] . " ";
				}
				echo "</td>";
				echo "</tr>";
			}
				echo "</table>"; // Tabelle schließen
			}
			unset($sql, $result);

			// Tabelle mit allen Allergenen Ausgeben
			$sql = "SELECT * FROM allergen";
			$result = $link->query($sql);
			if ($result->num_rows < 1) { // Überprüfen, ob die Abfrage Fehler hat
				echo "Fehler während der Abfrage:  ", mysqli_error($link);
				exit();
			}
			
			// Kopfzeile der Tabelle
			echo "<h2>Liste Aller Allergene</h2><table>
					<tr id='thead'>\n
						<th>Code</th>\n
						<th>Name</th>\n
						<th>Typ</th>\n
					</tr>";
			
			// "Für jedes gefundene Allergen: erstelle einen Eintrsg in der Tabelle"
			while ($row = $result->fetch_assoc()){
				echo "<tr>";
				echo "<td>" . $row['code'] . "</td>";
				echo "<td>" . $row['name'] . "</td>";
				echo "<td>" . $row['typ'] . "</td>";
				echo "</tr>";
			}
			echo "</table>"; // Tabelle schließen
			unset($sql, $result);


			?>
																							<!-- Ende Speisen -->
																							<!-- Anfang Zahlen -->
			<h2 id="zahlen">E-Mensa in Zahlen</h2>
			<div id="grid2">
				<div>
					<p class="mensaZahlen"><?php
					// Lesen und ausgeben, wie oft die Seite geladen wurde
					$count;
					if (file_exists("countVisit.json")) {
						$count = json_decode(file_get_contents("countVisit.json"), true);
					} else $count = 0;

					echo $count;

					unset($count);
					?> Besuche</p>
				</div>
				<div>
					<p class="mensaZahlen">
					<?php // Zählen, wie viele User sich aregistriert haben
					if (file_exists("user.json")) {	// hat sich überhaupt ein User registriert?
						$userlist = json_decode(file_get_contents("user.json"), true);
						echo count($userlist) . "";
						file_put_contents("user.json", json_encode($userlist));
						unset($userlist);
					} else echo 0;
					?> Anmeldungen zum Newsletter</p>
				</div>
				<div>
					<p class="mensaZahlen">
					<?php // Zählen, wie viele Speisen es gibt !! an Tabelle auf der Seite angepasst, nicht an DB !!
					$sql = "SELECT * FROM gericht LIMIT 5";

					$result = $link->query($sql);
					if ($result->num_rows < 1) { // Überprüfen, ob die Abfrage Fehler hat
						echo "Fehler während der Abfrage:  ", mysqli_error($link);
						exit();
					}
						echo $result->num_rows . "";

						unset($sql, $result);
					?> Speisen</p>
				</div>
			</div>
																							<!-- Ende Zahlen -->
																							<!-- Anfang Kontakt -->
			<h2 id="kontakt">Interesse geweckt? Wir informieren Sie!</h2>
			<div> 
																							<!-- Kontaktformular -->
				<form action="./index.php" method="post">
					<div id="formGrid">
						<div>
							<label for="vorname">Ihr Name:</label><br>
							<input type="text" placeholder="Vorname" id="vorname" name="vorname" required>
						</div>
						<div>
							<label for="email">Ihre E-Mail:</label><br>
							<input type="email" id="email" placeholder="name@example.com" name="email" required>
						</div>
						<div>
							<label for="newsSprache">Newsletter bitte in:</label><br>
							<select name="newsSprache" id="newsSprache">
								<option value="de">Deutsch</option>
								<option value="en">Englisch</option>
								<option value="es">Spanisch</option>
								<option value="nl">Niederländisch</option>
							</select>
						</div>
					</div>
					<input type="checkbox" id="dsgvo" name="dsgvo" required>
					<label for="dsgvo">Den Datenschutzbestimmungen stimme ich zu</label>
					<input type="submit" name="submit" id="formSubmit" value="Zum Newsletter anmelden">
				</form>
																						<!-- Ende Kontaktformular -->
				<?php
																							// Datenverarbeitung  //
				
				// Wenn vorname, email und die gewünschte Newslettersprache nicht leer sind:
				if (isset($_POST["vorname"], $_POST["email"], $_POST["newsSprache"])) {
				
					
					// boolean var. werden später gebraucht
					$nameOK = false;
					$mailOK = true;
					
					// Array mit allen Buchstaben
					$letters = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",
									 "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
					
					// Array mit unerwünschten E-Mail_Providern
					$bannedMail = array("rcpt","damnthespam","wegwerfmail","trashmail");
					
					// Gucken, ob mindest ein Buchstabe enthalten ist. 
					// Falls ja => var $nameOK auf true setzen
					for ($i = 0; $i < count($letters); $i++){
						if (strpos($_POST["vorname"], $letters[$i]) != false) {
							$nameOK = true;
							break;
						}
					}

					// neue Userdaten lesen
					$newUser = array(array($_POST["vorname"], $_POST["email"], $_POST["newsSprache"]));

					// Gucken ob der Name Sonderzeichen enthält 
					// ja => 'Fehler' ausgeben, nein => skipp
					if (!preg_match("/^(?!\s*$)[\sa-zA-Z]+$/", $_POST["vorname"])) {
						echo "Der Name darf nur Buchstaben und Leerzeichen enthalten";
					
					// Gucken ob die E-Mailadresse gültig ist
					// ja => skipp, nein => 'Fehler' ausgeben
					} else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) || (strpos($_POST["email"], "rcpt") !== false || strpos($_POST["email"], "damnthespam") !== false || strpos($_POST["email"], "wegwerfmail") !== false|| strpos($_POST["email"], "trashmail") !== false)) {
						echo "Die E-Mailadresse ist ungültig";
					
					// wenn alte userdaten existieren:
					} else if (file_exists("user.json")) { 
						// alte Userdaten lesen
						$userlist = json_decode(file_get_contents("user.json"), true);

						// wenn neuer user noch nicht in der Liste:
						if (array_search($newUser[0], $userlist) == false) { 
							// arrays mergen und Registrierung ausgeben
							$userlist = array_merge($userlist, $newUser);
							echo "Sie haben sich erfolgreich registriert!";
						
						// Sonst 'Fehler' ausgeben
						} else echo "Sie sind bereits registriert";
						
						// neues Array speichern
						file_put_contents("user.json", json_encode($userlist));
					} else {
						// sonst neuen/ersten user speichern und Registrierung ausgeben
						file_put_contents("user.json", json_encode($newUser));
						echo "Sie haben sich erfolgreich registriert!";
					}
				}
				?>
			</div>
																								<!-- Ende Kontakt -->
			<h2 id="important">Das ist uns wichtig</h2>
			<div id="prinzipien">
				<ul>
					<li>Beste frische saisonaler Zutaten</li>
					<li>Ausgewiesene abwechslungsreiche Gerichte</li>
					<li>Sauberkeit</li>
				</ul>
			</div>
			
			<h2 id="aufwiedersehen"> Wir freuen uns auf Ihren Besuch!</h2>
		</div>
																						<!-- Ende Contentspalte -->
		<div id="empty_right1">
																						<!-- Leere Spalte rechts -->
		</div>
	</main>
	<footer>
		<div id="empty_left2">

		</div>
		<div id="footerListe">
			<div id="copyrightDiv">
				<p id="copyright">(c) E-Mensa GmbH</p></div>
			<div class="footerBorder" id="myNameDiv">
				<p id="myName">Alejandro Schmeing</p></div>
			<div class="footerBorder" id="impressumDiv">
				<p id="impressum"><a href="Impressum.html">Impressum</a></p></div>
		</div>   
		<div id="empty_right2">

		</div>     
	</footer>
</body>
</html>