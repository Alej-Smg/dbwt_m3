<!--
	- Praktikum DBWT. Autoren:
	- Paundra, Daran, 3290902
	- Alejandro, Schmeing, 3203949
-->

<!DOCTYPE html>
<html lang=de>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="index.css">
		<title>Newsletter Admin</title>

	</head>
	<body>
		<form action="./nl-admin.php" method="GET">
			<label for="">Sortieren nach:</label><br>
			<input type="radio" name="filter" value="filterName">
			<label for="filterName">Name</label><br>
			<input type="radio" name="filter" value="filterEmail">
			<label for="filterEmail">E-Mail</label><br>
			<input type="text" name="nameSearch" placeholder="Wen suchen Sie?"><br>
			<input type="submit" value="Filter anwenden">
		</form>
		<table>
		<thead>
			<tr>
				<td>Name</td>
				<td>E-Mail</td>
				<td>Sprache</td>
				<td>Datenschutz zustimmung</td>
			</tr>
		</thead>
		<tbody>
		<?php
		$userlist;
		if (file_exists("user.json")) 
			// alte Userdaten lesen
			$userlist = json_decode(file_get_contents("user.json"), true);
		else $userlist = array(array());

		if (isset($_GET["filter"]) && $_GET["filter"] == "filterName") array_multisort($userlist, SORT_ASC, 0);
		if (isset($_GET["filter"]) && $_GET["filter"] == "filterEmail") array_multisort($userlist, SORT_ASC, 1);

		
		if (isset($_GET["nameSearch"]) && strlen($_GET["nameSearch"]) > 0) {
			foreach ($userlist as $user) {
				$haystack = strtolower($user[0]);
				$needle = strtolower($_GET["nameSearch"]);

				if((strpos($haystack, $needle) !== false)) {
					echo "<tr>
						<td>{$user[0]}</td> 
						<td>{$user[1]}</td> 
						<td>{$user[2]}</td> 
						<td>Ja</td>
						</tr>" ;

				}

			}
		} else {

			foreach ($userlist as $user) {
				echo "<tr>
					<td>{$user[0]}</td> 
					<td>{$user[1]}</td> 
					<td>{$user[2]}</td> 
					<td>Ja</td>
					</tr>" ;

			}
		}
		?>
		</tbody>
		</table>
	</body>


</html>