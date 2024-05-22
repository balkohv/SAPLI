<?php
    ini_set( "display_errors", 1);
    require('../connexion.php');

    function add_movie($name, $plateforme, $duration, $episode){
        global $db;
        $code=00;
        $message = 'erreur serveur';
        $req1 = $db->prepare('INSERT INTO movie (name, plateforme,episode,duration) VALUES(:name, :plateforme, :episode, :duration)');
        if ($req1){
            $code=401;
            $message = 'parametre manquant ou invalide';
            $req1->execute(array(
                'name' => $name,
                'plateforme' => $plateforme,
                'episode' => $episode,
                'duration' => $duration,
            ));
            if($req1){
                $code=201;
                $message = 'film cree';
            }
        }
        return array("code"=>$code, "message"=>$message, "data"=>null);
    }
    function get_movie($name,$plateforme){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM movie WHERE name = :name and plateforme = :plateforme and archive = 0');
        $req1->execute(array(
            'name' => $name,
            'plateforme' => $plateforme,
        ));
        $movie = $req1->fetch();
        if(isset($movie['name'])){
            $code=200;
            $message = 'film trouve';
        }else{
            $code=404;
            $message = 'film non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$movie);
        
    }
    function get_serie($name,$episode,$plateforme,$duration){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM movie WHERE name = :name and episode = :episode and plateforme = :plateforme and archive = 0');
        $req1->execute(array(
            'name' => $name,
            'episode' => $episode,
            'plateforme' => $plateforme,
        ));
        $movie = $req1->fetch();
        if(isset($movie['name'])){
            $code=200;
            $message = 'serie trouve';
        }else{
            $code=404;
            $message = 'serie non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$movie);
    }

    function get_all_movie(){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM movie where archive = 0');
        $req1->execute();
        $movie = $req1->fetchall();
        if(isset($movie)){
            $code=200;
            $message = 'film trouve';
        }else{
            $code=404;
            $message = 'film non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$movie);
    }

    function get_movie_id($id){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM movie WHERE id_movie = :id');
        $req1->execute(array(
            'id' => $id,
        ));
        $film = $req1->fetch();
        if(isset($film['name'])){
            $code=200;
            $message = 'film trouve';
        }else{
            $code=404;
            $message = 'film non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$film);
    }

    function archive_movie($name){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('UPDATE movie SET archive = not(archive) WHERE name = :name');
        $req1->execute(array(
            'name' => $name,
        ));
        if($req1){
            if($req1->rowCount()>0){
                $code=200;
                $message = 'film archive';
            }else{
                $code=400;
                $message = 'film non trouve';
            }
        }else{
            $code=500;
            $message = 'erreur serveur';
        }
        return array("code"=>$code, "message"=>$message, "data"=>null);
    }

    function update_movie ($name, $plateforme, $id){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('UPDATE movie SET name = :name, plateforme = :plateforme WHERE id_movie = :id');
        $req1->execute(array(
            'name' => $name,
            'plateforme' => $plateforme,
            'id' => $id,
        ));
        if($req1){
            $code=200;
            $message = 'film modifie';
            if($req1->rowCount()==0){
                $code=400;
                $message = 'film non trouve';
            }
        }else{
            $code=500;
            $message = 'erreur serveur';
        }
        return array("code"=>$code, "message"=>$message, "data"=>null);
    }

?>