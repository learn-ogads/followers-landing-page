<?php
require_once "../services/ServerService.php";
require_once "../services/DotEnvService.php";
require_once "../services/ValidationService.php";
require_once "../database/Session.php";
require_once "../database/Click.php";

(new DotEnvService(__DIR__ . "/../.env"))->load();

// Get the current session and delete all clicks
$aff_sub4 = ValidationService::affSub4();
$session = new Session();
$current_session = $session->get(ServerService::getIpAddress(), $aff_sub4);
if (!$current_session) {
    echo "Invalid request attempted";
    die();
}
$click = new Click();
$click->deleteBySessionId($current_session["id"]);

// Remove the aff_sub4 cookie
if (isset($_COOKIE["aff_sub4"])) {
    unset($_COOKIE["aff_sub4"]);
    setcookie("aff_sub4", "", time() - 3600, "/");
}

$app_name = (string)getenv("APP_NAME");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $app_name ?> - Completed</title>
    <link rel="stylesheet" href="../assets/css/output.css">
</head>
<body>
<div class="bg-gray-900 min-h-screen w-full flex items-center justify-center">
    <div class="w-full px-8 md:px-20 lg:px-14 xl:px-0 mx-auto max-w-screen-sm md:max-w-screen-md lg:max-w-screen-lg xl:max-w-screen">
        <h3 class="text-4xl text-center mb-3">🎉</h3>
        <h1 class="text-3xl font-semibold text-white text-center mb-2">Congrats! You're finished</h1>
        <p class="text-white/80 text-center">
            Please allow up to 48-72 hours for your followers to be delivered.
            <br>
            <span class="text-red-400">
                If you didn't receive them within 48-72 hours, please try again.
            </span>
        </p>
    </div>
</div>
</body>
</html>
