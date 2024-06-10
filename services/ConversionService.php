<?php

class ConversionService
{
    /*
     * Converts the total follower amount to the amount of conversions required.
     * */
    public static function followersToConversions(int $followers): int
    {
        switch ($followers) {
            case 250:
                return 1;
            case 500:
                return 2;
            default:
                return 3;
        }
    }
}
