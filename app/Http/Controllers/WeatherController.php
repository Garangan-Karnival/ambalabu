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
        $city   = env('WEATHER_CITY', 'Jakarta');

        // Use forecast endpoint to get sunrise/sunset
        $response = Http::withoutVerifying()->get("https://api.weatherapi.com/v1/forecast.json", [
            'key' => $apiKey,
            'q'   => $city,
            'days' => 1,
            'aqi' => 'no',
            'alerts' => 'no'
        ]);

        if (! $response->successful()) {
            Log::warning('WeatherAPI request failed', [
                'status' => $response->status(),
                'body'   => $response->body()
            ]);

            return view('home', [
                'temp'        => null,
                'humidity'    => null,
                'uv'          => null,
                'condition'   => null,
                'city'        => $city,
                'sunriseTime' => null,
                'sunsetTime'  => null,
                'timezone'    => null,
            ]);
        }

        $data = $response->json();

        // Extract astro data safely
        $sunrise = data_get($data, 'forecast.forecastday.0.astro.sunrise');
        $sunset  = data_get($data, 'forecast.forecastday.0.astro.sunset');
        $timezone = data_get($data, 'location.tz_id');

        $sunriseTime = $sunrise ? date("H:i", strtotime($sunrise)) : null;
        $sunsetTime  = $sunset  ? date("H:i", strtotime($sunset))  : null;

        // Extract current weather
        $temp = data_get($data, 'current.temp_c');
        $humidity = data_get($data, 'current.humidity');
        $uv = data_get($data, 'current.uv');
        $condition = data_get($data, 'current.condition.text');

        return view('home', compact(
            'temp',
            'humidity',
            'uv',
            'condition',
            'city',
            'sunriseTime',
            'sunsetTime',
            'timezone'
        ));
    }
}
