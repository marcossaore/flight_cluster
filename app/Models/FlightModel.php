<?php

namespace App\Models;

class FlightModel
{
    public static function getFlights()
    {
        $data = (new \GuzzleHttp\Client())->request('GET', env('FLIGHT_ENDPOINT'));

        return (object)[
            'statusCode' => json_decode($data->getStatusCode(), true),
            'data' => json_decode($data->getBody(), true),
        ];
    }
}
