<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightClusterController;

Route::get('flights', function (Request $request) {
    return (new FlightClusterController)->groupFlights();
});
