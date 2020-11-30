<?php

namespace App\Http\Controllers;

use  Exception;
use App\Models\FlightModel;

class FlightClusterController extends Controller
{
    private const FARE_1AF = '1AF';
    private const FARE_4DA = '4DA';

    private $flights;

    public function groupFlights()
    {
        try {
            $this->flights = (FlightModel::getFlights())->data;

            return $this->flights;

            if (! $this->flights) {
                throw new Exception('Não há voos disponíveis!');
            }

            $flightsType1AF = $this->groupFlightWithFarePriceAndTypeFlight($this->flights, self::FARE_1AF);

            $flightsType4DA = $this->groupFlightWithFarePriceAndTypeFlight($this->flights, self::FARE_4DA);

            if (! $flightsType1AF && $flightsType4DA) {
                throw new Exception('Não há voos disponíveis com as tarifas associadas ');
            }

            $groups = $this->createGroupFlightAndExtractCheapestGroupFlight($flightsType1AF, $flightsType4DA, $cheapestFlightGroup);

            $complementWithadditionalInfo = $this->addAditionalInfo($flights, $groups, $cheapestFlightGroup);

            return ($complementWithadditionalInfo);
        } catch (Exception $exception) {
            return [
                'error' => $exception->getMessage(),
                'code' => 500,
            ];
        }
    }

    private function groupFlightWithFarePriceAndTypeFlight(array $flights, string $fare)
    {
        $flightsSegregateByFare = $this->groupFlightsByTypeFare($flights, $fare);

        $this->groupFlightsByPrice($flightsSegregateByFare);

        $flightsSegregateByTypeFlight = $this->groupFlightsByTypeFlight($flightsSegregateByFare);

        return $flightsSegregateByTypeFlight;
    }

    private function createGroupFlightAndExtractCheapestGroupFlight(array $groupFlights1AF, array $groupFlights4DA, &$chepeastGroup)
    {
        $index = 0;

        $outbounds1AF = $groupFlights1AF['outbound'];
        $inbounds1AF = $groupFlights1AF['inbound'];

        $groups1AF = $this->segregateOutboundandAndInboundFlights($outbounds1AF, $inbounds1AF, $index);

        $outbounds4DA = $groupFlights4DA['outbound'];
        $inbounds4DA = $groupFlights4DA['inbound'];

        $groups4DA = $this->segregateOutboundandAndInboundFlights($outbounds4DA, $inbounds4DA, $index);

        $groups = array_merge($groups1AF, $groups4DA);

        $this->appendPropertiesInGroup($groups);

        $this->groupFlightsCheapestTotalPrice($groups);

        $chepeastGroup = $groups[0] ?? null;

        return $groups;
    }

    private function addAditionalInfo(array $flights, array $groupsOfFlights, array $cheapestFlightGroup)
    {
        return [
            'flights' => $flights,
            'groups' => $groupsOfFlights,
            'totalGroups' => sizeOf($groupsOfFlights),
            // não entendi
            'totalFlights' => sizeOf($flights),
            'cheapestPrice' => $cheapestFlightGroup['totalPrice'] ?? null,
            'cheapestGroup' => $cheapestFlightGroup['uniqueId'] ?? null,
        ];
    }

    private function groupFlightsByTypeFare(array $flights, string $fare)
    {
        return array_filter($flights, function ($flight) use ($fare) {
            return $flight['fare'] == $fare;
        });
    }

    private function groupFlightsByPrice(array &$flights)
    {
        usort($flights, function ($item1, $item2) {
            return $item1['price'] <=> $item2['price'];
        });
    }

    private function groupFlightsByTypeFlight(array $flights)
    {
        $priceChange = null;

        $groupOfInboundOrOutbounds = null;

        $index = 0;

        foreach ($flights as $key => $value) {
            if (! $priceChange) {
                $priceChange = $value['price'];
            }

            $isInboundOrOutbound = $value['outbound'] == 1 ? 'outbound' : 'inbound';

            if ($priceChange == $value['price']) {
                $groupOfInboundOrOutbounds[$isInboundOrOutbound][$index][] = $value;
            } else {
                $priceChange = $value['price'];
                $index++;

                $groupOfInboundOrOutbounds[$isInboundOrOutbound][$index][] = $value;
            }
        }

        //reindex arrays
        $groupOfInboundOrOutbounds['outbound'] = array_values($groupOfInboundOrOutbounds['outbound']);
        $groupOfInboundOrOutbounds['inbound'] = array_values($groupOfInboundOrOutbounds['inbound']);

        return $groupOfInboundOrOutbounds;
    }

    private function segregateOutboundandAndInboundFlights(array $outbounds, array $inbounds, int &$index = 0)
    {
        foreach ($outbounds as $keyOutbound => $outboundFlightGroup) {
            $group[$index]['outbound'] = $outboundFlightGroup;

            foreach ($inbounds as $keyInbound => $inboundFlightGroup) {
                if ($keyInbound == 0) {
                    $group[$index]['inbound'] = $inboundFlightGroup;
                } else {
                    $index++;
                    $group[$index]['outbound'] = $outboundFlightGroup;
                    $group[$index]['inbound'] = $inboundFlightGroup;
                }
            }

            $index++;
        }

        return $group;
    }

    private function groupFlightsCheapestTotalPrice(array $groupFlights)
    {
        usort($groupFlights, function ($item1, $item2) {
            return $item1['totalPrice'] <=> $item2['totalPrice'];
        });

        return $groupFlights;
    }

    private function appendPropertiesInGroup(&$groups)
    {
        foreach ($groups as $key => $value) {
            $groups[$key]['uniqueId'] = ($key + 1);

            $outboundPrice = $groups[$key]['outbound'][0]['price'];
            $inboundPrice = $groups[$key]['inbound'][0]['price'];

            $groups[$key]['totalPrice'] =  ($outboundPrice + $inboundPrice);
        }
    }
}
