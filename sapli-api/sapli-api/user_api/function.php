<?php
    ini_set( "display_errors", 1);
    require('../connexion.php');

    function add_user($username, $password, $email){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('INSERT INTO user (username, password, email, browser_id) VALUES(:username, :password, :email, :brower_id)');

            if ($req1){
                $code=401;
                $message = 'parametre manquant ou invalide';
                $req1->execute(array(
                    'username' => $username,
                    'password' => hash('sha256',$password),
                    'email' => $email,
                    "brower_id"=>generate_random(50),
                ));
                if($req1){
                    $code=201;
                    $message = 'user cree';
                }
            }
        
        return array("code"=>$code, "message"=>$message, "data"=>null);
    }

    function login ($username, $password){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM user WHERE username = :username and password = :password and archive = 0');
        $req1->execute(array(
            'username' => $username,
            'password' => hash('sha256',$password),
        ));
        $user = $req1->fetch();
        if(isset($user['username'])){
            $code=200;
            $message = 'user trouve';
        }else{
            $code=404;
            $message = 'user non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$user['browser_id']);
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

    function get_user_by_browser_id($browser_id){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM user WHERE browser_id = :browser_id and archive = 0');
        $req1->execute(array(
            'browser_id' => $browser_id,
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

    function get_user_id($browser_id){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM user WHERE browser_id = :id');
        $req1->execute(array(
            'id' => $browser_id,
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

    function archive_user($username){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('UPDATE user SET archive = not(archive) WHERE username = :username ');
        $req1->execute(array(
            'username' => $username,
        ));
        if($req1){
            $code=200;
            $message = 'user archive';
        }else{
            $code=500;
            $message = 'erreur serveur';
        }
        return array("code"=>$code, "message"=>$message, "data"=>null);
    }

    function update_user ($username, $password, $id){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('UPDATE user SET password = :password , username = :username WHERE id_user = :id');
        $req1->execute(array(
            'id' => $id,
            'username' => $username,
            'password' => hash('sha256',$password),
        ));
        if($req1){
            if($req1->rowCount()>0){
                $code=200;
                $message = 'user modifie';
            }else{
                $code=400;
                $message = 'user non trouve';
            }
        }else{
            $code=500;
            $message = 'erreur serveur';
        }
        return array("code"=>$code, "message"=>$message, "data"=>null);
    }

    function generate_random($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function get_vote_movie_phobia($id_movie, $id_phobia, $id_user, $time_code){
        global $db;
        $code=500;
        $message = 'erreur serveur';
        $req1 = $db->prepare('SELECT * FROM vote WHERE id_movie = :id_movie and id_phobia = :id_phobia and id_user = :id_user and time_start = :time_code');
        $req1->execute(array(
            'id_movie' => $id_movie,
            'id_phobia' => $id_phobia,
            'id_user' => $id_user,
            'time_code' => $time_code,
        ));
        $vote = $req1->fetch();
        if(isset($vote['id_movie'])){
            $code=200;
            $message = 'vote trouve';
        }else{
            $code=404;
            $message = 'vote non trouve';
        }
        return array("code"=>$code, "message"=>$message, "data"=>$vote);
    }

?>