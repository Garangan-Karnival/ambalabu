<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    public function currentWeather()
    {
        $apiKey = env('WEATHER_API_KEY');
        $city = env('WEATHER_CITY', 'Jakarta');

        // Call WeatherAPI current endpoint
        $response = Http::withoutVerifying()->get("https://api.weatherapi.com/v1/current.json", [
    'key' => env('WEATHER_API_KEY'),
    'q' => 'Jakarta',
    'aqi' => 'no'
]);


        // If request failed, log and provide empty data to view
        if (! $response->successful()) {
            Log::warning('WeatherAPI request failed', [
                'status' => $response->status(),
                'body'   => $response->body()
            ]);

            // Send safe defaults so view doesn't break
            return view('home', [
                'temp'      => null,
                'humidity'  => null,
                'uv'        => null,
                'condition' => null,
                'city'      => $city,
            ]);
        }

        $data = $response->json();

        // Debugging helper (uncomment while testing)
        // dd($data);

        // Safe extraction with null coalescing
        $temp = data_get($data, 'current.temp_c');
        $humidity = data_get($data, 'current.humidity');
        $uv = data_get($data, 'current.uv');                // WeatherAPI provides current.uv
        $condition = data_get($data, 'current.condition.text');

        return view('home', compact('temp', 'humidity', 'uv', 'condition', 'city'));
        
    }
}
