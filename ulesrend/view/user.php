<?php
$osztaly = new osztaly();   
$result = $osztaly->getOsztaly();
while ($row = $result->fetch_assoc()) {
    if($row['id'] == $_GET['id']){
        //echo'<img src="uploads/'.$row['id'].'.png" alt="'.$row['id'].'.png">';
        $img = FALSE;

    foreach(IMG_EXTS as $ext) {
        $imgFile = TARGET_DIR . $row["id"].$ext;
        if (file_exists($imgFile)) {
            $img = '<img src="'.$imgFile.'?time='.time().'" style="width: 100px;"><br>';
            break;
        }
    }
        
echo '<div class="col-md-6">';
echo $img;
echo '<h3>'.$row['nev'].'</h3>';
echo '<p> oszlop: '.($row['oszlop'] +1).' sor: '.($row['sor']+1).'</p>';
echo '</div>';
}
}
?>