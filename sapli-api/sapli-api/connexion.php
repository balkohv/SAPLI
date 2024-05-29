<?php
    require('jwt_utils.php');
    require('deliverResponse.php');

    if(get_bearer_token() == null){
        deliver_response(401, "Token needed", null);
        exit();
    }
    $url = "http://192.168.1.59/SAPLI/sapli-auth/dispatch.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer '. get_bearer_token()
    ));
    
    
    $api_output =  curl_exec($ch);
    $json_output = json_decode($api_output);
    $output = $json_output?$json_output:$api_output;
    curl_close($ch);
    if($output->status_code == 200){
        $username ="root";
        $pwd = "Micheldu15*";  
        try {
            $db = new PDO('mysql:host=192.168.1.59;port=3306;dbname=phobia_warning',$username,$pwd);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    }elseif (!$output){
        deliver_response(500, "api authentification error", null);
        exit();
    }else{
        deliver_response($output->status_code, $output->status_message, $output->data);
        exit();
    }

?>