<?php

$role = "admin";
require_once "head.php";

function get_function() {
    global $db;
    global $SESSIONCREDS;
    $data;

    if (isset($_GET["id"])) {

        if ($_SESSION[$SESSIONCREDS]["role"] == "driver" && $_SESSION[$SESSIONCREDS]["usr_id"] != $_GET["id"]) {
            output_and_die(403, ["error" => "Aizliegta darbība"]);
        }

        $id = prepare_id($_GET["id"]);

        $data = $db->query("SELECT `usr_id`, `usr`, `name`, `fname`, `email`, `phone`, `descr`, `imagehex`,`role` FROM `taxi_usr` WHERE removed = 0 and usr_id = :id", true, 
        new SQLQueryItem(PDO::PARAM_INT, "id", $_GET["id"]))[0];

        if ($_SESSION[$SESSIONCREDS]["usr_id"] == $_GET["id"] || $_SESSION[$SESSIONCREDS]["role"] == "superuser") {
            $data["canpasswd"] = "1";
        }

        if ($_SESSION[$SESSIONCREDS]["usr_id"] == $_GET["id"]) {
            $data["caned"] = "1";
            
        } else if ($_SESSION[$SESSIONCREDS]["role"] == "superuser") {
            if ($data["role"] == "admin" || $data["role"] == "driver") {
                $data["caned"] = "1";
            }
        } else if ($_SESSION[$SESSIONCREDS]["role"] == "admin") {
            if ($data["role"] == "driver") {
                $data["caned"] = "1";
            }
        }

        if ($_SESSION[$SESSIONCREDS]["usr_id"] == $_GET["id"] || $_SESSION[$SESSIONCREDS]["role"] == "admin" || $_SESSION[$SESSIONCREDS]["role"] == "driver") {
            unset($data["role"]);
            unset($data[8]);
        }

        if (empty($data)) {
            output_and_die(404, ["error" => "Nav tāds lietotājs"]);
        }

    } else {
        role_check();

        $data = $db->query("SELECT `usr_id`, `usr`, `name`, `fname`,    `role` FROM `taxi_usr` WHERE removed = 0", true);


        for ($i=0; $i < sizeof($data); $i++) { 
            if ($_SESSION[$SESSIONCREDS]["role"] == "superuser") {
                if ($data[$i]["role"] == "admin" || $data[$i]["role"] == "driver") {
                    $data[$i]["cand"] = "1";
                }
            } else if ($_SESSION[$SESSIONCREDS]["role"] == "admin") {
                if ($data[$i]["role"] == "driver") {
                    $data[$i]["cand"] = "1";
                }
            }
        }
    }

    output_and_die(200, $data);
}


function delete_function() {
    global $db;
    global $SESSIONCREDS;

    $id = prepare_id($_GET["id"]);
    role_check($_GET["id"]);

    try {
        
        $sql = $db->query(
            "UPDATE taxi_usr SET removed = 1 WHERE usr_id = :id", false,
            new SQLQueryItem(PDO::PARAM_STR, "id", $_GET["id"])
        );

        if (empty($sql)) throw new Exception("Neatspējoja lietotāju.");  
        
        output_and_die(200, ["success" => "Tika atspējots"]);   
    } catch (Exception $e) {
        output_and_die(500, ["error" => $e->getMessage()]);
    }

}

function post_function() {
    global $db;
    global $SESSIONCREDS;

    role_check();

    $data = json_decode(file_get_contents("php://input"), true);
    inspectInput($data, true);
    
    $query = "INSERT INTO `taxi_usr`(`usr`, `passwd`, `name`, `fname`, `email`, `phone`, `descr`";

    $hashedpasswd = password_hash($data["passwd"], PASSWORD_BCRYPT);

    if (!isset($data["removeimage"]) && isset($data["imagehex"])) {
        $query = $query.", `imagehex`";
    }

    $query = $query.") VALUES (:usr, :passwd, :name, :fname, :email, :phone, :descr";

    if (!isset($data["removeimage"]) && isset($data["imagehex"])) {
        $query = $query.", :imagehex";
    }

    $query = $query.")";

    try {
        $sql = $db->query(
            $query, false,
            new SQLQueryItem(PDO::PARAM_STR, "usr", $data["usr"]),
            new SQLQueryItem(PDO::PARAM_STR, "name", $data["name"]),
            new SQLQueryItem(PDO::PARAM_STR, "fname", $data["fname"]),
            new SQLQueryItem(PDO::PARAM_STR, "email", $data["email"]),
            new SQLQueryItem(PDO::PARAM_STR, "phone", $data["phone"]),
            new SQLQueryItem(PDO::PARAM_STR, "descr", $data["desc"]),
            new SQLQueryItem(PDO::PARAM_STR, "passwd", $hashedpasswd),

            ( !isset($data["removeimage"]) && isset($data["imagehex"])) ? new SQLQueryItem(PDO::PARAM_STR, "imagehex", $data["imagehex"]) : null
        );

        if (empty($sql)) throw new Exception("Nepievienoja lietotāju.");        output_and_die(200, ["success" => "Dati tika pievienoti"]);   
    } catch (Exception $e) {
        output_and_die(500, ["error" => $e->getMessage()]);
    }

}

