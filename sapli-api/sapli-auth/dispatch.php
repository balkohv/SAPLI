<?php
    ini_set( "display_errors", 1);
    header("Access-Control-Allow-Origin:*");
    header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require("api_auth.php");
    require('connexion.php');
    require('deliverResponse.php');

    $http_method = $_SERVER['REQUEST_METHOD'];
    switch ($http_method){
    case "POST" :
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData,true);
        if(isset($data['login']) && isset($data['password']) && isset($data['id_auth']) && isset($data['role'])){
            $login = $data['login'];
            $password = $data['password'];
            $idauth = $data['id_auth'];
            $role = $data['role'];
            $response=register($db, $login, $password, $idauth, $role);
        }else{
            $login = $data['login'];
            $password =$data['mdp'];
            $response = login($db, $login, $password);

        }
        deliver_response($response["code"], $response["message"], $response["data"]);
        break;
    break;
    case "GET" :
        $response = isValid($db, get_bearer_token());
        deliver_response($response["code"], $response["message"], $response["data"]);
        break;
    default :
        deliver_response(405, "methode incorrect", null);
        break;
    }



?>