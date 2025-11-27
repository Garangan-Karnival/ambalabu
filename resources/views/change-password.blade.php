<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#F6F8FC] min-h-screen flex flex-col">

    <main class="flex-1 flex justify-center items-center p-6">
        <div class="w-full max-w-md bg-white shadow-2xl rounded-3xl p-8 border border-gray-100">

            <!-- BACK BUTTON -->
            <a href="/profile"
                class="flex items-center text-white text-lg hover:text-gray-200 transition mb-6 pr-4 pl-4 pt-px pb-px w-min bg-gray-900 rounded-xl">
                <span class="text-2xl font-bold mr-2">&larr;</span>
                <span class="text-sm">Back</span>
            </a>

            <h1 class="text-3xl font-semibold text-gray-800 mb-8">Ganti Password</h1>

            <!-- ERROR HANDLING -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-600 px-4 py-3 rounded-xl mb-4">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- SUCCESS -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Password Lama -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Password Lama</label>
                    <input type="password" name="old_password"
                        class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Masukkan password lama">
                </div>

                <!-- Password Baru -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Password Baru</label>
                    <input type="password" name="new_password"
                        class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Password baru">
                </div>

                <!-- Konfirmasi Password Baru -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation"
                        class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Konfirmasi password baru">
                </div>

                <button
                    class="w-full mt-4 bg-blue-600 text-white py-3 rounded-xl shadow hover:bg-blue-700 transition">
                    Ganti Password →
                </button>
            </form>

        </div>
    </main>

</body>

</html>
