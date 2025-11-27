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
    // PERBAIKAN: id_user kini disimpan di tabel cahaya dan suhu juga.
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
            // Insert 1: cahaya - KUNCI PERBAIKAN: id_user ditambahkan
            $id_cahaya = DB::table('cahaya')->insertGetId([
                'intensitas_cahaya' => $cahaya,
                'waktu' => now(),
                'id_user' => $sensor_user_id, 
            ]);

            // Insert 2: suhu - KUNCI PERBAIKAN: id_user ditambahkan
            $id_suhu = DB::table('suhu')->insertGetId([
                'temperature' => $suhu,
                'kelembapan' => $kelembapan,
                'waktu' => now(),
                'id_user' => $sensor_user_id, 
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
    // KUNCI PERBAIKAN: Menggunakan JOIN dan output JSON string
    // ==========================================================
    public function showGrafik()
    {
        // 1. Ambil ID User yang sedang login
        $user_id = Auth::id(); 
        
        // Inisialisasi variabel untuk View
        $json_data_cahaya = '[]';
        $json_data_suhu = '[]';
        $json_data_raindrop = '[]';
        $error_message = null; 

        // Pastikan user sudah login
        if (!$user_id) {
             return redirect('/login')->with('error', 'Anda harus login untuk melihat grafik.');
        }

        try {
            // 2. Query GABUNGAN: Filter berdasarkan id_user dan JOIN semua tabel
            $latest_data = DB::table('raindrop')
                // JOIN CAHAYA
                ->join('cahaya', 'raindrop.id_cahaya', '=', 'cahaya.id_cahaya')
                // JOIN SUHU
                ->join('suhu', 'raindrop.id_suhu', '=', 'suhu.id_suhu')
                // Filter hanya data milik user yang sedang login
                ->where('raindrop.id_user', $user_id) 
                // Pilih semua kolom yang dibutuhkan
                ->select([
                    'raindrop.waktu',
                    'cahaya.intensitas_cahaya',
                    'suhu.temperature',
                    'suhu.kelembapan',
                    'raindrop.intensitas_hujan', 
                ])
                ->orderBy('raindrop.waktu', 'desc')
                ->limit(50) 
                ->get()
                ->reverse() // Balikkan urutan agar grafik tampil dari waktu lama ke baru
                ->values();


            if ($latest_data->isEmpty()) {
                $error_message = "Tidak ada data sensor ditemukan untuk ID User: {$user_id}. Pastikan sensor sudah mengirim data (cek juga kolom id_user di database).";
            }

            // 3. Pisahkan dan format data menjadi objek terpisah yang siap di-JSON-kan
            
            $data_cahaya = $latest_data->map(function ($item) {
                return (object)[
                    'waktu' => $item->waktu,
                    'intensitas_cahaya' => $item->intensitas_cahaya
                ];
            });

            $data_suhu = $latest_data->map(function ($item) {
                return (object)[
                    'waktu' => $item->waktu,
                    'temperature' => $item->temperature,
                    'kelembapan' => $item->kelembapan
                ];
            });

            $data_raindrop = $latest_data->map(function ($item) {
                return (object)[
                    'waktu' => $item->waktu,
                    'intensitas_hujan' => $item->intensitas_hujan
                ];
            });

            // 4. Konversi ke JSON string untuk dikirim ke View
            $json_data_cahaya = $data_cahaya->toJson();
            $json_data_suhu = $data_suhu->toJson();
            $json_data_raindrop = $data_raindrop->toJson();

        } catch (Exception $e) {
            // Tangani error database (misalnya kolom id_user tidak ada di salah satu tabel)
            $error_message = "Database Error! Gagal memuat data. Pesan: " . $e->getMessage() . 
                             ". Pastikan semua tabel sensor memiliki kolom 'id_user'.";
        }


        // 5. Kirim data dan pesan error ke View
        return view('grafik', [
            'json_data_cahaya' => $json_data_cahaya, 
            'json_data_suhu' => $json_data_suhu,
            'json_data_raindrop' => $json_data_raindrop, 
            'error_message' => $error_message, // Pesan ini penting!
            'user_id' => $user_id 
        ]);
    }
}