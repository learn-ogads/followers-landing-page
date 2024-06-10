<?php

class ServerService
{
    public static function getIpAddress(): string
    {
        // Check if the IP is local. If it is, return a hardcoded IP address
        return $_SERVER["REMOTE_ADDR"] == "127.0.0.1" ? "66.249.66.138" : $_SERVER["REMOTE_ADDR"];
    }

    public static function getUserAgent(): string
    {
        return $_SERVER["HTTP_USER_AGENT"];
    }
}