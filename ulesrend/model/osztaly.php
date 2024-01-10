<?php

class Osztaly {

    function getOsztaly() {
    
        $sql = "SELECT id, nev, sor, oszlop FROM ".DB_PREFIX."_osztaly ORDER BY sor, oszlop;";
        $result = DataBase::$conn->query($sql);
        
        return $result;
    }



    function getUser($id) {
        /* Prepared statement, stage 1: prepare */
        $stmt = DataBase::$conn->prepare("SELECT nev, sor, oszlop FROM ".DB_PREFIX."_osztaly WHERE id = ?");

        /* Prepared statement, stage 2: bind and execute */
        $stmt->bind_param("i", $id); // "i" means that $id is bound as an integer

        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result;
    }
    
    function updateOsztaly() {
        $sql = "UPDATE ".DB_PREFIX."_osztaly SET nev = ? WHERE id = ".$_SESSION['id'];
        /* Prepared statement, stage 1: prepare */
        $stmt = DataBase::$conn->prepare($sql);
        
        /* Prepared statement, stage 2: bind and execute */
        $stmt->bind_param("s", $_POST['modositandoNev']); // 

        $stmt->execute();

        if($result = $stmt->affected_rows) {
            $msg = "A név módosításra került";
        }
        else {
            $msg = "A név nem került módosításra";
            if(DataBase::$conn->error) {
                echo DataBase::$conn->error;
                echo $sql;
            }
        }
        return $msg;
    }

    /**
     * ellenőrzi a felhasználót, belépteti, sessiont ír, vagy hibát ad vissza
     */
    function checkLogin($msg) {
        $sql = "SELECT jelszo, id, nev FROM ".DB_PREFIX."_osztaly WHERE felhasznalonev = ?";
        
        $stmt = DataBase::$conn->prepare($sql);
        
        $stmt->bind_param("s", $_POST['felhasznalonev']);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            if($row = $result->fetch_assoc()) {
                if($row['jelszo'] == md5($_POST['jelszo'])) {
                    $_SESSION['felhasznalonev'] = $_POST['felhasznalonev'];
                    $_SESSION['nev'] = $row['nev'];
                    $_SESSION['id'] = $row['id'];
                }
                else {
                    $msg .= "A felhasználóhoz megadott jelszó nem érvényes. ";
                }
            }
        }
        else {
            $msg .= "A megadott ".$_POST['felhasznalonev']." felhasználónév nem található. ";
        }
        return $msg;
    }
}

?>