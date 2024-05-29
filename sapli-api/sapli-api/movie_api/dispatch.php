<?php
    //corss origin
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
    header("Content-Type: application/json");
    require('function.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'];
            $plateforme = $data['plateform'];
            $duration = $data['duration'];
            $episode = isset($data['episode'])?$data['episode']:null;
            $response = add_movie($name, $plateforme, $duration, $episode);
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'GET':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($_GET['name']) and isset($_GET['plateform'])){
                $name = $_GET['name'];
                $plateforme = $_GET['plateform'];
                if(isset($_GET['duration']) and isset($_GET['episode'])){
                    $episode = $_GET['episode'];
                    $duration = $_GET['duration'];
                    $response = get_serie($name,$episode,$plateforme,$duration);
                }else{
                    $response = get_movie($name,$plateforme);
                }
            }else{
                $response = get_all_movie();
            }
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'];
            $response = archive_movie($name);
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'PATCH':
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data["id"];
            $response = get_movie_id($id);
            $name = isset($data['name'])?$data['name']:$response["data"]["name"];
            $plateforme = isset($data['plateforme'])?$data['plateforme']:$response["data"]["plateforme"];
            $response = update_movie($name, $plateforme, $id);
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