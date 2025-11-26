<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-[#F6F8FC] min-h-screen flex flex-col">
    <main class="flex-1">
        <nav class="w-full bg-white shadow rounded-b-3xl px-8 py-4 flex items-center justify-between">
            
                        <!-- Center Menu -->
                        <div class="flex items-center gap-16">
                            <a href="/" class="flex flex-col items-center text-center hover:text-blue-600">
                                <span class="text-sm mt-1">Home</span>
                            </a>
            
                            <a href="/grafik" class="flex flex-col items-center text-center hover:text-blue-600">
                                <span class="text-sm mt-1">Grafik</span>
                            </a>
            
                            <a href="/profile" class="flex flex-col items-center text-center hover:text-blue-600">
                                <span class="text-sm mt-1">Profile</span>
                            </a>
                        </div>
            
                        <!-- Buttons -->
                        <div class="flex gap-4">
                            <a href="{{ route('login.page') }}" class="bg-blue-500 text-white px-5 py-2 rounded-xl hover:bg-blue-600">
                                Sign Up
                            </a>
                        </div>
            
                    </nav>
        {{-- Encasing --}}

        <div class="p-6">

            {{-- HEADER --}}
            <div class="mb-6">
                @auth
                <h1 class="text-3xl font-semibold text-gray-700">
                    Hi, <span class="text-blue-600 font-bold">{{ Auth::user()->username }}!</span>
                </h1>
                @endauth
                @guest
                <h1 class="text-3xl font-semibold text-gray-700">
                    Hi, <span class="text-blue-600 font-bold">Guest!</span>
                </h1>
                @endguest

                <p class="text-gray-400">
                    The roof will automatically adjust to the <span class="text-yellow-600">current weather
                        conditions.</span>
                </p>
            </div>

            {{-- AIR QUALITY INDEX --}}
            <p class="text-gray-700 font-medium mb-1">Indeks Kualitas Udara</p>
            <div class="w-full h-10 bg-gray-200 rounded-2xl mb-6"></div>

            <div class="flex flex-col lg:flex-row gap-10">

                {{-- LEFT SIDE — REAL TIME MONITORING --}}
                <div class="flex flex-col">
                    <p class="text-gray-700 font-medium mb-2">Real-Time Roof Monitoring</p>

                    <div class="bg-white rounded-2xl shadow p-4 inline-block w-fit">
                        <img src="/images/open-roof-house.png" class="w-[500px] rounded-xl" alt="Roof Monitoring Image">
                    </div>
                </div>

                {{-- RIGHT SIDE — WEATHER OVERVIEW --}}
                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Weather Overview</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Card 1 --}}
                        <div class="bg-white shadow rounded-2xl p-6 flex gap-4 items-start">
                            <img src="/images/sun.png" class="w-10 h-10">
                            <div>
                                <p class="font-semibold text-gray-700 text-lg">Tingkat Paparan Sinar Matahari</p>
                                <p class="text-3xl font-bold text-yellow-600">
                                    3 <span class="text-sm text-gray-500">(Rendah)</span>
                                </p>
                                <p class="text-gray-500 text-sm mt-1">
                                    UV sedang. Atap dapat tetap terbuka, tetapi waspadai peningkatan panas.
                                </p>
                            </div>
                        </div>

                        {{-- Card 2 --}}
                        <div class="bg-white shadow rounded-2xl p-6 flex gap-4 items-start">
                            <img src="/images/temp.png" class="w-10 h-10">
                            <div>
                                <p class="font-semibold text-gray-700 text-lg">Perkiraan Suhu Saat Ini</p>
                                <p class="text-3xl font-bold text-red-600">34°C</p>
                                <p class="text-gray-500 text-sm mt-1">Kelembapan tinggi membuat suhu terasa lebih panas.
                                </p>
                            </div>
                        </div>

                        {{-- Card 3 --}}
                        <div class="bg-white shadow rounded-2xl p-6 flex gap-4 items-start">
                            <img src="/images/humidity.png" class="w-10 h-10">
                            <div>
                                <p class="font-semibold text-gray-700 text-lg">Tingkat Kelembapan Saat Ini</p>
                                <p class="text-3xl font-bold text-blue-600">84%</p>
                                <p class="text-gray-500 text-sm mt-1">Kelembapan tinggi membuat suhu terasa lebih panas.
                                </p>
                            </div>
                        </div>

                        {{-- Card 4 --}}
                        <div class="bg-white shadow rounded-2xl p-6 flex gap-4 items-start">
                            <img src="/images/cloud.png" class="w-10 h-10">
                            <div>
                                <p class="font-semibold text-gray-700 text-lg">Status Cuaca dari Sensor</p>
                                <p class="text-3xl font-bold text-blue-600">Hujan Ringan</p>
                                <p class="text-gray-500 text-sm mt-1">Cuaca berawan dan sedikit basah.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <div class="w-full shadow rounded-t-3xl px-8 py-4 flex items-center justify-center border-2" style="background-color: #3b82f6">
            <p class=" text-sm">&copy; 2024 Ambalabu. All rights reserved.</p>
        </div>
    </footer>
    </div>
</body>

</html>