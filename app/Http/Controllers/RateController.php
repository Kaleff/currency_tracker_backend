<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
{
    // Store the rates data in database
    public function store()
    {
        $xmlString = file_get_contents('http://www.bank.lv/vk/ecb.xml');
        $xmlObject = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);
        // Convert xml data to json and back to PHP array
        $json = json_encode($xmlObject);
        $phpArray = json_decode($json, true);
        Rate::upsert($phpArray['Currencies']['Currency'], ['ID'], ['rate']);
    }

    public function index()
    {
        // Return the information about all the currencies
        $rates = Rate::all()
                    ->pluck('rate', 'ID');
        return response()->json($rates);
    }
}
