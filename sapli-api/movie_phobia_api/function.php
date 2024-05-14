<?php
    ini_set( "display_errors", 1);
    require('../connexion.php');

    function add_movie_phobia($id_movie, $id_phobia, $time_code, $upvote, $downvote){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('INSERT INTO movie_phobia (id_movie, id_phobia, time_code, up_vote, down_vote) VALUES(:id_movie, :id_phobia, :time_code, :upvote, :downvote)');
        if ($req1){
            $code=401;
            $message = 'parametre manquant ou invalide';
            try{
                $req1->execute(array(
                'id_movie' => $id_movie,
                'id_phobia' => $id_phobia,
                'time_code' => $time_code,
                'upvote' => $upvote,
                'downvote' => $downvote,
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
    function get_movie_phobia($id_movie, $id_phobia){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM movie_phobia WHERE id_movie = :id_movie and id_phobia = :id_phobia and archive = 0');
        $req1->execute(array(
            'id_movie' => $id_movie,
            'id_phobia' => $id_phobia,
        ));
        $movie_phobia = $req1->fetch();
        if(isset($movie_phobia['id_movie'])){
            $code=200;
            $message = 'lien trouve';
        }else{
            $code=404;
            $message = 'lien non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$movie_phobia);
    }

    function get_all_movie_phobia(){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM movie_phobia where archive = 0');
        $req1->execute();
        $movie_phobia = $req1->fetchall();
        if(isset($movie_phobia)){
            $code=200;
            $message = 'lien trouve';
        }else{
            $code=404;
            $message = 'lien non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$movie_phobia);
    }

    function archive_movie_phobia($id_movie, $id_phobia){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('UPDATE movie_phobia SET archive = NOT(archive) WHERE id_movie = :id_movie and id_phobia = :id_phobia');
        $req1->execute(array(
            'id_movie' => $id_movie,
            'id_phobia' => $id_phobia,
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

    function update_movie_phobia ($id_movie, $id_phobia, $time_code, $upvote, $downvote){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('UPDATE movie_phobia SET time_code = :time_code, up_vote = :upvote, down_vote = :downvote WHERE id_movie = :id_movie and id_phobia = :id_phobia');
        $req1->execute(array(
            'id_movie' => $id_movie,
            'id_phobia' => $id_phobia,
            'time_code' => $time_code,
            'upvote' => $upvote,
            'downvote' => $downvote,
        ));
        if($req1){
            if($req1->rowCount()>0){
                $code=200;
                $message = 'lien modifie';
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