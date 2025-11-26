<!DOCTYPE html>
<h lang="en">

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

        <div class="p-6">

            {{-- HEADER --}}
            <div class="mb-6">
                <h1 class="text-3xl font-semibold text-gray-700">
                    Hi, <span class="text-blue-600 font-bold">User!</span>
                </h1>

                <p class="text-gray-400">
                    The roof will automatically adjust to the <span class="text-yellow-600">current weather
                        conditions.</span>
                </p>
            </div>

            {{-- MAIN HORIZONTAL WRAPPER --}}
            <div class="flex gap-10 mt-4 w-full">

                {{-- LEFT: WEATHER 7 DAYS GRAPH --}}
                <div class="flex-1">
                    <h2 class="text-gray-700 font-semibold mb-3">
                        Kondisi cuaca 7 hari terakhir
                    </h2>

                    <div class="w-full h-[520px] bg-white rounded-xl shadow border border-gray-200"></div>
                </div>

                {{-- RIGHT: INFO DETAIL CUACA --}}
                <div class="flex-1">
                    <h2 class="text-gray-700 font-semibold mb-4">
                        Info detail cuaca
                    </h2>

                    <div class="flex flex-col gap-6">

                        {{-- CARD 1 – Temperatur Boninitas --}}
                        <div class="bg-[#EFF2FF] rounded-2xl shadow p-5 text-center">
                            <div class="flex justify-center mb-3">
                                <img src="https://img.icons8.com/ios-filled/50/fa5252/temperature.png"
                                    class="w-10 opacity-80">
                            </div>
                            <h3 class="font-semibold text-gray-700">Temperature</h3>
                            <p class="text-xs text-gray-500 mt-2">
                                Baca grafik suhu dari sensor
                            </p>
                        </div>

                        {{-- CARD 2 – Radiance --}}
                        <div class="bg-[#FEFCE8] rounded-2xl shadow p-5 text-center">
                            <div class="flex justify-center mb-3">
                                <img src="https://img.icons8.com/fluency/48/sun.png" class="w-12">
                            </div>
                            <h3 class="font-semibold text-gray-700">Radiance</h3>
                            <p class="text-xs text-gray-500 mt-2">
                                Grafik intensitas cahaya matahari
                            </p>
                        </div>

                        {{-- CARD 3 – Raindrop --}}
                        <div class="bg-white rounded-2xl shadow p-5 text-center border border-gray-100">
                            <div class="flex justify-center mb-3">
                                <img src="https://img.icons8.com/ios/50/3b82f6/rain.png" class="w-10 opacity-80">
                            </div>
                            <h3 class="font-semibold text-gray-700">Raindrop</h3>
                            <p class="text-xs text-gray-500 mt-2">
                                Grafik jumlah atau intensitas tetesan hujan
                            </p>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="mt-auto">
        <div class="w-full shadow rounded-t-3xl px-8 py-4 flex items-center justify-center border-2"
            style="background-color: #3b82f6">
            <p class="text-sm">&copy; 2024 Ambalabu. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>
