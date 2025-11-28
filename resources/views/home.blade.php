<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Sheen animation */
        #daynightBar {
            position: relative;
            overflow: hidden;
        }

        #daynightBar.sheen::after {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(120deg,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.35) 50%,
                    rgba(255, 255, 255, 0) 100%);
            animation: sheenSlide 1.4s ease;
        }

        @keyframes sheenSlide {
            0% {
                left: -100%;
            }

            100% {
                left: 140%;
            }
        }

        /* Put label inside the bar */
        #dayPhaseTextInside {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            color: white;
            text-shadow: 0px 0px 6px rgba(0, 0, 0, 0.5);
        }

        /* SUN */
        .sun {
            position: absolute;
            top: 50%;
            width: 26px;
            height: 26px;
            background: radial-gradient(circle, #ffeb3b, #ff9800);
            border-radius: 50%;
            box-shadow: 0px 0px 10px rgba(255, 200, 50, 0.8);
            transform: translateY(-50%);
            z-index: 10;
            transition: left 1s linear, opacity 1s linear;
        }

        /* MOON */
        .moon {
            position: absolute;
            top: 50%;
            width: 18px;
            height: 18px;
            background: radial-gradient(circle, #ffffff, #d0d0d0);
            border-radius: 50%;
            box-shadow: 0px 0px 8px rgba(255, 255, 255, 0.8);
            transform: translateY(-50%);
            z-index: 10;
            transition: left 1s linear, opacity 1s linear;
        }

        /* CLOUDS */
        .cloud {
            position: absolute;
            top: 10%;
            width: 140px;
            height: 50px;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 50px;
            filter: blur(2px);
            animation: cloudMove 40s linear infinite;
            z-index: 5;
        }

        @keyframes cloudMove {
            0% {
                left: -150px;
            }

            100% {
                left: 120%;
            }
        }

        /* RAIN */
        .rain-drop {
            position: absolute;
            width: 2px;
            height: 12px;
            background: rgba(180, 200, 255, 0.8);
            top: -20px;
            border-radius: 5px;
            animation: rainFall 0.6s linear infinite;
            z-index: 6;
        }

        @keyframes rainFall {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(80px);
            }
        }

        /* FOG */
        .fog {
            position: absolute;
            top: 0;
            width: 180%;
            height: 100%;
            background: rgba(255, 255, 255, 0.25);
            filter: blur(8px);
            animation: fogDrift 20s linear infinite;
            z-index: 4;
        }

        @keyframes fogDrift {
            0% {
                left: -40%;
            }

            100% {
                left: 10%;
            }
        }

        /* STARS */
        .star {
            position: absolute;
            width: 3px;
            height: 3px;
            background: white;
            border-radius: 50%;
            opacity: 0;
            animation: starTwinkle 3s ease-in-out infinite;
            z-index: 3;
        }

        @keyframes starTwinkle {
            50% {
                opacity: 0.9;
            }
        }
    </style>

</head>

<body class="bg-[#F6F8FC] min-h-screen flex flex-col">

    <main class="flex-1">

        <!-- NAVBAR -->
        <nav class="w-full bg-white shadow rounded-b-3xl px-8 py-4 flex items-center justify-between">

            <div class="flex items-center gap-16">
                <a href="{{ route('home') }}" class="flex flex-col items-center hover:text-blue-600">
                    <span class="text-sm mt-1">Home</span>
                </a>

                <a href="{{ route('grafik') }}" class="flex flex-col items-center hover:text-blue-600">
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
        <div class="p-6">

            <!-- HEADER -->
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
                    The roof will automatically adjust to the
                    <span class="text-yellow-600">current weather conditions.</span>
                </p>
            </div>

            <!-- Sunrise/Sunset -->
            <div class="relative w-full h-12 rounded-2xl mb-6" id="daynightBar">
                <div id="sun" class="sun" style="opacity: 0;"></div>
                <div id="moon" class="moon" style="opacity: 0;"></div>
                <div id="fogLayer" class="fog" style="display:none;"></div>

                <!-- Clouds generated by JS -->
                <div id="cloudContainer"></div>

                <!-- Stars generated by JS -->
                <div id="starContainer"></div>

                <span id="dayPhaseTextInside">Loading...</span>
            </div>
            <p id="currentTimeClock" class="text-gray-500 mb-2">--:--</p>



            <!-- MAIN GRID -->
            <div class="flex flex-col lg:flex-row gap-10">

                <!-- LEFT: REAL TIME EXECUTIONS -->
                <div class="flex flex-col">
                    <p class="text-gray-700 font-medium mb-2">Real-Time Roof Monitoring</p>

                    <div class="bg-white rounded-2xl shadow p-4 inline-block w-fit">
                        <img src="/images/open-roof-house.png" class="w-[500px] rounded-xl" alt="Roof Monitoring Image">
                    </div>
                </div>

                <!-- RIGHT: WEATHER CARDS -->
                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Weather Overview</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- CARD 1: UV INDEX -->
                        <div id="uvCard"
                            class="bg-white shadow rounded-2xl p-6 flex gap-4 items-start transition-all duration-500">
                            <img src="/images/sun.png" class="w-10 h-10" alt="sun">

                            @php
                                $uvLabel = match (true) {
                                    is_null($uv) => '-',
                                    $uv <= 2 => 'Rendah',
                                    $uv <= 5 => 'Sedang',
                                    $uv <= 7 => 'Tinggi',
                                    default => 'Sangat Tinggi'
                                };

                                $uvDesc = match (true) {
                                    is_null($uv) => 'Data UV tidak tersedia.',
                                    $uv <= 2 => 'UV rendah, aman berada di luar ruangan.',
                                    $uv <= 5 => 'UV sedang. Waspadai panas berlebih.',
                                    $uv <= 7 => 'UV tinggi! Gunakan pelindung.',
                                    default => 'UV sangat tinggi — hindari paparan langsung.'
                                };
                            @endphp

                            <div>
                                <p class="font-semibold text-gray-700 text-lg">Tingkat Paparan Sinar Matahari</p>
                                <p class="text-3xl font-bold text-yellow-600">
                                    {{ $uv ?? '-' }}
                                    <span class="text-sm text-gray-500">({{ $uvLabel }})</span>
                                </p>
                                <p class="text-gray-500 text-sm mt-1">{{ $uvDesc }}</p>
                            </div>
                        </div>

                        <!-- CARD 2: TEMPERATURE -->
                        <div class="bg-white shadow rounded-2xl p-6 flex gap-4 items-start">
                            <img src="/images/temp.png" class="w-10 h-10" alt="temp">

                            <div>
                                <p class="font-semibold text-gray-700 text-lg">Perkiraan Suhu Saat Ini</p>
                                <p class="text-3xl font-bold text-red-600">
                                    {{ is_null($temp) ? '-' : $temp . '°C' }}
                                </p>
                                <p class="text-gray-500 text-sm mt-1">Suhu dapat terasa lebih panas karena kelembapan.
                                </p>
                            </div>
                        </div>

                        <!-- CARD 3: HUMIDITY -->
                        <div class="bg-white shadow rounded-2xl p-6 flex gap-4 items-start">
                            <img src="/images/humidity.png" class="w-10 h-10" alt="humidity">

                            <div>
                                <p class="font-semibold text-gray-700 text-lg">Tingkat Kelembapan Saat Ini</p>
                                <p class="text-3xl font-bold text-blue-600">{{ $humidity ?? '-' }}%</p>
                                <p class="text-gray-500 text-sm mt-1">
                                    Kelembapan tinggi membuat suhu terasa lebih panas.
                                </p>
                            </div>
                        </div>

                        <!-- CARD 4: CONDITION -->
                        <div class="bg-white shadow rounded-2xl p-6 flex gap-4 items-start">
                            <img src="/images/cloud.png" class="w-10 h-10" alt="cloud">

                            <div>
                                <p class="font-semibold text-gray-700 text-lg">Status Cuaca dari API</p>
                                <p class="text-3xl font-bold text-blue-600">{{ $condition ?? 'Unknown' }}</p>
                                <p class="text-gray-500 text-sm mt-1">
                                    Data diambil langsung dari WeatherAPI untuk {{ $city ?? 'lokasi' }}.
                                </p>
                            </div>

                        </div><!-- end card 4 -->

                    </div><!-- end card grid -->
                </div><!-- end right section -->

            </div><!-- end main grid -->

        </div><!-- end p-6 wrapper -->

    </main>

    <footer>
        <div class="w-full shadow rounded-t-3xl px-8 py-4 flex items-center justify-center border-2"
            style="background-color: #3b82f6">
            <p class="text-sm text-white">&copy; 2024 Ambalabu. All rights reserved.</p>
        </div>
    </footer>

    <script>
    // Sunrise / sunset from Laravel
    const sunrise = "{{ $sunriseTime }}";
    const sunset = "{{ $sunsetTime }}";

    function toMinutes(str) {
        const [h, m] = str.split(':').map(Number);
        return h * 60 + m;
    }

    const sunriseMin = toMinutes(sunrise);
    const sunsetMin = toMinutes(sunset);

    // Colors
    const night = [12, 20, 69];
    const sunriseC = [255, 181, 102];
    const day = [135, 206, 250];
    const sunsetC = [255, 217, 147];

    function interpolate(a, b, t) { return a + (b - a) * t; }
    function mix(c1, c2, t) {
        return `rgb(
            ${Math.round(interpolate(c1[0], c2[0], t))},
            ${Math.round(interpolate(c1[1], c2[1], t))},
            ${Math.round(interpolate(c1[2], c2[2], t))}
        )`;
    }

    let lastHour = -1;

    function updateSkyEffects(minutes) {
        const totalDay = 24 * 60;

        const sunEl = document.getElementById("sun");
        const moonEl = document.getElementById("moon");
        const cloudContainer = document.getElementById("cloudContainer");
        const starContainer = document.getElementById("starContainer");
        const fogLayer = document.getElementById("fogLayer");

        // SUN movement
        const sunPos = (minutes - sunriseMin) / (sunsetMin - sunriseMin);
        if (sunPos >= 0 && sunPos <= 1) {
            sunEl.style.opacity = 1;
            moonEl.style.opacity = 0;
            sunEl.style.left = (sunPos * 90 + 5) + "%";
        }

        // MOON movement
        const moonPos = minutes < sunriseMin
            ? minutes / sunriseMin
            : (minutes - (sunsetMin + 120)) / (totalDay - (sunsetMin + 120));

        if (minutes < sunriseMin || minutes > sunsetMin + 120) {
            moonEl.style.opacity = 1;
            sunEl.style.opacity = 0;
            moonEl.style.left = (moonPos * 90 + 5) + "%";
        }

        // Stars
        starContainer.style.opacity =
            (minutes < sunriseMin || minutes > sunsetMin + 120) ? 1 : 0;

        // Fog
        fogLayer.style.display =
            (minutes >= sunriseMin - 40 && minutes <= sunriseMin + 40)
            ? "block" : "none";
    }

    function updateBar() {
        const now = new Date();
        const hour = now.getHours();
        const minutes = now.getHours() * 60 + now.getMinutes();

        const timeClock = document.getElementById("currentTimeClock");
        const uvCard = document.getElementById("uvCard");
        const bar = document.getElementById("daynightBar");
        const labelInside = document.getElementById("dayPhaseTextInside");

        // REAL CLOCK ticking each second
        timeClock.innerText = now.toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit"
        });

        // Hourly sheen animation
        if (hour !== lastHour) {
            bar.classList.add("sheen");
            setTimeout(() => bar.classList.remove("sheen"), 1500);
            lastHour = hour;
        }

        let topColor, bottomColor, textLabel;

        // Night → Sunrise
        if (minutes < sunriseMin) {
            const t = minutes / sunriseMin;
            topColor = mix(night, sunriseC, t);
            bottomColor = mix(night, day, t * 0.5);
            textLabel = "Night";

        // Sunrise
        } else if (minutes < sunriseMin + 120) {
            const t = (minutes - sunriseMin) / 120;
            topColor = mix(sunriseC, day, t);
            bottomColor = mix(night, sunriseC, t);
            textLabel = "Sunrise";

        // Day
        } else if (minutes < sunsetMin) {
            const t = (minutes - (sunriseMin + 120)) / (sunsetMin - (sunriseMin + 120));
            topColor = mix(day, sunsetC, t);
            bottomColor = mix(sunriseC, day, t * 0.5);
            textLabel = "Day";

        // Sunset
        } else if (minutes < sunsetMin + 120) {
            const t = (minutes - sunsetMin) / 120;
            topColor = mix(sunsetC, night, t);
            bottomColor = mix(day, sunsetC, t);
            textLabel = "Sunset";

        // Night
        } else {
            topColor = `rgb(${night[0]}, ${night[1]}, ${night[2]})`;
            bottomColor = `rgb(${night[0] + 10}, ${night[1] + 10}, ${night[2] + 15})`;
            textLabel = "Night";
        }

        bar.style.background = `linear-gradient(to right, ${topColor}, ${bottomColor})`;
        labelInside.innerText = textLabel;

        // UV noon glow
        if (hour >= 11 && hour <= 14) {
            uvCard.classList.add("bg-yellow-200");
        } else {
            uvCard.classList.remove("bg-yellow-200");
        }

        // Now update animated sky
        updateSkyEffects(minutes);
    }

    // Run immediately
    updateBar();

    // Update live every second
    setInterval(updateBar, 1000);
</script>


</body>

</html>