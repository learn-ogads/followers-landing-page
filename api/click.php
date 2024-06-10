<?php

/*
 * Click is used by the frontend to open the offer and add the information in the database as a Click.
 * */

require_once "../services/ServerService.php";
require_once "../services/ValidationService.php";
require_once "../services/DotEnvService.php";
require_once "../database/Session.php";
require_once "../database/Click.php";

(new DotEnvService(__DIR__ . "/../.env"))->load();

header("Content-Type: application/json");

$aff_sub4 = ValidationService::affSub4();

$session = new Session();
$current_session = $session->get(ServerService::getIpAddress(), $aff_sub4);

if (!$current_session) {
    echo "Failed to get the current session, please try refreshing the previous page and try again";
    die();
}

$click = new Click();
$click->create((int)$_GET["offer_id"], $current_session["id"]);

header("Location: " . urldecode($_GET["link"]));
