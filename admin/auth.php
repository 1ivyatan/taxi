<?php

require_once "../db/db.php";
require_once "../assets/strings.php";
require_once "../admin/permscheck.php";

$usr = $_POST["usr"];
$passwd = $_POST["passwd"];

$gatheredusr = $db->query(
    "SELECT * FROM `taxi_usr` WHERE `usr` = :usr", true, 
    new SQLQueryItem(PDO::PARAM_STR, "usr", $usr)
);

// usrname compare
if (empty($gatheredusr)) {
    $_SESSION[$SESSIONNOTIF_ERR] = "Nepareizie dati!";
    header("Location: login.php");
    die;
}

// password comapre
if (password_verify($passwd, $gatheredusr[0]["passwd"])) {
    unset($gatheredusr[0]["passwd"]);
    unset($gatheredusr[0][2]);
    
    $_SESSION[$SESSIONCREDS] = $gatheredusr[0];
    header("Location: index.php");
    die;

} else {
    $_SESSION[$SESSIONNOTIF_ERR] = "Nepareizie dati!";
    header("Location: login.php");
    die;
}