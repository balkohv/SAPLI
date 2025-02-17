<?php
    $username ="";
    $pwd = ""; 
    try {
        $db = new PDO('mysql:host=192.168.1.59;port=3306;dbname=api_user',$username,$pwd);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

?>
