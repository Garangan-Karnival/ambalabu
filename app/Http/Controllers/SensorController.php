<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan ID user yang login
use Exception; 

class SensorController extends Controller
{
    // ==========================================================
    // FUNGSI 1: INPUT DATA DARI SENSOR (PENYIMPANAN DATA)
    // ==========================================================
    public function input(Request $request)
    {
        // 1. Ambil dan Konversi Data (Type Casting)
        $cahaya = (int)$request->get('cahaya', 0);
        $suhu = (float)$request->get('suhu', 0); 
        $kelembapan = (int)$request->get('kelembapan', 0);
        $rainValue = (int)$request->get('rainValue', 0);
        $status = $request->get('status', "Unknown");
        
        // PENTING: Ambil ID User dari parameter URL.
        $sensor_user_id = (int)$request->get('user_id', 1);

        // --- 2. Mulai Database Transaction ---
        DB::beginTransaction();

        try {
            // Insert 1: cahaya
            $id_cahaya = DB::table('cahaya')->insertGetId([
                'intensitas_cahaya' => $cahaya,
                'waktu' => now(),
            ]);

            // Insert 2: suhu
            $id_suhu = DB::table('suhu')->insertGetId([
                'temperature' => $suhu,
                'kelembapan' => $kelembapan,
                'waktu' => now(),
            ]);

            // Insert 3: raindrop
            DB::table('raindrop')->insert([
                'intensitas_hujan' => $rainValue,
                'waktu' => now(),
                'keterangan' => $status,
                'id_cahaya' => $id_cahaya,
                'id_suhu' => $id_suhu,
                'id_user' => $sensor_user_id, // ID user yang dikirim sensor
            ]);

            // Jika semua berhasil, COMMIT
            DB::commit();

            return response()->json(['message' => 'Data berhasil disimpan!'], 200);

        } catch (Exception $e) {
            // Jika ada yang gagal, ROLLBACK
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal menyimpan data ke database.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }


    // ==========================================================
    // FUNGSI 2: MENAMPILKAN DATA KE GRAFIK (FILTERING BY LOGIN)
    // ==========================================================
    public function showGrafik()
    {
        // 1. Ambil ID User yang sedang login
        $user_id = Auth::id(); 

        if (!$user_id) {
            return redirect('/login')->with('error', 'Anda harus login untuk melihat grafik.');
        }
        
        // 2. Ambil ID Cahaya dan Suhu yang terkait dengan user_id ini (50 data terakhir)
        $latest_raindrop_ids = DB::table('raindrop')
            ->where('id_user', $user_id) // FILTER BERDASARKAN USER YANG LOGIN
            ->orderBy('waktu', 'desc')
            ->limit(50)
            ->get(['id_cahaya', 'id_suhu']);

        // Jika tidak ada data
        if ($latest_raindrop_ids->isEmpty()) {
             return view('grafik', [
                'data_cahaya' => collect(), 
                'data_suhu' => collect()
            ]);
        }
        
        // Buat array ID untuk query berikutnya
        $cahaya_ids = $latest_raindrop_ids->pluck('id_cahaya')->toArray();
        $suhu_ids = $latest_raindrop_ids->pluck('id_suhu')->toArray();
        
        // 3. Ambil data cahaya berdasarkan ID yang difilter
        $data_cahaya = DB::table('cahaya')
                         ->whereIn('id_cahaya', $cahaya_ids) 
                         ->orderBy('waktu', 'asc') 
                         ->get();

        // 4. Ambil data suhu berdasarkan ID yang difilter
        $data_suhu = DB::table('suhu')
                       ->whereIn('id_suhu', $suhu_ids) 
                       ->orderBy('waktu', 'asc') 
                       ->get();

        // 5. Kirim data ke View
        return view('grafik', [
            'data_cahaya' => $data_cahaya, 
            'data_suhu' => $data_suhu
        ]);
    }
}