<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
require ('function.php');
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $id_user = $data['id_user'];
        $id_phobias = $data['phobias'];
        var_dump($id_phobias);
        $response = add_user_phobia($id_phobias, $id_user);
        deliver_response($response["code"], $response["message"], $response["data"]);
        break;
    case 'GET':
        if (isset($_GET['id_movie']) && isset($_GET['id_user'])) {
            $response = get_time_code($_GET['id_movie'], $_GET['id_user']);
        }elseif (isset($_GET['id_user'])) {
            $id_user = $_GET['id_user'];
            $response = get_user_phobia($id_user);
        } else {
            $response = get_all_user_phobia();
        }
        deliver_response($response["code"], $response["message"], $response["data"]);
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $id_user = $data['id_user'];
        $id_phobia = $data['ids_phobias'];
        $response = archive_user_phobia($id_user, $id_phobia);
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