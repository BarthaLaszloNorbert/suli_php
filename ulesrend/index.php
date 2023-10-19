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
		require "mysql.php";

		function safe_input($data){
			$data = trim($data);
			$data = stripcslashes($data);
			$data = htmlspecialchars($data);
			return $data;
			
		}

		$msg = '';
		//ha érkezik módosításra név és id
		if(!empty($_POST['modosoitandoNev']) and isset($_POST['id'])){
			$nev = safe_input($_POST['modosoitandoNev']);
			if(empty($nev)){
				$msg .= "A névben csak space karakterek nem lehetnek<br>";
			}
			if(!preg_match("/^[a-záéíóúöüőűÁÉÍÓÚÖÜŐŰA-Z-' ]*$/",$nev)){
				$msg .= "A névben csak betűk és space karakterek lehetnek<br>";
			}
			if(mb_strlen($nev) > 100){
				$msg .= "A név maximum 100 karakter hosszú lehet<br>";
			}elseif(mb_strlen($nev) < 5){
				$msg .= "A név minimum 5 karakternek kell lennie<br>";
			}


			if($msg == '') {
				$sql = "UPDATE osztaly SET nev = '".$_POST['modosoitandoNev']."' WHERE id = ".$_POST['id'];
			
				if($result = $conn->query($sql)){
					$msg = "A név módosításra került";
				} else{
					$msg = "A név nem került módosításra";
				}
			}

			
		}

		if(isset($msg)){
			echo '<h2 class="msg_warning">'.$msg.'</h2>';
		}

		$sql = "SELECT `id`,`nev`,`sor`,`oszlop` FROM `osztaly` ORDER BY `sor`,`oszlop` ASC;";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		// output data of each row
		echo '<table>';

		$sorId = NULL;
		$modosoitandoNev = '';

		while($row = $result->fetch_assoc()) {
			if($row["sor"] != $sorId) {
				if($sorId != NULL) echo "		</tr>";
				echo "		<tr>";
				$sorId = $row["sor"];
			}

			$kiiras = '<td class = "tanulo"><a href="index.php?id='.$row["id"].'">'.$row['nev'].'</a></td>';

			if($row['sor']==3 && $row['oszlop']==3) {
				for($i = 0; $i<3; $i++){
					echo '<td class = "emptyCell"></td>';
				}
				echo $kiiras;
				echo '<td class = "emptyCell"></td>';
			} elseif($row['sor']<3 && $row['oszlop']==4 ){
				if($row['sor']==2){
					echo '<td class = "emptyCell"></td>';	
				}
				echo '<td class = "emptyCell"></td>';
				echo $kiiras;
			} else {
				echo $kiiras;
			}

			if($row['sor']==0) {
				if($row['oszlop']==0){
					echo '<td rowspan="4" class="tolto" style="width: 40px;"></td>';
				}
			}
		
			if(isset($_GET['id'])){
				if($row["id"] == $_GET['id']){
					$modosoitandoNev = $row["nev"];
				}
			}

		}
		echo "		</tr>";
		echo '</table>';
		}
	?>

	<?php
	//adatmódosítás, ha jütt GET id
	if($modosoitandoNev){
		echo '<form action="index.php" method="post">';
		echo '	<input type="text" name="modosoitandoNev" value="'.$modosoitandoNev.'">';
		echo '	<input type="hidden" name="id" value="'.$_GET['id'].'">';
		echo '	<input type="submit" value="MÓDOSÍTÁS ">';
		echo '</form>';
	}
	?>
	
</body>
</html>