<?php

class ValidationService
{
    /*
     * Check if the username was provided or if the length is an invalid amount.
     * This provides small validation and could be extended to check each platform.
     * */
    public static function username(): string
    {
        $username = $_GET["username"] ?? null;
        if ($username == null || strlen($username) < 1) {
            echo json_encode([
                "success" => false,
                "error" => "username was not provided or is invalid"
            ]);
            die();
        }
        return $username;
    }

    /*
     * Check if the platform was provided or if an invalid platform was selected.
     * This could be extended for additional platforms.
     * */
    public static function platform(): string
    {
        $platform = $_GET["platform"] ?? null;
        switch ($platform) {
            case "instagram":
                return "instagram";
            case "tiktok":
                return "tiktok";
            default:
                echo json_encode([
                    "success" => false,
                    "error" => "platform was not provided or is invalid"
                ]);
                die();
        }
    }

    /*
     * Check if affsub4 exists in the cookies.
     * If not, then return an error message.
     * */
    public static function affSub4(): string
    {
        $aff_sub4 = $_COOKIE["aff_sub4"] ?? null;
        if ($aff_sub4 == null) {
            echo json_encode([
                "success" => false,
                "error" => "aff_sub4 was not provided"
            ]);
            die();
        }
        return $aff_sub4;
    }

    /*
     * Check the follower amount provided, and validate that it is a correct amount.
     * This is to prevent someone from manipulating the amount they receive on the frontend.
     * */
    public static function followers()
    {
        switch ($_GET["followers"] ?? null) {
            case "250":
                return 250;
            case "500":
                return 500;
            case "1000":
                return 1000;
            default:
                // Invalid value was provided so throw an error
                echo json_encode([
                    "success" => false,
                    "error" => "Invalid follower amount provided"
                ]);
                die();
        }
    }
}
