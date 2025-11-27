<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Sensor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0/dist/chartjs-adapter-moment.min.js"></script>
</head>

<body class="bg-[#F6F8FC] min-h-screen flex flex-col">

    <main class="flex-1">

        <nav class="w-full bg-white shadow rounded-b-3xl px-8 py-4 flex items-center justify-between">

            <div class="flex items-center gap-16">
                <a href="{{ route('home') }}" class="flex flex-col items-center hover:text-blue-600">
                    <span class="text-sm mt-1">Home</span>
                </a>
                <a href="{{ route('grafik') }}" class="flex flex-col items-center hover:text-blue-600">
                    <span class="text-sm mt-1">Grafik</span>
                </a>
                <a href="{{ route('profile') }}" class="flex flex-col items-center hover:text-blue-600">
                    <span class="text-sm mt-1">Profile</span>
                </a>
            </div>

            <div class="flex gap-4">
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-red-500 text-white px-5 py-2 rounded-xl hover:bg-red-600">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login.page') }}"
                        class="bg-blue-500 text-white px-5 py-2 rounded-xl hover:bg-blue-600">
                        Login / Register
                    </a>
                @endauth
            </div>

        </nav>
        <div class="p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Data Sensor Real-Time</h1>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-xl font-semibold mb-4">Intensitas Cahaya</h2>
                    <canvas id="cahayaChart"></canvas>
                </div>

                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-xl font-semibold mb-4">Suhu Ruangan</h2>
                    <canvas id="suhuChart"></canvas>
                </div>
            </div>

            <script>
                // 1. Ambil data dari PHP menggunakan Blade directive @json
                // Masalah "unexpected token ," terjadi karena komentar di baris yang sama.
                // Sekarang komentar berada di barisnya sendiri (baris 58).
                
                const dataCahaya = @json($data_cahaya ?? []);
                const dataSuhu = @json($data_suhu ?? []);

                // 2. Sekarang Anda bisa melanjutkan dengan kode inisialisasi Chart.js Anda di sini.
                // Contoh inisialisasi sederhana (Anda perlu menyesuaikannya dengan data Anda):
                
                // --- Grafik Cahaya ---
                const ctxCahaya = document.getElementById('cahayaChart').getContext('2d');
                new Chart(ctxCahaya, {
                    type: 'line',
                    data: {
                        labels: dataCahaya.map(item => item.waktu), // Asumsi ada kolom 'waktu'
                        datasets: [{
                            label: 'Intensitas Cahaya',
                            data: dataCahaya.map(item => item.id_cahaya), // Asumsi ada kolom 'id_cahaya'
                            borderColor: 'rgb(255, 159, 64)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'hour'
                                }
                            }
                        }
                    }
                });

                // --- Grafik Suhu ---
                const ctxSuhu = document.getElementById('suhuChart').getContext('2d');
                new Chart(ctxSuhu, {
                    type: 'line',
                    data: {
                        labels: dataSuhu.map(item => item.waktu), // Asumsi ada kolom 'waktu'
                        datasets: [{
                            label: 'Suhu (°C)',
                            data: dataSuhu.map(item => item.id_suhu), // Asumsi ada kolom 'id_suhu'
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'hour'
                                }
                            }
                        }
                    }
                });

            </script>
        </div>
        </main>

    <footer>
        <div class="w-full shadow rounded-t-3xl px-8 py-4 flex items-center justify-center border-2"
            style="background-color: #3b82f6">
            <p class="text-sm text-white">&copy; 2024 Ambalabu. All rights reserved.</p>
        </div>
    </footer>
    
</body>

</html>