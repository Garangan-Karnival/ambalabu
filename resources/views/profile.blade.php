<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-[#F6F8FC] min-h-screen flex flex-col">

    <main class="flex-1">

        <!-- NAVBAR -->
        <nav class="w-full bg-white shadow rounded-b-3xl px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-16">
                <a href="/" class="text-sm hover:text-blue-600">Home</a>
                <a href="/grafik" class="text-sm hover:text-blue-600">Grafik</a>
                <a href="/profile" class="text-sm text-blue-600 font-bold border-b-2 border-blue-600 pb-1">Profile</a>
            </div>

            <div class="flex gap-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-500 text-white px-5 py-2 rounded-xl hover:bg-red-600">
                        Logout
                    </button>
                </form>
            </div>
        </nav>

        <div class="p-6 max-w-xl mx-auto">
            <h1 class="text-3xl font-extrabold text-gray-800 mb-8 text-center">Profil Akun</h1>

            {{-- ALERT SUCCESS UPDATE --}}
            @if (session('update_success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                    <strong>Berhasil!</strong> Profil berhasil diperbarui.
                </div>
            @endif

            <div class="bg-white shadow-2xl rounded-3xl p-8 border border-gray-100">

                <div class="flex flex-col items-center mb-10 pb-6 border-b border-gray-100">
                    <div class="w-28 h-28 bg-blue-500 rounded-full flex items-center justify-center text-white text-4xl font-bold">
                        {{ strtoupper(substr($user->username, 0, 1)) }}
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mt-4">{{ $user->username }}</h2>
                    <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Username</label>
                        <input type="text" name="username"
                            value="{{ old('username', $user->username) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm">
                    </div>

                    <!-- Email (disabled) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Email</label>
                        <input type="email" disabled
                            value="{{ $user->email }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100 text-gray-500">
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-bold tracking-wide py-3 rounded-xl hover:bg-blue-700 transition duration-300">
                        Simpan Perubahan
                    </button>
                </form>

                <div class="mt-10 pt-6 border-t border-gray-200">
                    <h3 class="text-xl font-semibold text-red-600 mb-4">Keamanan</h3>
                    <a href="{{ route('password.change') }}">
                        <button class="w-full bg-red-500 text-white font-semibold py-3 rounded-xl hover:bg-red-600">
                            Ganti Password
                        </button>
                    </a>
                </div>

            </div>
        </div>
    </main>

</body>
</html>
