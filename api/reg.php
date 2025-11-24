<?php

require_once "head.php";


function post_function() {
    global $db;
    global $SESSIONCREDS;

    $data = json_decode(file_get_contents("php://input"), true);
    inspect_input($data);

    if (
        !isset($data["date"]) || empty($data["status"]) || (
                strtotime($data["date"]) <= strtotime("now")
            )
    ) {
        output_and_die(401, ["error" => "Nepareizi ievadīti dati"]);
    }

    $query = "INSERT INTO `taxi_reservations`(`phone`, `email`, `name`, `fname`, `notes`, `date`, `status`) VALUES (:phone, :email, :name, :fname, :notes, :date, :status)";

    try {
        $sql = $db->query(
            $query, false,
            new SQLQueryItem(PDO::PARAM_STR, "phone", $data["phone"]),
            new SQLQueryItem(PDO::PARAM_STR, "email", $data["email"]),
            new SQLQueryItem(PDO::PARAM_STR, "name", $data["name"]),
            new SQLQueryItem(PDO::PARAM_STR, "fname", $data["fname"]),
            new SQLQueryItem(PDO::PARAM_STR, "notes", $data["notes"]),
            new SQLQueryItem(PDO::PARAM_STR, "date", $data["date"]),
            new SQLQueryItem(PDO::PARAM_STR, "status", $data["status"])
        );

        if (empty($sql)) throw new Exception("Nepievienoja pieteikumu.");        output_and_die(200, ["success" => "Tika pievienota"]);   
    } catch (Exception $e) {
        output_and_die(500, ["error" => $e->getMessage()]);
    }

}

function put_function() {
    global $db;
    global $SESSIONCREDS;

    $id = prepare_id($_GET["id"]);
    $data = json_decode(file_get_contents("php://input"), true);
    inspect_input($data);

    if (
        !isset($data["date"]) || empty($data["status"]) || (
                strtotime($data["date"]) <= strtotime($db->query("SELECT `created` FROM `taxi_reservations` WHERE `res_id` = :id", true, new SQLQueryItem(PDO::PARAM_STR, "id", $_GET["id"]))[0]["created"])
            )
    ) {
        output_and_die(401, ["error" => "Nepareizi ievadīti dati"]);
    }
    

    $query = "UPDATE `taxi_reservations` SET `phone`=:phone,`email`=:email,`name`=:name,`fname`=:fname,`notes`=:notes,`date`=:date,`status`=:status,`edited`=now() WHERE `res_id` = :id";

    try {
        $sql = $db->query(
            $query, false,
            new SQLQueryItem(PDO::PARAM_STR, "phone", $data["phone"]),
            new SQLQueryItem(PDO::PARAM_STR, "email", $data["email"]),
            new SQLQueryItem(PDO::PARAM_STR, "name", $data["name"]),
            new SQLQueryItem(PDO::PARAM_STR, "fname", $data["fname"]),
            new SQLQueryItem(PDO::PARAM_STR, "notes", $data["notes"]),
            new SQLQueryItem(PDO::PARAM_STR, "date", $data["date"]),
            new SQLQueryItem(PDO::PARAM_STR, "status", $data["status"]),
            new SQLQueryItem(PDO::PARAM_STR, "id", $_GET["id"])
        );

        if (empty($sql)) throw new Exception("Nevarēja rediģēt.");        output_and_die(200, ["success" => "Tika rediģēta"]);   
    } catch (Exception $e) {
        output_and_die(500, ["error" => $e->getMessage()]);
    }




}

function get_function() {
    global $db;
    global $SESSIONCREDS;
    $data;

    if (isset($_GET["id"])) {
        $id = prepare_id($_GET["id"]);

        $data = $db->query("SELECT `res_id`, `phone`, `email`, `name`, `fname`, `notes`, `date`, `status`,  date_format(`created`, '%k:%i %d.%m.%Y') as created, date_format(`edited`, '%k:%i %d.%m.%Y') as edited FROM `taxi_reservations` WHERE `res_id` = :id", true, 
        new SQLQueryItem(PDO::PARAM_INT, "id", $_GET["id"]))[0];

        if (empty($data)) {
            output_and_die(404, ["error" => "Nav tāds pieteikums"]);
        }
    } else if (isset($_GET["query"]) && !empty(isset($_GET["query"]))) {
        $data = $db->query("SELECT `res_id`, `phone`, `name`, `fname`, `status` FROM `taxi_reservations` WHERE `removed` = 0 and `phone` like :phone or `name` like :name or `fname` like :fname or `email` like :email order by res_id desc", true, 
        new SQLQueryItem(PDO::PARAM_STR, "phone", "%".$_GET["query"]."%"),
        new SQLQueryItem(PDO::PARAM_STR, "name", "%".$_GET["query"]."%"),
        new SQLQueryItem(PDO::PARAM_STR, "fname", "%".$_GET["query"]."%"),
        new SQLQueryItem(PDO::PARAM_STR, "email", "%".$_GET["query"]."%")
        );
    } else {
        $data = $db->query("SELECT `res_id`, `phone`, `name`, `fname`, `status` FROM `taxi_reservations` WHERE `removed` = 0 order by res_id desc", true);
    }
    
    output_and_die(200, $data);
}


function delete_function() {
    global $db;
    global $SESSIONCREDS;

    $id = prepare_id($_GET["id"]);

    try {
        
        $sql = $db->query(
            "UPDATE taxi_reservations SET removed = 1 WHERE res_id = :id", false,
            new SQLQueryItem(PDO::PARAM_STR, "id", $_GET["id"])
        );

        if (empty($sql)) throw new Exception("Nenoņēma pieteikumu.");  
        
        output_and_die(200, ["success" => "Tika noņemts."]);   
    } catch (Exception $e) {
        output_and_die(500, ["error" => $e->getMessage()]);
    }

}


function inspect_input($data) {
    if (
        (
            !isset($data["name"]) || empty($data["name"])
        ) ||
        (
            !isset($data["fname"]) || empty($data["fname"])
        ) ||
        (
            !isset($data["phone"]) || empty($data["phone"])
        )  ||
        (
            !isset($data["email"]) || empty($data["email"])
        )  ||
        (
            !isset($data["notes"]) || empty($data["notes"])
        ) ||
        (
            !isset($data["status"]) || empty($data["status"])
        )
    ) {
        output_and_die(401, ["error" => "Nepareizi ievadīti dati"]);
    }
}


function prepare_id($id) {
    if (empty($id) || !is_numeric($id)) {
        output_and_die(400, ["error" => "Nederīgs ID"]);
    }

    $newid = (int)$id;

}