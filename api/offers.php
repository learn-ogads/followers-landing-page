<?php

require_once "../services/OffersService.php";
require_once "../services/ValidationService.php";
require_once "../services/DotEnvService.php";

(new DotEnvService(__DIR__ . "/../.env"))->load();

$aff_sub4 = ValidationService::affSub4();

$api_key = (string)getenv("OFFERS_API_KEY");
$offers_limit = (int)getenv("OFFERS_LIMIT");
$offers_service = new OffersService($api_key, $aff_sub4);

header("Content-Type: application/json");

try {
    $offers = $offers_service->get();
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => "Failed to fetch the offers"
    ]);
    die();
}

// Limit the offers based on OFFERS_LIMIT
if ($offers_limit > 0) {
    $offers = array_slice($offers, 0, $offers_limit);
}

echo json_encode([
    "success" => true,
    "data" => $offers
]);
