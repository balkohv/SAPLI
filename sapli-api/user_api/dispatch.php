<?php
    require('function.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            var_dump("coucou");
            $data = json_decode(file_get_contents('php://input'), true);
            $username = $data['username'];
            $password = $data['password'];
            $response = add_user($username, $password);
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'GET':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['username'])){
                $username = $data['username'];
                $response = get_user($username);
            }else{
                $response = get_all_user();
            }
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $username = $data['username'];
            $response = archive_user($username);
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        default:
            http_response_code(405);
            break;
    }
?>