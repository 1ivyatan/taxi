<?php

require_once "../assets/strings.php";
require_once "../db/db.php";

if (!isset($_SESSION)) {
    session_start();
}

function is_logged_in($redirect) {
    global $SESSIONCREDS;
    global $db;

    if (!isset($_SESSION[$SESSIONCREDS]) || empty($_SESSION[$SESSIONCREDS])) {
        header("Location: login.php");
        die;
    }

    $profile = $db->query("SELECT `usr_id`, `usr`, `name`, `fname`, `email`, `phone`, `descr`, `imagehex`, `role`, `removed` FROM `taxi_usr` WHERE usr_id = :id",true,  new SQLQueryItem(PDO::PARAM_INT, "id", $_SESSION[$SESSIONCREDS]["usr_id"]))[0];

    if ($profile["removed"] == "1") {
        if ($redirect == true) {
            header("Location: destroy.php");
            die;
        } else {
            return false;
        }
    }

    $_SESSION[$SESSIONCREDS] = $profile;
    return true;
}

function is_logged_out() {
    global $SESSIONCREDS;

    if (isset($_SESSION[$SESSIONCREDS]) && !empty($_SESSION[$SESSIONCREDS])) {
        header("Location: index.php");
        die;
    }
}
