<?php
ini_set("display_errors", 1);
require ('../connexion.php');

function add_user_phobia($id_phobia, $id_user)
{
    global $db;
    $code = 500;
    $message = 'erreur serveur';
    $req1 = 'INSERT INTO user_phobia (id_phobia, id_user) SELECT p.id_phobia, '. $id_user .' as id_user FROM phobia p WHERE p.id_phobia in (';
    $req1_end =') and p.id_phobia NOT IN ( SELECT id_phobia FROM user_phobia where id_user = '. $id_user .' and id_phobia in(';
    $req2 = 'update phobia set nb_subscribers = nb_subscribers + 1 where id_phobia in(';
    $req2_end =') and id_phobia NOT IN ( SELECT id_phobia FROM user_phobia where id_user = '. $id_user .' and id_phobia in(';
    if($id_phobia == null or count($id_phobia) == 0){
        $code = 200;
        $message = 'aucune phobia a ajouter';
        return array("code" => $code, "message" => $message, "data" => null);
    }
    foreach ($id_phobia as $id) {
        $req1 .= $id .',';
        $req1_end .=  $id .',';
        $req2 .= $id.',';
        $req2_end .=  $id .',';
    }
    $req2 = substr($req2, 0, -1);
    $req2_end = substr($req2_end, 0, -1);
    $req2_end .= '));';
    $req2 .= $req2_end;
    $req2 = $db->prepare($req2);
    $req2->execute();
    $req1= substr($req1, 0, -1);
    $req1_end = substr($req1_end, 0, -1);
    $req1_end .= '));';
    $req1 .= $req1_end;
    $req1 = $db->prepare($req1);
    $req1->execute();
    var_dump($req2);
    if ($req1 && $req2) {
        $code = 200;
        $message = 'lien cree';
    } else {
        $code = 500;
        $message = 'erreur serveur';
    }
    return array("code" => $code, "message" => $message, "data" => null);
}
function get_user_phobia($id_user)
{
    global $db;
    $code = 500;
    $message = 'erreur serveur';
    $req1 = $db->prepare('SELECT * FROM user_phobia WHERE id_user = :id_user');
    $req1->execute(
        array(
            'id_user' => $id_user
        )
    );
    $user_phobia = $req1->fetchAll();
    if (isset($user_phobia[0]['id_phobia'])) {
        $code = 200;
        $message = 'lien trouve';
    } else {
        $code = 404;
        $message = 'lien non trouve';
    }
    return array("code" => $code, "message" => $message, "data" => $user_phobia);
}

function get_all_user_phobia()
{
    global $db;
    $code = 500;
    $message = 'erreur serveur';
    $req1 = $db->prepare('SELECT * FROM user_phobia');
    $req1->execute();
    $user_phobia = $req1->fetchAll();
    if ($user_phobia) {
        $code = 200;
        $message = 'lien trouve';
    } else {
        $code = 404;
        $message = 'lien non trouve';
    }
    return array("code" => $code, "message" => $message, "data" => $user_phobia);
}

function archive_user_phobia($id_user, $ids_phobias)
{
    global $db;
    $code = 500;
    $message = 'erreur serveur';
    if(count($ids_phobias) == 0){
        $code = 400;
        $message = 'aucune phobia a archiver';
        return array("code" => $code, "message" => $message, "data" => null);
    }
    $req1 = 'DELETE FROM user_phobia WHERE id_user = '. $id_user .' and id_phobia in (select u.id_phobia from user_phobia u join phobia p on u.id_phobia = p.id_phobia where u.id_user = '.$id_user.' and u.id_phobia in (';
    $req2 = 'update phobia set nb_subscribers = nb_subscribers - 1 where id_phobia in( select u.id_phobia from user_phobia u join phobia p on u.id_phobia = p.id_phobia where u.id_user = '.$id_user.' and u.id_phobia in (';
    foreach ($ids_phobias as $id) {
        $req1 .= $id . ',';
        $req2 .= $id.',';
    }
    $req2 = substr($req2, 0, -1);
    $req2.= '));';
    $req2 = $db->prepare($req2);
    $req2->execute();
    $req1 = substr($req1,0,-1);
    $req1 .= '));';
    var_dump($req1);
    $req1 = $db->prepare($req1);
    $req1->execute();
    if ($req1 && $req2) {
        if ($req1->rowCount() > 0) {
            $code = 200;
            $message = 'lien archive';
        } else {
            $code = 200;
            $message = 'lien non trouve';
        }
    } else {
        $code = 500;
        $message = 'erreur serveur';
    }
    return array("code" => $code, "message" => $message, "data" => null);
}

function get_time_code($id_movie, $id_user)
{
    global $db;
    $code = 500;
    $message = "";
    $req1 = $db->prepare('SELECT p.name , m.time_code , m.time_code_end , m.up_vote , m.down_vote , p.id_phobia
            FROM movie_phobia m 
            join phobia p on m.id_phobia = p.id_phobia 
            join user_phobia u on u.id_phobia = p.id_phobia 
            WHERE m.id_movie = :id_movie and u.id_user = :id_user and m.archive = 0 and p.archive = 0');
    $req1->execute(
        array(
            'id_user' => $id_user,
            'id_movie' => $id_movie
        )
    );
    if ($req1) {
        if ($req1->rowCount() > 0) {
            $code = 200;
            $message = 'ok';
            $data = $req1->fetchAll();
        } else {
            $code = 200;
            $message = 'no data';
            $data = null;
        }
    } else {
        $code = 500;
        $message = 'erreur serveur';
        $data = null;
    }
    return array('code' => $code, 'message' => $message, 'data' => $data);
}

?>