<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SensorController extends Controller
{
    public function input(Request $request)
    {
        $cahaya = $request->get('cahaya', 0);
        $suhu = $request->get('suhu', 0);
        $kelembapan = $request->get('kelembapan', 0);
        $rainValue = $request->get('rainValue', 0);
        $status = $request->get('status', "Unknown");

        // Insert cahaya
        $id_cahaya = DB::table('cahaya')->insertGetId([
            'intensitas_cahaya' => $cahaya,
            'waktu' => now(),
        ]);

        // Insert suhu
        $id_suhu = DB::table('suhu')->insertGetId([
            'temperature' => $suhu,
            'kelembapan' => $kelembapan,
            'waktu' => now(),
        ]);

        // Insert raindrop
        DB::table('raindrop')->insert([
            'intensitas_hujan' => $rainValue,
            'waktu' => now(),
            'keterangan' => $status,
            'id_cahaya' => $id_cahaya,
            'id_suhu' => $id_suhu
        ]);

        return "Data berhasil disimpan!";
    }
}
