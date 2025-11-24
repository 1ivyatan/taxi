<?php

require_once "head.php";

function get_function() {
    global $db;
    global $SESSIONCREDS;
    $data;

    if (isset($_GET["id"])) {
        $id = prepare_id($_GET["id"]);

        $data = $db->query("SELECT `car_id`, `license`, `model`, `seats`, `imagehex`, date_format(`created`, '%k:%i %d.%m.%Y') as created, date_format(`edited`, '%k:%i %d.%m.%Y') as edited, hidden FROM `taxi_cars` WHERE `removed` = 0 and car_id = :id", true, 
        new SQLQueryItem(PDO::PARAM_INT, "id", $_GET["id"]))[0];

        if (empty($data)) {
            output_and_die(404, ["error" => "Nav tāda mašīna"]);
        }
    } else {
        $data = $db->query("SELECT `car_id`, `license`, `model` FROM `taxi_cars` WHERE `removed` = 0", true);
    }
    
    output_and_die(200, $data);
}

function put_function() {
    global $db;
    global $SESSIONCREDS;

    $id = prepare_id($_GET["id"]);
    $data = json_decode(file_get_contents("php://input"), true);
    inspect_input($data);

    $query = "UPDATE `taxi_cars` SET `license`=:license,`model`=:model,`seats`=:seats, edited=now(), hidden=:hidden";

    if ( isset($data["imagehex"]) || !empty($data["imagehex"]) ) {
        echo "123";
        $query = $query.", `imagehex`=:imagehex";
    }

    $query = $query." WHERE `car_id` = :id";
    
    try {
        $sql = $db->query(
            $query, false,
            new SQLQueryItem(PDO::PARAM_STR, "model", $data["model"]),
            new SQLQueryItem(PDO::PARAM_STR, "license", $data["license"]),
            new SQLQueryItem(PDO::PARAM_INT, "seats", $data["seats"]),
            new SQLQueryItem(PDO::PARAM_INT, "id", $_GET["id"]),
            ( isset($data["imagehex"]) && !empty($data["imagehex"]) ) ? new SQLQueryItem(PDO::PARAM_STR, "imagehex", $data["imagehex"]) : null,
            new SQLQueryItem(PDO::PARAM_BOOL, "hidden", isset($data["hidden"]))
        );

        if (empty($sql)) throw new Exception("Netika rediģēta mašīna.");        output_and_die(200, ["success" => "Tika rediģēta"]);   
    } catch (Exception $e) {
        output_and_die(500, ["error" => $e->getMessage()]);
    }

}

function post_function() {
    global $db;
    global $SESSIONCREDS;

    $data = json_decode(file_get_contents("php://input"), true);
    inspect_input($data);


    if ( !isset($data["imagehex"]) || empty($data["imagehex"]) ) {
        output_and_die(401, ["error" => "Nepareizi ievadīti dati"]);
    }

    $query = "INSERT INTO `taxi_cars`(`license`, `model`, `seats`, `imagehex`, `hidden`) VALUES (:license, :model, :seats, :imagehex, :hidden)";

    try {
        $sql = $db->query(
            $query, false,
            new SQLQueryItem(PDO::PARAM_STR, "model", $data["model"]),
            new SQLQueryItem(PDO::PARAM_STR, "license", $data["license"]),
            new SQLQueryItem(PDO::PARAM_INT, "seats", $data["seats"]),
            new SQLQueryItem(PDO::PARAM_STR, "imagehex", $data["imagehex"]),
            new SQLQueryItem(PDO::PARAM_BOOL, "hidden", isset($data["hidden"]))
        );

        if (empty($sql)) throw new Exception("Nepievienoja mašīnu.");        output_and_die(200, ["success" => "Tika pievienota"]);   
    } catch (Exception $e) {
        output_and_die(500, ["error" => $e->getMessage()]);
    }
}

function inspect_input($data) {
    if (
        (
            !isset($data["model"]) || empty($data["model"])
        )
        ||
        (
            !isset($data["license"]) || empty($data["license"])
        )
        ||
        (
            !isset($data["seats"]) || empty($data["seats"]) || (
                intval($data["seats"]) < 1 || intval($data["seats"]) > 16
            )
        )
    ) {
        output_and_die(401, ["error" => "Nepareizi ievadīti dati"]);
    }
    /*
    if (
        (
            !isset($data["model"]) || empty($data["model"])
        )
        ||
        (
            !isset($data["license"]) || empty($data["license"])
        )
        ||
        (
            !isset($data["imagehex"]) || empty($data["imagehex"])
        )
        ||
        (
            !isset($data["seats"]) || empty($data["seats"]) || (
                intval($data["seats"]) < 1 || intval($data["seats"]) > 16
            )
        )
    ) {
        output_and_die(401, ["error" => "Nepareizi ievadīti dati"]);
    }*/
}

function prepare_id($id) {
    if (empty($id) || !is_numeric($id)) {
        output_and_die(400, ["error" => "Nederīgs ID"]);
    }

    $newid = (int)$id;

}

function delete_function() {
    global $db;
    global $SESSIONCREDS;

    $id = prepare_id($_GET["id"]);

    try {
        
        $sql = $db->query(
            "UPDATE taxi_cars SET removed = 1 WHERE car_id = :id", false,
            new SQLQueryItem(PDO::PARAM_STR, "id", $_GET["id"])
        );

        if (empty($sql)) throw new Exception("Nenoņēma mašīnu.");  
        
        output_and_die(200, ["success" => "Tika noņemts."]);   
    } catch (Exception $e) {
        output_and_die(500, ["error" => $e->getMessage()]);
    }

}