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
                <a href="{{ route('login.page') }}"
                    class="bg-blue-500 text-white px-5 py-2 rounded-xl hover:bg-blue-600">
                    Sign Up
                </a>
            </div>
        </nav>

        <!--  MULAI ISI PROFIL DARI SINI YOSHIDAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA  -->
    </main>
    <footer>
        <div class="w-full shadow rounded-t-3xl px-8 py-4 flex items-center justify-center border-2"
            style="background-color: #3b82f6">
            <p class=" text-sm">&copy; 2024 Ambalabu. All rights reserved.</p>
        </div>
    </footer>
    </div>
</body>

</html>