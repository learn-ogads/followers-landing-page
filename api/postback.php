<?php

/*
 * Postback is used by the OGAds API to update a Click.
 * If the conversions required is completed we then send an API request to SMMGlobe to send the requested follower amount.
 * */

require_once "../services/ServerService.php";
require_once "../services/ConversionService.php";
require_once "../services/SMMGlobeService.php";
require_once "../services/DotEnvService.php";
require_once "../database/Session.php";
require_once "../database/Click.php";

(new DotEnvService(__DIR__ . "/../.env"))->load();

const AUTHORIZED_IPS = [
    // US-West
    "50.18.215.132", "50.18.215.133", "50.18.215.134", "50.18.215.135",
    // US-East
    "107.21.28.235", "107.21.36.214", "107.23.2.46", "107.23.2.50",
    // AP-Northeast
    "54.64.15.176", "54.64.21.195",
    // SA-East
    "54.94.179.76", "54.207.34.180", "54.207.36.218",
    // EU-West
    "54.246.166.8", "54.246.166.9", "54.246.166.12", "54.246.166.17",
    // IAD1
    "209.170.120.242", "209.170.120.243", "209.170.120.244"
];

header("Content-Type: application/json");

if (!in_array(ServerService::getIpAddress(), AUTHORIZED_IPS)) {
    echo json_encode([
        "success" => false,
        "error" => "You're not authorized to access this page"
    ]);
    die();
}

if(!isset($_GET["aff_sub4"]) || !isset($_GET["ip"]) || !isset($_GET["offer_id"])) {
    echo json_encode([
        "success" => false,
        "error" => "You failed to provide the required parameters"
    ]);
    die();
}

// Get the current session based on the provided query params
$session = new Session();
$current_session = $session->get($_GET["ip"], $_GET["aff_sub4"]);
if (!$current_session) {
    echo json_encode([
        "success" => false,
        "error" => "Failed to fetch the session information"
    ]);
    die();
}

// Update the current click/offer to be completed
$click = new Click();
$click->update(1, new DateTime(), (int)$_GET["offer_id"], $current_session["id"]);

// Get all completed clicks. Then check if the conversions required amount is hit.
$completed_clicks = $click->getAll($current_session["id"]);
$conversions_required = ConversionService::followersToConversions($current_session["followers"]);
if (count($completed_clicks) < $conversions_required) {
    echo json_encode([
        "success" => false,
        "error" => "Not enough offers have been completed"
    ]);
    die();
}

// Place an order for followers
$smmGlobe = new SMMGlobeService();
try {
    $smmGlobe->addOrder($current_session["platform"], $current_session["username"], $current_session["followers"]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => "Failed to order the followers"
    ]);
    die();
}

echo json_encode([
   "success" => true
]);
