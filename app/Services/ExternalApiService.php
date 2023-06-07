<?php

namespace App\Services;

class ExternalApiService
{
    /**
     * Consume SMS API
     *
     * @param string $phone
     * @param string $code
     */
    public function sendTFA(string $phone, string $code) {
        $client = new \GuzzleHttp\Client();

        $url = "https://telesign-telesign-send-sms-verification-code-v1.p.rapidapi.com/sms-verification-code?phoneNumber={$phone}&verifyCode={$code}";

        try {
            $response = $client->request('POST', $url,
                [
                    // TODO: Move API keys to env file
                    'headers' => [
                        'X-RapidAPI-Host' => 'telesign-telesign-send-sms-verification-code-v1.p.rapidapi.com',
                        'X-RapidAPI-Key' => '6388d0e157mshbe15331cdce7643p14a382jsn0b7b778c0edb',
                    ],
                ]
            );
            return [
                'success' => true
            ];
        } catch(\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $message = json_decode($response->getBody()->getContents(), 1);
            
            return [
                'success' => false,
                'message' => $message['message']
            ];
        }
    }

    /**
     * Consume VIN API
     *
     * @param string $vin
     */
    public function decodeVin(string $vin) {
        $client = new \GuzzleHttp\Client();

        $url = "https://vindecoder.p.rapidapi.com/decode_vin?vin={$vin}";

        try {
            $response = $client->request('POST', $url,
                [
                    // TODO: Move API keys to env file
                    'headers' => [
                        'X-RapidAPI-Host' => 'vindecoder.p.rapidapi.com',
                        'X-RapidAPI-Key' => '6388d0e157mshbe15331cdce7643p14a382jsn0b7b778c0edb',
                    ],
                ]
            );
            return [
                'success' => true
            ];
        } catch(\GuzzleHttp\Exception\ClientException $e) {

            return $this->getFakeVinData();     // REMOVE THIS LINE after subscribe to the APIs

            $response = $e->getResponse();
            $message = json_decode($response->getBody()->getContents(), 1);
            
            return [
                'success' => false,
                'message' => $message['message']
            ];
        }
    }

    /**
     * Consume Salvage API
     *
     * @param string $vin
     */
    public function salvageCheck(string $vin) {
        $client = new \GuzzleHttp\Client();

        $url = "https://vindecoder.p.rapidapi.com/salvage_check?vin={$vin}";

        try {
            $response = $client->request('POST', $url,
                [
                    // TODO: Move API keys to env file
                    'headers' => [
                        'X-RapidAPI-Host' => 'vindecoder.p.rapidapi.com',
                        'X-RapidAPI-Key' => '6388d0e157mshbe15331cdce7643p14a382jsn0b7b778c0edb',
                    ],
                ]
            );
            return [
                'success' => true
            ];
        } catch(\GuzzleHttp\Exception\ClientException $e) {

            return $this->getFakeSalvageData();     // REMOVE THIS LINE after subscribe to the APIs

            $response = $e->getResponse();
            $message = json_decode($response->getBody()->getContents(), 1);
            
            return [
                'success' => false,
                'message' => $message['message']
            ];
        }
    }

    /* 
        I COULD NOT CONSUME THE VIN APIs with the credentials provided
        I got this error message
        "You are not subscribed to this API."

        So I need to use FakeData functions to simulate a response
    */
    function getFakeVinData() {
        return [
            'vin'=>"4F2YU09161KM33122",
            'year'=>"2001",
            'make'=>"MAZDA",
            'model'=>"TRIBUTE",
            'trim_level'=>"LX",
            'engine'=>"3.0L V6 DOHC 24V",
            'style'=>"SPORT UTILITY 4-DR",
            'made_in'=>"UNITED STATES",
            'steering_type'=>"R&P",
            'anti_brake_system'=>"Non-ABS | 4-Wheel ABS",
            'tank_size'=>"16.40 gallon",
            'overall_height'=>"69.90 in.",
            'overall_length'=>"173.00 in.",
            'overall_width'=>"71.90 in.",
            'standard_seating'=>"5",
            'optional_seating'=>null,
            'highway_mileage'=>"24 miles/gallon",
            'city_mileage'=>"18 miles/gallon",
        ];
    }

    function getFakeSalvageData() {
        return [
            'images'=>['a', 'b', 'c'],
            'vehicle_title'=>'NY - MV-907A SALVAGE CERTIFICATE',
            'mileage'=>'628 (ACTUAL)',
            'primary_damage'=>'REAR END',
            'secondary_damage'=>'FR',
            'loss_type'=>'COLLISION',
        ];
    }

}
