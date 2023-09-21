<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
	<title>Ülésrend</title>
</head>
<body>

<?php
	$osztaly = array(
		array("S. Balázs", "Halir Szabolcs", "Fehér László", "", "Gulcsik Zoltán", "Harsányi Ferenc"),
		array("Kiss Márton", "Bartha László","Krenner Dominik", "", "Járfás Dániel", "Végh Szabolcs"),
		array("Bella Marcell", "Simon Attila", "", "", "Hadnagy Márk", "Rácz Dávid"),
		array("", "", "", "Bicsák József", "",  "Topercer Márton")
	);
	/*print "<pre>";
	print_r($osztaly);
	print "<pre>"*/
?>
	<table>
		<?php
			foreach($osztaly as $sorIndex => $sor){
				echo "<tr>";
				foreach($sor as $oszlop => $nev){
					$class = "tanulo";
					if(!$nev) {
						$class = "emptyCell";
					} elseif($sorIndex == 3 && $oszlop == 3) {
						$class = "tanar";
					}
					echo '<td class="'.$class.'">'.$nev.'</td>';
				}
				echo "<tr>";
			}
		?>
	</table>
<!--
	<div id="claswsroom">
		<table>
			<tr id=firstColumn>
				<td class = "tanulo"></td>
				<td rowspan="4" style="width: 40px;"></td>
				<td class = "tanulo"></td>
				<td class = "tanulo"></td>
				<td colspan="1" class="emptyCell"></td>
				<td class = "tanulo"></td>
				<td class = "tanulo"></td>
			</tr>
			<tr>
				<td class = "tanulo">Kiss Márton</td>
				<td class = "tanulo">Bartha László</td>
				<td class = "tanulo">Krenner Dominik</td>
				<td colspan="1" class="emptyCell"></td>
				<td class = "tanulo">Járfás Dániel</td>
				<td class = "tanulo">Végh Szabolcs</td>
			</tr>
			<tr>
				<td class = "tanulo">Bella Marcell</td>
				<td class = "tanulo">Simon Attila</td>
				<td colspan="2" class="emptyCell"></td>
				<td class = "tanulo">Hadnagy Márk</td>
				<td class = "tanulo">Rácz Dávid</td>
			</tr>
			<tr>
				<td colspan="3" class="emptyCell"></td>
				<td class = "tanar">Bicsák József</td>
				<td colspan="1" class="emptyCell"></td>
				<td class = "tanulo">Topercer Márton</td>
			</tr>
			
		</table>
	</div>
-->
</body>
</html>