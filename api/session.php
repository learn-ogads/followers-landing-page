<?php

/*
 * Session is used by the frontend to set the aff_sub4 and the follower amount chosen.
 * If they revisit this endpoint we check if the request follower amount has changed.
 * If it has, then we need to update the session.
 * */

require_once "../services/ServerService.php";
require_once "../services/ValidationService.php";
require_once "../services/DotEnvService.php";
require_once "../database/Session.php";

(new DotEnvService(__DIR__ . "/../.env"))->load();

header("Content-Type: application/json");

$aff_sub4 = ValidationService::affSub4();
$username = ValidationService::username();
$platform = ValidationService::platform();
$followers = ValidationService::followers();

$session = new Session();
$current_session = $session->get(ServerService::getIpAddress(), $aff_sub4);

// Update the existing session if one field is different
if ($current_session) {
    if ($followers != $current_session["followers"] || $platform != $current_session["platform"] || $username != $current_session["username"]) {
        $session->update(ServerService::getIpAddress(), $aff_sub4, $username, $platform, $followers);
    }
    echo json_encode([
        "success" => true
    ]);
    die();
}

// If a session doesn't exist then create it
$session->create(ServerService::getIpAddress(), $aff_sub4, $username, $platform, $followers);
echo json_encode([
    "success" => true
]);
