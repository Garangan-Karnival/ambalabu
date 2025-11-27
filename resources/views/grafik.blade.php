<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sensor Real-Time</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
</head>

<body class="bg-[#F6F8FC] min-h-screen flex flex-col">

    <main class="flex-1">

        <!-- NAVBAR -->
        <nav class="w-full bg-white shadow rounded-b-3xl px-8 py-4 flex items-center justify-between">

            <div class="flex items-center gap-16">
                <a href="{{ route('home') }}" class="flex flex-col items-center hover:text-blue-600">
                    <span class="text-sm mt-1">Home</span>
                </a>

                <a href="{{ route('grafik') }}" class="flex flex-col items-center text-blue-600 font-semibold">
                    <span class="text-sm mt-1">Grafik</span>
                </a>

                <a href="{{ route('profile.show') }}" class="flex flex-col items-center hover:text-blue-600">
                    <span class="text-sm mt-1">Profile</span>
                </a>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('login.page') }}"
                    class="bg-blue-500 text-white px-5 py-2 rounded-xl hover:bg-blue-600">
                    Sign Up
                </a>
            </div>

        </nav>

        <!-- PAGE CONTENT -->
        <div class="p-8">

            <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">
                Data Sensor User ID: {{ $user_id ?? 'N/A' }}
            </h1>

            <!-- ERROR MESSAGE -->
            @if (isset($error_message) && $error_message)
                <div id="alert-error"
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    <strong class="font-bold">Perhatian!</strong>
                    <span class="block sm:inline">{{ $error_message }}</span>
                    <p class="mt-2 text-sm">Saran: Coba input data baru dari sensor, atau cek ID User.</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Cahaya -->
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition">
                    <h2 class="text-xl font-semibold text-blue-600 mb-4">Intensitas Cahaya (LDR)</h2>
                    <canvas id="cahayaChart"></canvas>
                </div>

                <!-- Suhu + Kelembapan -->
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition">
                    <h2 class="text-xl font-semibold text-green-600 mb-4">Suhu & Kelembapan (DHT11)</h2>
                    <canvas id="suhukelembapanChart"></canvas>
                </div>

                <!-- Hujan -->
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition">
                    <h2 class="text-xl font-semibold text-indigo-600 mb-4">Intensitas Hujan (Raindrop)</h2>
                    <canvas id="raindropChart"></canvas>
                </div>

            </div>

        </div>

    </main>

    <footer>
        <div class="w-full shadow rounded-t-3xl px-8 py-4 flex items-center justify-center border-2"
            style="background-color: #3b82f6">
            <p class="text-sm text-white">&copy; 2024 Ambalabu. All rights reserved.</p>
        </div>
    </footer>

    <!-- JS GRAFIK -->
    <script>
        const jsonCahaya = @json($json_data_cahaya ?? '[]');
        const jsonSuhu = @json($json_data_suhu ?? '[]');
        const jsonRaindrop = @json($json_data_raindrop ?? '[]');

        let dataCahaya = [];
        let dataSuhu = [];
        let dataRaindrop = [];

        try {
            dataCahaya = JSON.parse(jsonCahaya);
            dataSuhu = JSON.parse(jsonSuhu);
            dataRaindrop = JSON.parse(jsonRaindrop);
        } catch (e) {
            console.error("Gagal memproses JSON:", e);
        }

        const formatTime = (time) => {
            const date = new Date(time);
            return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        };

        // CHART 1: Cahaya
        if (dataCahaya.length > 0) {
            new Chart(document.getElementById('cahayaChart'), {
                type: 'line',
                data: {
                    labels: dataCahaya.map(i => formatTime(i.waktu)),
                    datasets: [{
                        label: 'Intensitas Cahaya',
                        data: dataCahaya.map(i => i.intensitas_cahaya),
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        borderColor: 'rgba(59,130,246,1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: { responsive: true }
            });
        }

        // CHART 2: Suhu & Kelembapan
        if (dataSuhu.length > 0) {
            new Chart(document.getElementById('suhukelembapanChart'), {
                type: 'bar',
                data: {
                    labels: dataSuhu.map(i => formatTime(i.waktu)),
                    datasets: [
                        {
                            label: 'Suhu (°C)',
                            data: dataSuhu.map(i => i.temperature),
                            backgroundColor: 'rgba(16,185,129,0.7)',
                            borderColor: 'rgba(16,185,129,1)',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Kelembapan (%)',
                            data: dataSuhu.map(i => i.kelembapan),
                            backgroundColor: 'rgba(251,191,36,0.7)',
                            borderColor: 'rgba(251,191,36,1)',
                            type: 'line',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { type: 'linear', position: 'left' },
                        y1: { type: 'linear', position: 'right', grid: { drawOnChartArea: false }, max: 100 }
                    }
                }
            });
        }

        // CHART 3: Raindrop
        if (dataRaindrop.length > 0) {
            new Chart(document.getElementById('raindropChart'), {
                type: 'line',
                data: {
                    labels: dataRaindrop.map(i => formatTime(i.waktu)),
                    datasets: [{
                        label: 'Intensitas Hujan',
                        data: dataRaindrop.map(i => i.intensitas_hujan),
                        backgroundColor: 'rgba(99,102,241,0.1)',
                        borderColor: 'rgba(99,102,241,1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: { responsive: true }
            });
        }
    </script>

</body>

</html>
