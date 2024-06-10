<?php
ini_set("display_errors", 1);
require ('../connexion.php');

function add_movie_phobia($id_user, $id_movie, $id_phobia, $time_code, $time_code_end, $upvote, $downvote)
{
    global $db;
    $code = 500;
    $message = 'erreur serveur';
    $req1 = $db->prepare('INSERT INTO movie_phobia (id_movie, id_phobia, time_code, time_code_end, up_vote, down_vote) VALUES(:id_movie, :id_phobia, :time_code, :time_code_end, :up_vote, :down_vote)');
    $req_point = $db->prepare('UPDATE user SET points = points + 10 WHERE id_user = :id_user');
    $req_point->execute(
        array(
            'id_user' => $id_user
        )
    );
    if ($req1) {
        $code = 401;
        $message = 'parametre manquant ou invalide';
        try {
            $req1->execute(
                array(
                    'id_movie' => $id_movie,
                    'id_phobia' => $id_phobia,
                    'time_code' => $time_code,
                    'time_code_end' => $time_code_end,
                    'up_vote' => $upvote,
                    'down_vote' => $downvote
                )
            );
            if ($req1) {
                $code = 201;
                $message = 'lien cree';
            }
        } catch (Exception $e) {
            if ($e->getCode() == 23000) {
                $code = 409;
                $message = 'lien deja existant';
            } else {
                $code = 500;
                $message = 'erreur serveur:' . $e->getmessage();
            }
        }
    }
    return array("code" => $code, "message" => $message, "data" => null);
}
function get_movie_phobia($id_movie, $id_phobia)
{
    global $db;
    $code = 500;
    $message = 'erreur serveur';
    $req1 = $db->prepare('SELECT * FROM movie_phobia WHERE id_movie = :id_movie and id_phobia = :id_phobia and archive = 0');
    $req1->execute(
        array(
            'id_movie' => $id_movie,
            'id_phobia' => $id_phobia,
        )
    );
    $movie_phobia = $req1->fetchAll();
    if (isset($movie_phobia['id_movie'])) {
        $code = 200;
        $message = 'lien trouve';
    } else {
        $code = 404;
        $message = 'lien non trouve';
    }
    return array("code" => $code, "message" => $message, "data" => $movie_phobia);
}

function get_all_movie_phobia()
{
    global $db;
    $code = 500;
    $message = 'erreur serveur';
    $req1 = $db->prepare('SELECT * FROM movie_phobia where archive = 0');
    $req1->execute();
    $movie_phobia = $req1->fetchall();
    if (isset($movie_phobia)) {
        $code = 200;
        $message = 'lien trouve';
    } else {
        $code = 404;
        $message = 'lien non trouve';
    }
    return array("code" => $code, "message" => $message, "data" => $movie_phobia);
}

function archive_movie_phobia($id_movie, $id_phobia)
{
    global $db;
    $code = 500;
    $message = 'erreur serveur';
    $req1 = $db->prepare('UPDATE movie_phobia SET archive = NOT(archive) WHERE id_movie = :id_movie and id_phobia = :id_phobia');
    $req1->execute(
        array(
            'id_movie' => $id_movie,
            'id_phobia' => $id_phobia,
        )
    );
    if ($req1) {
        if ($req1->rowCount() > 0) {
            $code = 200;
            $message = 'lien archive';
        } else {
            $code = 400;
            $message = 'lien non trouve';
        }
    } else {
        $code = 500;
        $message = 'erreur serveur';
    }
    return array("code" => $code, "message" => $message, "data" => null);
}

function update_movie_phobia($id_movie, $id_phobia, $time_code, $upvote, $downvote)
{
    global $db;
    $code = 500;
    $message = 'erreur serveur';
    $req1 = $db->prepare('UPDATE movie_phobia SET time_code = :time_code, up_vote = :upvote, down_vote = :downvote WHERE id_movie = :id_movie and id_phobia = :id_phobia ');
    $req1->execute(
        array(
            'id_movie' => $id_movie,
            'id_phobia' => $id_phobia,
            'time_code' => $time_code,
            'upvote' => $upvote,
            'downvote' => $downvote,
        )
    );
    if ($req1) {
        if ($req1->rowCount() > 0) {
            $code = 200;
            $message = 'lien modifie';
        } else {
            $code = 400;
            $message = 'lien non trouve';
        }
    } else {
        $code = 500;
        $message = 'erreur serveur';
    }
    return array("code" => $code, "message" => $message, "data" => null);
}

