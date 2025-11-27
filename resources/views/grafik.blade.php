@extends('layouts.app') 
{{-- Ganti 'layouts.app' jika Anda menggunakan nama layout lain --}}

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Monitoring Data Sensor (User ID: {{ Auth::id() }})</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- KOTAK GRAFIK CAHAYA --}}
        <div class="bg-white p-6 rounded-lg shadow-xl">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Intensitas Cahaya</h2>
            <div class="h-96">
                <canvas id="cahayaChart"></canvas>
            </div>
        </div>

        {{-- KOTAK GRAFIK SUHU DAN KELEMBAPAN --}}
        <div class="bg-white p-6 rounded-lg shadow-xl">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Suhu dan Kelembapan</h2>
            <div class="h-96">
                <canvas id="suhuKelembabanChart"></canvas>
            </div>
        </div>

    </div>

    @if ($data_cahaya->isEmpty())
        <div class="mt-8 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
            <p class="font-bold">Informasi:</p>
            <p>Belum ada data sensor yang masuk untuk user ini (ID: {{ Auth::id() }}).</p>
        </div>
    @endif
</div>
@endsection

@section('scripts')
{{-- PENTING: Import Library Chart.js dari CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0/dist/chartjs-adapter-moment.min.js"></script>

<script>
    // 1. Ambil data dari PHP menggunakan Blade directive json
    const dataCahaya = @json($data_cahaya);
    const dataSuhu = @json($data_suhu);

    // Variabel untuk menampung data yang siap digambar
    const waktuLabels = dataCahaya.map(item => item.waktu);

    // ===========================================
    // 2. GRAFIK CAHAYA
    // ===========================================
    const intensitasCahaya = dataCahaya.map(item => item.intensitas_cahaya);

    new Chart(document.getElementById('cahayaChart'), {
        type: 'line',
        data: {
            labels: waktuLabels,
            datasets: [{
                label: 'Intensitas Cahaya',
                data: intensitasCahaya,
                borderColor: 'rgba(255, 193, 7, 1)', // Warna Kuning
                backgroundColor: 'rgba(255, 193, 7, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'time', // Menggunakan type time agar format WAKTU terbaca
                    time: {
                        unit: 'minute',
                        tooltipFormat: 'HH:mm:ss DD/MM/YY'
                    },
                    title: {
                        display: true,
                        text: 'Waktu'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nilai Sensor (0-1023)'
                    }
                }
            }
        }
    });

    // ===========================================
    // 3. GRAFIK SUHU DAN KELEMBAPAN
    // ===========================================
    const suhuData = dataSuhu.map(item => item.temperature);
    const kelembabanData = dataSuhu.map(item => item.kelembapan);

    new Chart(document.getElementById('suhuKelembabanChart'), {
        type: 'line',
        data: {
            labels: waktuLabels,
            datasets: [
                {
                    label: 'Suhu (°C)',
                    data: suhuData,
                    borderColor: 'rgba(220, 53, 69, 1)', // Warna Merah
                    backgroundColor: 'rgba(220, 53, 69, 0.2)',
                    yAxisID: 'ySuhu',
                    tension: 0.4,
                    fill: false
                },
                {
                    label: 'Kelembapan (%)',
                    data: kelembabanData,
                    borderColor: 'rgba(0, 123, 255, 1)', // Warna Biru
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    yAxisID: 'yKelembaban',
                    tension: 0.4,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'minute',
                        tooltipFormat: 'HH:mm:ss DD/MM/YY'
                    },
                    title: {
                        display: true,
                        text: 'Waktu'
                    }
                },
                ySuhu: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'Suhu (°C)'
                    }
                },
                yKelembaban: {
                    type: 'linear',
                    display: true,
                    position: 'right', // Kelembaban di sumbu kanan
                    grid: {
                        drawOnChartArea: false // Hanya tampilkan grid untuk ySuhu
                    },
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Kelembapan (%)'
                    },
                    max: 100 // Batasan maksimal 100%
                }
            }
        }
    });
</script>
@endsection

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$users = DB::table('user')->limit(6)->get();

foreach ($user as $user) {
    DB::table('user')
        ->where('id', $user->id)
        ->update(['password' => Hash::make($user->password)]);
}
