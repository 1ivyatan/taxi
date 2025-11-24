<?php
header("Content-Type: application/json; charset=UTF-8");

require_once "../admin/permscheck.php";

if (!is_logged_in(false)) {
    output_and_die(401, ["error" => "Nepieciešama autorizācija"]);
}

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        get_function();
        break;

    case 'POST':
        post_function();
        break;

    case 'PUT':
        put_function();
        break;

    case 'DELETE':
        delete_function();
        break;
    
    default:
        output_and_die(405, ["error" => "Neatbalstīta metode"]);
        break;
        
}

function output_and_die($code, $message) {
    http_response_code($code);
    echo json_encode($message);
    exit;
}
