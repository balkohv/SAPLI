<?php
    ini_set( "display_errors", 1);
    require('../connexion.php');

    function add_phobia($name, $nb_subscribers){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('INSERT INTO phobia (name, nb_subscribers) VALUES(:name, :nb_subscribers)');
        if ($req1){
            $code=401;
            $message = 'parametre manquant ou invalide';
            $req1->execute(array(
                'name' => $name,
                'nb_subscribers' => $nb_subscribers,
            ));
            if($req1){
                $code=201;
                $message = 'phobia cree';
            }
        }
        return array("code"=>$code, "message"=>$message, "data"=>null);
    }
    function get_phobia($name){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM phobia WHERE name = :name and archive = 0');
        $req1->execute(array(
            'name' => $name,
        ));
        $phobia = $req1->fetch();
        if(isset($phobia['name'])){
            $code=200;
            $message = 'phobia trouve';
        }else{
            $code=404;
            $message = 'phobia non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$phobia);
    }

    function get_all_phobia(){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM phobia where archive = 0');
        $req1->execute();
        $phobia = $req1->fetchall();
        if(isset($phobia)){
            $code=200;
            $message = 'phobia trouve';
        }else{
            $code=404;
            $message = 'phobia non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$phobia);
    }

    function get_phobia_id($id){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM phobia WHERE id_phobia = :id');
        $req1->execute(array(
            'id' => $id,
        ));
        $phobia = $req1->fetch();
        if(isset($phobia['name'])){
            $code=200;
            $message = 'phobia trouve';
        }else{
            $code=404;
            $message = 'phobia non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$phobia);
    }

    function archive_phobia($name){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('UPDATE phobia SET archive = NOT(archive) WHERE name = :name');
        $req1->execute(array(
            'name' => $name,
        ));
        if($req1){
            if($req1->rowCount()>0){
                $code=200;
                $message = 'phobia archive';
            }else{
                $code=400;
                $message = 'phobia non trouve';
            }
        }else{
            $code=500;
            $message = 'erreur serveur';
        }
        return array("code"=>$code, "message"=>$message, "data"=>null);
    }

    function update_phobia ($name, $nb_subscribers, $id){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('UPDATE phobia SET name = :name, nb_subscribers = :nb_subscribers WHERE id_phobia = :id');
        $req1->execute(array(
            'name' => $name,
            'nb_subscribers' => $nb_subscribers,
            'id' => $id,
        ));
        if($req1){
            if($req1->rowCount()>0){
                $code=200;
                $message = 'phobia modifie';
            }else{
                $code=400;
                $message = 'phobia non trouve';
            }
        }else{
            $code=500;
            $message = 'erreur serveur';
        }
        return array("code"=>$code, "message"=>$message, "data"=>null);
    }

?>