<?php
    ini_set( "display_errors", 1);
    require('../connexion.php');

    function add_user_phobia($id_phobia, $id_user){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('INSERT INTO user_phobia (id_phobia, id_user) VALUES(:id_phobia, :id_user)');
        if ($req1){
            $code=401;
            $message = 'parametre manquant ou invalide';
            try{
                $req1->execute(array(
                'id_phobia' => $id_phobia,
                'id_user' => $id_user
                ));
                if($req1){
                    $code=201;
                    $message = 'lien cree';
                }
            }catch(Exception $e){
                if($e->getCode()==23000){
                    $code=409;
                    $message = 'lien deja existant';
                }else{
                    $code=500;
                    $message = 'erreur serveur:'.$e->getmessage();
                }
            }
        }
        return array("code"=>$code, "message"=>$message, "data"=>null);
    }
    function get_user_phobia($id_phobia, $id_user){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM user_phobia WHERE id_phobia = :id_phobia and id_user = :id_user and archive = 0');
        $req1->execute(array(
            'id_phobia' => $id_phobia,
            'id_user' => $id_user
        ));
        $user_phobia = $req1->fetch();
        if(isset($user_phobia['id_phobia'])){
            $code=200;
            $message = 'lien trouve';
        }else{
            $code=404;
            $message = 'lien non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$user_phobia);
    }

    function get_all_user_phobia(){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM user_phobia WHERE archive = 0');
        $req1->execute();
        $user_phobia = $req1->fetchAll();
        if($user_phobia){
            $code=200;
            $message = 'lien trouve';
        }else{
            $code=404;
            $message = 'lien non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$user_phobia);
    }

    function archive_user_phobia($id_user, $id_phobia){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('UPDATE user_phobia SET archive = NOT(archive) WHERE id_user = :id_user and id_phobia = :id_phobia');
        $req1->execute(array(
            'id_user' => $id_user,
            'id_phobia' => $id_phobia
        ));
        if($req1){
            if($req1->rowCount()>0){
                $code=200;
                $message = 'lien archive';
            }else{
                $code=400;
                $message = 'lien non trouve';
            }
        }else{
            $code=500;
            $message = 'erreur serveur';
        }
        return array("code"=>$code, "message"=>$message, "data"=>null);
    }

?>