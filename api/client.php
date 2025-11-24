<?php


require_once "../assets/strings.php";
require_once "../db/db.php";



header("Content-Type: application/json; charset=UTF-8");




if ($_SERVER['REQUEST_METHOD'] == "POST") {
    global $db;

    $data = json_decode(file_get_contents("php://input"), true);
    inspect_input($data);

    if (
        !isset($data["date"]) || (
                strtotime($data["date"]) <= strtotime("now")
            )
    ) {
        output_and_die(401, ["error" => "Nepareizi ievadīti dati"]);
    }

    $query = "INSERT INTO `taxi_reservations`(`phone`, `email`, `name`, `fname`, `notes`, `date`) VALUES (:phone, :email, :name, :fname, :notes, :date)";

    try {
        $sql = $db->query(
            $query, false,
            new SQLQueryItem(PDO::PARAM_STR, "phone", $data["phone"]),
            new SQLQueryItem(PDO::PARAM_STR, "email", $data["email"]),
            new SQLQueryItem(PDO::PARAM_STR, "name", $data["name"]),
            new SQLQueryItem(PDO::PARAM_STR, "fname", $data["fname"]),
            new SQLQueryItem(PDO::PARAM_STR, "notes", $data["notes"]),
            new SQLQueryItem(PDO::PARAM_STR, "date", $data["date"])
        );

        if (empty($sql)) throw new Exception("Nepievienoja pieteikumu.");        
        output_and_die(200, ["success" => "Tika pievienota"]);   
    } catch (Exception $e) {
        output_and_die(500, ["error" => $e->getMessage()]);
    }

} else {
    output_and_die(405, ["error" => "Neatbalstīta metode"]);
}

function output_and_die($code, $message) {
    http_response_code($code);
    echo json_encode($message);
    exit;
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
        )
    ) {
        output_and_die(401, ["error" => "Nepareizi ievadīti dati"]);
    }
}