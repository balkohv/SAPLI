<?php
    ini_set( "display_errors", 1);
    require('../connexion.php');

    function add_user($username, $password){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('INSERT INTO user (username, password) VALUES(:username, :password)');

            if ($req1){
                $code=401;
                $message = 'parametre manquant ou invalide';
                $req1->execute(array(
                    'username' => $username,
                    'password' => hash('sha256',$password),
                ));
                if($req1){
                    $code=201;
                    $message = 'user cree';
                }
            }
        
        return array("code"=>$code, "message"=>$message, "data"=>null);
    }
    function get_user($username){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM user WHERE username = :username and archive = 0');
        $req1->execute(array(
            'username' => $username,
        ));
        $user = $req1->fetch();
        if(isset($user['username'])){
            $code=200;
            $message = 'user trouve';
        }else{
            $code=404;
            $message = 'user non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$user);
    }

    function get_all_user(){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM user where archive = 0');
        $req1->execute();
        $user = $req1->fetchall();
        if(isset($user)){
            $code=200;
            $message = 'user trouve';
        }else{
            $code=404;
            $message = 'user non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$user);
    }

    function archive_user($username){// TODO: ajouter desarchiver
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('UPDATE user SET archive = 1 WHERE username = :username ');
        $req1->execute(array(
            'username' => $username,
        ));
        if($req1){
            $code=200;
            $message = 'user archive';
        }else{
            $code=404;
            $message = 'user non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>null);
    }

?>