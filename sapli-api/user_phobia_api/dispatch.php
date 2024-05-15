<?php
    require('function.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $id_user = $data['id_user'];
            $id_phobia = $data['id_phobia'];
            $response=add_user_phobia($id_user, $id_phobia);
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'GET':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['id_user']) && isset($data['id_phobia'])){
                $id_user = $data['id_user'];
                $id_phobia = $data['id_phobia'];
                $response = get_user_phobia($id_user, $id_phobia);
            }else{
                $response = get_all_user_phobia();
            }
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $id_user = $data['id_user'];
            $id_phobia = $data['id_phobia'];
            $response = archive_user_phobia($id_user, $id_phobia);
            deliver_response($response["code"], $response["message"], $response["data"]);
            break;
        default:
            http_response_code(405);
            break;
    }
?>