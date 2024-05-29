<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
    header("Content-Type: application/json");
    require('function.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'];
            $nb_subscribers = isset($data['nb_subscribers'])? $data['nb_subscribers']:0;
            $response = add_phobia($name, $nb_subscribers);
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'GET':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($_GET['name'])){
                $name = $_GET['name'];
                $response = get_phobia($name);
            }else{
                $response = get_all_phobia();
            }
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'];
            $response = archive_phobia($name);
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'PATCH':
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data["id"];
            $response = get_phobia_id($id);
            $name = isset($data['name'])?$data['name']:$response["data"]["name"];
            $nb_subscribers = isset($data['nb_subscribers'])?$data['nb_subscribers']:$response["data"]["nb_subscribers"];
            $response = update_phobia($name, $nb_subscribers, $id);
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