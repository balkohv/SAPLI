<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Content-Type: application/json");
    require('function.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['email']) and isset($data['username']) and isset($data['password'])){
                $email = $data['email'];
                $username = $data['username'];
                $password = $data['password'];
                $response = add_user($username, $password, $email);
            }elseif(isset($data['username']) and isset($data['password'])){
                $username = $data['username'];
                $password = $data['password'];
                $response = login($username, $password);
            }else{
                $response = array("code"=>401, "message"=>"parametre manquant ou invalide", "data"=>null);
            }
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'GET':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($_GET['username'])){
                $username = $_GET['username'];
                $response = get_user($username);
            }elseif(isset($_GET['browser_id'])){
                $response=get_user_by_browser_id($_GET['browser_id']);
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
        case 'PATCH':
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data["id"];
            $response = get_user_id($id);
            $username = isset($data['username'])?$data['username']:$response["data"]["username"];
            $password = isset($data['password'])?$data['password']:$response["data"]["password"];
            $response = update_user($username, $password, $id);
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'OPTIONS':
            http_response_code(200);
            break;
        default:
            http_response_code(405);
            break;
    }
?>