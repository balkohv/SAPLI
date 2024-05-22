<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Content-Type: application/json");
    require('function.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $id_movie = $data['id_movie'];
            $id_phobia = $data['id_phobia'];
            $time_code = $data['time_code'];
            $time_end = $data['time_end'];
            $upvote = isset($data['up_vote'])? $data['up_vote']:0;
            $downvote = isset($data['down_vote'])? $data['down_vote']:0;
            $response=add_movie_phobia($id_movie, $id_phobia, $time_code,$time_end, $upvote, $downvote);
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'GET':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['id_movie']) and isset($data['id_phobia'])){
                $id_movie = $data['id_movie'];
                $id_phobia = $data['id_phobia'];
                $response = get_movie_phobia($id_movie, $id_phobia);
            }else{
                $response = get_all_movie_phobia();
            }
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $id_movie = $data['id_movie'];
            $id_phobia = $data['id_phobia'];
            $response = archive_movie_phobia($id_movie, $id_phobia);
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'PATCH':
            $data = json_decode(file_get_contents('php://input'), true);
            $response = get_movie_phobia($data['id_movie'], $data['id_phobia']);
            $id_movie = $data['id_movie'];
            $id_phobia = $data['id_phobia'];
            $time_code = isset($data['time_code'])?$data['time_code']:$response["data"]["time_code"];
            $upvote = isset($data['up_vote'])?$data['up_vote']:$response["data"]["up_vote"];
            $downvote = isset($data['down_vote'])?$data['down_vote']:$response["data"]["down_vote"];
            $response = update_movie_phobia($id_movie, $id_phobia, $time_code, $upvote, $downvote);
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