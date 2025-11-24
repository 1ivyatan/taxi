<?php

require_once "head.php";

function get_function() {
    global $db;
    global $SESSIONCREDS;

    $data = $db->query("SELECT `usr_id`, `usr`, `name`, `fname`, `email`, `phone`, `descr`, `imagehex` FROM `taxi_usr` WHERE usr_id = :id", true, 
        new SQLQueryItem(PDO::PARAM_INT, "id", $_SESSION[$SESSIONCREDS]["usr_id"]))[0];

    if (empty($data)) {
        output_and_die(404, ["error" => "Nav tāds pieteikums"]);
    } else output_and_die(200, $data);

}

function put_function() {
    global $db;
    global $SESSIONCREDS;

    $data = json_decode(file_get_contents("php://input"), true);
    inspectInput($data, true);

    $query = "UPDATE `taxi_usr` SET `usr`=:usr,`name`=:name,`fname`=:fname,`email`=:email,`phone`=:phone,`descr`=:descr";

    $hashedpasswd;
    if (isset($data["passwd"]) && !empty($data["passwd"]) ) {
        $hashedpasswd = password_hash($data["passwd"], PASSWORD_BCRYPT);
        $query = $query.", `passwd`=:passwd";
    }

    if (isset($data["removeimage"])) {
        $query = $query.", `imagehex`=''";
    } else if (isset($data["imagehex"])) {
        $query = $query.", `imagehex`=:imagehex";
    }

    $query = $query." WHERE `usr_id` = :id";

    try {
        $sql = $db->query(
            $query, false,
            new SQLQueryItem(PDO::PARAM_STR, "id", $_SESSION[$SESSIONCREDS]["usr_id"]),
            new SQLQueryItem(PDO::PARAM_STR, "usr", $data["usr"]),
            new SQLQueryItem(PDO::PARAM_STR, "name", $data["name"]),
            new SQLQueryItem(PDO::PARAM_STR, "fname", $data["fname"]),
            new SQLQueryItem(PDO::PARAM_STR, "email", $data["email"]),
            new SQLQueryItem(PDO::PARAM_STR, "phone", $data["phone"]),
            new SQLQueryItem(PDO::PARAM_STR, "descr", $data["desc"]),

            ( isset($data["passwd"]) && !empty($data["passwd"]) ) ? new SQLQueryItem(PDO::PARAM_STR, "passwd", $hashedpasswd) : null,

            ( !isset($data["removeimage"]) && isset($data["imagehex"]) && !empty($data["imagehex"])) ? new SQLQueryItem(PDO::PARAM_STR, "imagehex", $data["imagehex"]) : null
        );

        if (empty($sql)) throw new Exception("Nenomainīja datus");
        output_and_die(200, ["success" => "Dati tika nomainīti"]);   
    } catch (Exception $e) {
        output_and_die(500, ["error" => $e->getMessage()]);
    }
}




function inspectInput($data, $all = false) {
    if (
        (
            !isset($data["usr"]) || empty($data["usr"])
        )
        ||
        (
            !isset($data["name"]) || empty($data["name"])
        )
        ||
        (
            !isset($data["fname"]) || empty($data["fname"])
        )
        ||
        (
            !isset($data["email"]) || empty($data["email"])
        )
        ||
        (
            !isset($data["phone"]) || empty($data["phone"])
        )
    ) {
        output_and_die(401, ["error" => "Nepareizi ievadīti dati"]);
    }

    if ($all == false ) return;

    if (
        (
            isset($data["desc"]) && empty($data["desc"])
        )
        ||
        (
            isset($data["passwd"]) && empty($data["passwd"])
        )
     ) {
        output_and_die(401, ["error" => "Nepareiziievadīt i dati"]);
    }
}