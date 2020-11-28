<?php

namespace App\Http\Controllers;

class FlightClusterController extends Controller
{
    public static function index()
    {
        return response()->json(['sender' => true], 200);
    }
}
