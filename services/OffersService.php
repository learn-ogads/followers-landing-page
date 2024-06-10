<?php

use Detection\MobileDetect;
require_once "../utils/MobileDetect.php";
require_once "../services/ServerService.php";

class OffersService
{
    private string $API_KEY;
    private ?string $AFF_SUB4;

    public function __construct(string $apiKey, ?string $affSub4)
    {
        $this->API_KEY = $apiKey;
        $this->AFF_SUB4 = $affSub4;
    }

    /**
     * @throws Exception The exception can vastly differ. We want a generic catch-all.
     */
    public function get()
    {
        $ch = $this->createCURL();
        $content = curl_exec($ch);
        // Check for an error
        if (!$content) {
            throw new Exception(curl_error($ch));
        }
        curl_close($ch);
        try {
            return $this->parseResponse($content);
        } catch (JsonException $e) {
            throw new Exception("Unable to fetch required data, please try again");
        }
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    private function parseResponse(string $resp) {
        $content = json_decode($resp, null, 512, JSON_THROW_ON_ERROR);
        if (!$content->success) {
            throw new Exception($content->error);
        }
        return $content->offers;
    }

    private function createCURL()
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => getenv("OFFERS_ENDPOINT") . "?" . http_build_query($this->buildData()),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $this->API_KEY
            ]
        ]);
        return $ch;
    }

    /*
     * isMobileOrTablet will detect if a mobile device or tablet is being used.
     * This is commonly used to determine what type of offers to return.
     * */
    public static function isMobileOrTablet(): bool
    {
        $detect = new MobileDetect();
        if ($detect->isMobile() || $detect->isTablet()) return true;
        return false;
    }

    /*
     * buildData will build the required query params.
     * Additional query params can be appended to the array if required
     * */
    private function buildData(): array
    {
        return [
            "ip" => ServerService::getIpAddress(),
            "user_agent" => ServerService::getUserAgent(),
            "ctype" => OffersService::isMobileOrTablet() ? (int)getenv("OFFERS_CTYPE") : null,
            "aff_sub4" => $this->AFF_SUB4
        ];
    }
}