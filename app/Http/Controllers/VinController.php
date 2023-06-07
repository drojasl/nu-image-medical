<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExternalApiService;

class VinController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ExternalApiService $externalApiService)
    {

    }

    public function index()
    {
        $vin = [];
        $salvage = [];
        $search = false;

        return view('vin', compact('search', 'vin', 'salvage'));
    }

    public function search(Request $request)
    {
        $validatedData = $request->validate([
            'vin' => 'required',
        ]);

        $search = true;
        $vin = $this->externalApiService->decodeVin($validatedData['vin']);
        $salvage = $this->externalApiService->salvageCheck($validatedData['vin']);

        return view('vin', compact('search', 'vin', 'salvage'));
    }

}