function prepare_id($id) {
    if (empty($id) || !is_numeric($id)) {
        output_and_die(400, ["error" => "Nederīgs ID"]);
    }

    $newid = (int)$id;

}

function role_check($id = null) {
    global $db;
    global $SESSIONCREDS;

    if ($id == null) {
        if ($_SESSION[$SESSIONCREDS]["role"] == "superuser" || $_SESSION[$SESSIONCREDS]["role"] == "admin") {
            return null;
        } else {
            output_and_die(403, ["error" => "Aizliegta darbība"]);
        }
    } else {
        $newid = prepare_id($id);

        if ($_SESSION[$SESSIONCREDS]["usr_id"] == $id) {
            return $newid;
        }
        
        $targetusr = $db->query("SELECT `usr`, `role` FROM `taxi_usr` WHERE usr_id = :id", true, new SQLQueryItem(PDO::PARAM_INT, "id", $id))[0];
        
        if (empty($targetusr)) {
            output_and_die(404, ["error" => "Nepastāv lietotājs"]);
        }

        if ($_SESSION[$SESSIONCREDS]["role"] == "superuser") {
            if ($targetusr["role"] == "admin" || $targetusr["role"] == "driver") {
                return $newid;
            }
        } else if ($_SESSION[$SESSIONCREDS]["role"] == "admin") {
            if ($targetusr["role"] == "driver") {
                return $newid;
            }
        }

        output_and_die(403, ["error" => "Aizliegta darbība"]);
    }
  
}

function put_function() {
    global $db;
    global $SESSIONCREDS;

    $id = prepare_id($_GET["id"]);
    role_check($_GET["id"]);
    
    $data = json_decode(file_get_contents("php://input"), true);
    inspectInput($data, false);

    $query = "UPDATE `taxi_usr` SET `usr`=:usr, `name`=:name,`fname`=:fname,`email`=:email,`phone`=:phone,`descr`=:descr ";

    $hashedpasswd;
    if (isset($data["passwd"]) && !empty($data["passwd"]) ) {
        $hashedpasswd = password_hash($data["passwd"], PASSWORD_BCRYPT);
        $query = $query.", `passwd`=:passwd";
    }

    if (isset($data["role"])) {
        $query = $query.", `role`=:role";
    }
    
    if (isset($data["removeimage"])) {
        $query = $query.", `imagehex`=''";
    } else if (isset($data["imagehex"])) {
        $query = $query.", `imagehex`=:imghex";
    }

    $query = $query."  WHERE usr_id = :id";

    try {
        $sql = $db->query(
            $query, false,
            new SQLQueryItem(PDO::PARAM_STR, "usr", $data["usr"]),
            new SQLQueryItem(PDO::PARAM_STR, "name", $data["name"]),
            new SQLQueryItem(PDO::PARAM_STR, "fname", $data["fname"]),
            new SQLQueryItem(PDO::PARAM_STR, "email", $data["email"]),
            new SQLQueryItem(PDO::PARAM_STR, "phone", $data["phone"]),
            new SQLQueryItem(PDO::PARAM_STR, "descr", $data["desc"]),
            new SQLQueryItem(PDO::PARAM_STR, "id", $_GET["id"]),

            ( isset($data["passwd"]) && !empty($data["passwd"]) ) ? new SQLQueryItem(PDO::PARAM_STR, "passwd", $hashedpasswd) : null,

            (isset($data["role"]) && !empty($data["role"])) ? new SQLQueryItem(PDO::PARAM_STR, "role", $data["role"]) : null,

            ( !isset($data["removeimage"]) && isset($data["imagehex"]) && !empty($data["imagehex"])) ? new SQLQueryItem(PDO::PARAM_STR, "imghex", $data["imagehex"]) : null
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
            isset($data["role"]) && empty($data["role"])
        )
        ||
        (
            isset($data["passwd"]) && empty($data["passwd"])
        )
     ) {
        output_and_die(401, ["error" => "Nepareizi ievadīti dati"]);
    }
}