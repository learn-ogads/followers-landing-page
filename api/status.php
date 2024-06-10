<?php

/*
 * Status is used by the frontend to see if we have unlocked the content locker successfully.
 * This is done by checking if X amount of offers are completed for X amount of followers.
 * */

require_once "../services/ServerService.php";
require_once "../services/ValidationService.php";
require_once "../services/ConversionService.php";
require_once "../services/DotEnvService.php";
require_once "../database/Session.php";
require_once "../database/Click.php";

(new DotEnvService(__DIR__ . "/../.env"))->load();

header("Content-Type: application/json");

$aff_sub4 = ValidationService::affSub4();

// Get the current session, if it doesn't exist then return an error on the frontend
$session = new Session();
$current_session = $session->get(ServerService::getIpAddress(), $aff_sub4);
if (!$current_session) {
    echo json_encode([
        "success" => false,
        "error" => "The current session doesn't exist"
    ]);
    die();
}

$conversions_required = ConversionService::followersToConversions($current_session["followers"]);

$click = new Click();
$clicks = $click->getAll($current_session["id"]);

// We are completed the required numbers of offers
if (count($clicks) >= $conversions_required) {
    echo json_encode([
        "success" => true
    ]);
    die();
}

echo json_encode([
    "success" => false
]);
