<?php

class SMMGlobeService {
    /**
     * @throws Exception The exception can vastly differ. We want a generic catch-all.
     */
    function addOrder(string $platform, string $username, int $quantity)
    {
        $ch = curl_init();
        $query_params = [
            "key" => getenv("SMM_GLOBE_API_KEY"),
            "action" => "add",
            "service" => $this->getServiceId($platform),
            "link" => $this->platformLink($platform, $username),
            "quantity" => $quantity
        ];
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://smmglobe.com/api/v2" . "?" . http_build_query($query_params),
            CURLOPT_RETURNTRANSFER => true
        ]);
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

    /*
     * Get the service ID for the platform provided.
     * */
    private function getServiceId(string $platform): string
    {
        switch ($platform) {
            case "tiktok":
                return getenv("TIKTOK_FOLLOWERS_SERVICE_ID");
            default:
                return getenv("INSTAGRAM_FOLLOWERS_SERVICE_ID");
        }
    }

    /*
     * This will build the full URL for the platform with the username provided.
     * */
    private function platformLink(string $platform, string $username): string
    {
        switch ($platform) {
            case "tiktok":
                return "https://www.tiktok.com/@" . $username . "/";
            default:
                return "https://www.instagram.com/" . $username . "/";
        }
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    private function parseResponse(string $resp)
    {
        $content = json_decode($resp, null, 512, JSON_THROW_ON_ERROR);
        if (!$content->order) {
            throw new Exception($content->error);
        }
        return $content;
    }
}
