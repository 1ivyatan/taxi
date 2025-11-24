<?php

require_once "../assets/strings.php";
require_once "../admin/permscheck.php";

is_logged_in(false);

session_destroy();
header("Location: login.php");
die;