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
		session_start();

		if(isset($_GET['action'])){
			if(isset($_GET['action']) == 'logout'){
				/*
				unset($_SESSION['felhasznalonev']);
				unset($_SESSION['nev']);
				unset($_SESSION['id']);*/
				session_unset();
			}
		}

		function safe_input($data){
			$data = trim($data);
			$data = stripcslashes($data);
			$data = htmlspecialchars($data);
			return $data;
			
		}

		$msg = '';
		$uploadOk = 0;
		$target_dir = "uploads/";
		$imgExtensions = array('jpg','jpeg','png','gif');
		$maxFileSize = 5;//MB-ban
		$maxFileSize = $maxFileSize * 1024 * 1024;// MB-ra alakítás
		

		if(isset($_POST['felhasznalonev']) and isset($_POST['jelszo'])){
			//ha érkezik login adat
			if(empty($_POST['felhasznalonev'])){
				$msg .= "A felhasználónév nem került megadásra<br>";
			}

			if(empty($_POST['jelszo'])){
				$msg .= "A jelszó nem került megadásra<br>";
			}

			if(!$msg){
				$sql = "SELECT jelszo, id, nev FROM osztaly WHERE felhasznalonev = '".$_POST['felhasznalonev']."';";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					if($row = $result->fetch_assoc()) {
						if($row['jelszo'] == md5($_POST['jelszo'])){
							//echo "Felhasználó belépett";
							$_SESSION['felhasznalonev'] = $_POST['felhasznalonev'];
							$_SESSION['nev'] = $row['nev'];
							$_SESSION['id'] = $row['id'];
						} else {
							$msg .= "A felhasználóhoz megadott jelszó nem érvényes";
						}
					}
				} else {
					$msg .= "A megadott ".$_POST['felhasznalonev']." felhasználónév nem található<br>";
				}
			}

		} elseif(!empty($_POST['modosoitandoNev']) and isset($_SESSION['id'])){
			//ha érkezik módosításra név és id
			
			$fileName = basename($_FILES["fileToUpload"]["name"]);
			//echo "eredeti nev: $fileName <br>";
			$fileNameArray = preg_split("/\./",$fileName);
			//print_r($fileNameArray);
			$fileName = $_SESSION['id'].".".$fileNameArray[1];
			//echo "uj nev: $fileName";
			$target_file = $target_dir . $fileName;

			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
 		 		if($check !== false) {
    				// echo "File is an image - " . $check["mime"] . ".";
    				$uploadOk = 1;
  				} else {
    				$msg .= "A feltöltött ".$_FILES["fileToUpload"]["name"]." fájl nem kép.<br>";
    				$uploadOk = 0;
  				}
			
			if ($_FILES["fileToUpload"]["size"] > $maxFileSize) {
			    $msg .= "Sry, your PP is too large.<br>";
				$uploadOk = 0;
			}

			if($uploadOk == 1){
				foreach($imgExtensions as $ext) {
					$imgFile = $target_dir.$_SESSION['id'].$ext;
					if (file_exists($imgFile)) {
						unlink($imgFile);
					}
				}
				move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
			}

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
				$sql = "UPDATE osztaly SET nev = '".$_POST['modosoitandoNev']."' WHERE id = ".$_SESSION['id'];
			
				if($result = $conn->query($sql)){
					$msg = "A név módosításra került";
				} else{
					$msg = "A név nem került módosításra";
					if($conn->error) {
						echo $conn->error;
						echo $sql;
					}
				}
			}

			
		}

		if(isset($_SESSION['felhasznalonev'])){
			echo "ÜDV ".$_SESSION['nev']."!";
			echo '<a href="index.php?action=logout">>KILÉPÉS</a>'; 
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
			
			//van-e profilkép?
			
			$img = FALSE;
			foreach($imgExtensions as $ext){
				$imgFile = $target_dir.$row["id"].".".$ext;
				if(file_exists($imgFile)){
					//echo "létezik: $imgFile ";
					$img = '<img src="'.$imgFile.'" class="pp">';
				}
			}

			if(!$img){
				$img="";
			}

			
			$emptyCell = '<td class = "emptyCell"></td>';
			$class = "tanulo";

			//tanar
			if($row['sor']==3 && $row['oszlop']==3) {
				for($i = 0; $i<3; $i++){
					echo $emptyCell;
				}
				$class = "tanar";
			//2. ures oszlop
			} elseif($row['sor']<3 && $row['oszlop']==4 ){
				if($row['sor']==2){
					echo $emptyCell;	
				}
				echo $emptyCell;
			//en
			} elseif($row['sor']==1 && $row['oszlop']==1 ){
				$class = "meme";
			//topi
			} elseif($row['sor']==3 && $row['oszlop']==5 ){
				echo $emptyCell;
			}

			$kiiras = '<td class = "'.$class.'"><a href="index.php?id='.$row["id"].'">'.$img.'<br>'.$row['nev'].'</a>';
			echo $kiiras."</td>";

			if($row['sor']==0) {
				if($row['oszlop']==0){
					echo '<td rowspan="4" class="tolto" style="width: 40px;">';
				}
			}
		
			if(isset($_GET['id'])){
				if($row["id"] == $_GET['id']){
					$modosoitandoNev = $row["nev"];
				}
			}
			

			//Sor lezárás
			echo '</td>';
		}
		echo "		</tr>";
		echo '</table>';
		}
	?>

	<?php
	//adatmódosítás, ha jütt GET id
	if(isset($_SESSION['felhasznalonev'])){
		echo '<form action="index.php" method="post" enctype="multipart/form-data">';
		echo '	<input type="text" name="modosoitandoNev" value="'.$_SESSION['nev'].'"><br>';
		echo '	<p>Kérem válasszon ki egy profilképet: </p>
				<input type="file" name="fileToUpload" id="fileToUpload"><br>';
		//echo '	<input type="hidden" name="id" value="'.$_SESSION['id'].'">';
		echo '	<input type="submit" value="MÓDOSÍTÁS ">';
		echo '</form>';
	} else {
		?>
		<form action="index.php" method="post">
			<p>Felhasznalónév:</p>
			<input type="text" name="felhasznalonev" value="" required><br>
			<p>Jelszó:</p>
			<input type="password" name="jelszo" required><br>
			<input type="submit" value="BELÉPÉS">
		</form>
		<?php
	}
	?>
	
</body>
</html>