function vote($id_movie, $id_phobia, $id_user, $time_code, $type)
{
    global $db;
    $code = 500;
    $message = 'erreur serveur';
    $flag = false;
    $select_vote = $db->prepare('SELECT * FROM vote WHERE id_user = :id_user and id_movie = :id_movie and id_phobia = :id_phobia and time_start = :time_code');
    $select_vote->execute(
        array(
            'id_movie' => $id_movie,
            'id_phobia' => $id_phobia,
            'id_user' => $id_user,
            'time_code' => $time_code
        )
    );
    $select_vote_fetch = $select_vote->fetch(PDO::FETCH_ASSOC);
    var_dump($select_vote_fetch);
    if ($select_vote->rowCount() > 0 and $select_vote_fetch["vote"] == $type) {
        $del_vote = $db->prepare('DELETE FROM vote WHERE id_user = :id_user and id_movie = :id_movie and id_phobia = :id_phobia and time_start = :time_code');
        $del_vote->execute(
            array(
                'id_movie' => $id_movie,
                'id_phobia' => $id_phobia,
                'id_user' => $id_user,
                'time_code' => $time_code
            )
        );
        if ($del_vote->rowCount() > 0) {
            $message = 'vote supprime';
        } else {
            $message = 'erreur serveur';
        }
        $flag = true;
        $type = $type == "up" ? "down" : "up";
    } elseif ($select_vote->rowCount() > 0 and $select_vote_fetch["vote"] != $type) {
        $update_vote = $db->prepare('UPDATE vote SET vote = :type WHERE id_user = :id_user and id_movie = :id_movie and id_phobia = :id_phobia and time_start = :time_code');
        $update_vote->execute(
            array(
                'id_movie' => $id_movie,
                'id_phobia' => $id_phobia,
                'id_user' => $id_user,
                'time_code' => $time_code,
                'type' => $type
            )
        );
        if ($update_vote->rowCount() > 0) {
            $message = 'vote modifie';
        } else {
            $message = 'erreur serveur';
        }
        $flag = true;
    } else {
        $req3 = $db->prepare('INSERT INTO vote (id_user, id_movie, id_phobia, time_start, vote) VALUES(:id_user, :id_movie, :id_phobia, :time_code, :type)');
        $req3->execute(
            array(
                'id_user' => $id_user,
                'id_movie' => $id_movie,
                'id_phobia' => $id_phobia,
                'time_code' => $time_code,
                'type' => $type
            )
        );
        if ($req3) {
            $message = 'vote enregistre';
        } else {
            $code = 500;
            $message = 'erreur serveur';
        }
        $flag = true;
    }
    if ($flag) {
        $req1 = $db->prepare('SELECT * FROM movie_phobia WHERE id_movie = :id_movie and id_phobia = :id_phobia and time_code = :time_code');
        $req1->execute(
            array(
                'id_movie' => $id_movie,
                'id_phobia' => $id_phobia,
                'time_code' => $time_code
            )
        );
        $movie_phobia = $req1->fetch();
        if ($movie_phobia) {
            if ($type == 'up') {
                $req2 = $db->prepare('UPDATE movie_phobia SET up_vote = up_vote + 1 WHERE id_movie = :id_movie and id_phobia = :id_phobia and time_code = :time_code');
                $req2->execute(
                    array(
                        'id_movie' => $id_movie,
                        'id_phobia' => $id_phobia,
                        'time_code' => $time_code
                    )
                );
                if ($req2) {
                    $code = 200;
                    $message = 'upvote';
                } else {
                    $code = 500;
                    $message = 'erreur serveur';
                }
            } elseif ($type == 'down') {
                $req2 = $db->prepare('UPDATE movie_phobia SET down_vote = down_vote + 1 WHERE id_movie = :id_movie and id_phobia = :id_phobia and time_code = :time_code');
                $req2->execute(
                    array(
                        'id_movie' => $id_movie,
                        'id_phobia' => $id_phobia,
                        'time_code' => $time_code
                    )
                );
                if ($req2) {
                    $code = 200;
                    $message = 'downvote';
                } else {
                    $code = 500;
                    $message = 'erreur serveur';
                }
            } else {
                $code = 400;
                $message = 'mauvais type';
            }
        } else {
            $code = 404;
            $message = 'lien non trouve';
        }
    }
    return array("code" => $code, "message" => $message, "data" => null);
}

?>