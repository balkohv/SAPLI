<?php
    require('function.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'];
            $plateforme = $data['plateforme'];
            $response = add_movie($name,$plateforme);
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'GET':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['name'])){
                $name = $data['name'];
                $response = get_movie($name);
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
        default:
            http_response_code(405);
            break;
    }
?>