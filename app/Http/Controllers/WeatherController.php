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

        // Determine weather-based roof state
$weatherRoofState = 'open'; // default

if ($condition && str_contains(strtolower($condition), 'rain')) {
    $weatherRoofState = 'closed';
}

// SENSOR OVERRIDE SECTION
$sensorRoofState = $this->getSensorRoofState(); // <- we will create this

// Final decision: sensor has priority over weather
$roofState = $sensorRoofState ?? $weatherRoofState;


        return view('home', [
    'temp'        => $temp,
    'humidity'    => $humidity,
    'uv'          => $uv,
    'condition'   => $condition,
    'city'        => $city,
    'sunriseTime' => $sunriseTime,
    'sunsetTime'  => $sunsetTime,
    'timezone'    => $timezone,
    'roofState'   => $roofState
]);

    }
    private function getSensorRoofState()
{
    try {
        // Get latest raindrop data
        $latest = DB::table('raindrop')
            ->orderBy('waktu', 'desc')
            ->first();

        if (!$latest) {
            return 'error'; // no data at all
        }

        // Check if data expired (no new data for 2 minutes)
        if (now()->diffInSeconds($latest->waktu) > 120) {
            return 'error';
        }

        // If the sensor has an explicit status (optional)
        if (isset($latest->keterangan)) {
            if (strtolower($latest->keterangan) === 'error') {
                return 'error';
            }
        }

        // Interpret rainfall
        if ($latest->intensitas_hujan > 0) {
            return 'closed';
        }

        return 'open';

    } catch (\Exception $e) {
        return 'error';
    }
}

}
