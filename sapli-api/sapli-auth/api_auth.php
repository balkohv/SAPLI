<?php
require('jwt_utils.php');

function register($db, $username, $password, $idauth, $role){
    $message = '';
    $req1 = $db->prepare('INSERT INTO phobia_warning_user(login, mdp, id_auth, role) VALUES(:username,:password,:idauth,:role)');
        if (!$req1){
            die('Erreur : ' . $req1.errorInfo());
        }

        $req1->execute(array(
            'username' => $username,
            'password' => hash('sha256',$password),
            'idauth' => $idauth,
            'role' => $role,
        ));
    if($req1){
        $code=201;
    }else{
        $code=500;
        $message= 'query error: '. mysqli_error($db);
    }
    return array("code"=>$code, "message"=>$message, "data"=>null);
}
function login ($db, $username, $password){
    $password = hash('sha256',$password);
    $req = $db->prepare("SELECT * FROM phobia_warning_user WHERE login = :login AND mdp = :mdp");
    $req->bindParam(':login', $username);
    $req->bindParam(':mdp', $password);
    $req->execute();
        
    $res = $req->fetch(PDO::FETCH_ASSOC);
        
    if ($res) {
        $login = $username;

        $headers = array('alg'=>'HS256', 'typ'=>'JWT');
        $payload = array('login'=>$login, 'exp'=>(time() + 600), 'role'=>$res['role']);
        $jwt = generate_jwt($headers, $payload, 'clefapi');
        
        return array("code"=>200, "message"=>"Authentification réussie, le token fournit sera valide 10 minutes", "data"=>$jwt);
    } else {
        return array("code"=>401, "message"=>"Erreur d'authentification : login et/ou mot de passe incorrect(s)", "data"=>null);
    }
}

function isValid($db, $jwt){
    $decoded = is_jwt_valid($jwt, 'clefapi');
    if($decoded){
        return array("code"=>200, "message"=>"Token valide", "data"=>null);
    }else{
        return array("code"=>401, "message"=>"Token invalide", "data"=>null);
    }
}
?>