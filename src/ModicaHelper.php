<?php

namespace ModicaHelper;

use Illuminate\Support\Facades\Http;

/**
 * Class ModicaHelper
 * @package ModicaHelper
 */
class ModicaHelper
{
    private $clientId;
    private $clientSecret;
    private $baseUrl;

    /**
     * Constructor to set up client ID, secret and base URL.
     */
    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->baseUrl = 'https://mmdm.masu.one/service';
    }

    /**
     * Perform a GET request with the required headers.
     */
    private function makeGetRequest($url)
    {
        $response = Http::withHeaders([
            'mmdm-client-id' => $this->clientId,
            'mmdm-client-secret' => $this->clientSecret,
        ])->get($this->baseUrl . $url);

        return $response->successful() ? $response->json() : [];
    }

    /**
     * Perform a POST request with the required headers.
     */
    private function makePostRequest($url, $data)
    {
        $response = Http::withHeaders([
            'mmdm-client-id' => $this->clientId,
            'mmdm-client-secret' => $this->clientSecret,
        ])->post($this->baseUrl . $url, $data);

        return $response->successful() ? $response->json() : [];
    }

    public function getPhoneSlots()
    {
        return $this->makeGetRequest('/manage/phone-number-slots');
    }

    public function getClientInformation()
    {
        return $this->makeGetRequest('/manage/client');
    }

    public function getCallHistory($phoneNumber)
    {
        return $this->makeGetRequest('/specs/call-history?phone_number=' . $phoneNumber);
    }

    public function getPhoneOnlineStatus($phoneNumber)
    {
        return $this->makeGetRequest('/specs/phone-last-update?phone_number=' . $phoneNumber);
    }

    public function getPhoneRegisterQrHash($phoneNumber)
    {
        return $this->makePostRequest('/specs/get-verify-hash', ['phone_number' => $phoneNumber]);
    }

    public function getPhoneRecord($recordId, $phoneNumber)
    {
        return $this->makeGetRequest('/specs/get-phone-record?record_id=' . $recordId . '&phone_number=' . $phoneNumber);
    }
}