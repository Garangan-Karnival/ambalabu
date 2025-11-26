<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>

    <!-- Tailwind CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>

<body class="min-h-screen flex bg-cover" style="background-image: url('/images/home-design.jpg');">

    <!-- LEFT SIDE — Background Image -->
    <div class="hidden md:block w-1/2 h-screen bg-cover bg-center"
    >
    </div>

    <!-- RIGHT SIDE — Glass Form -->
    <div class="w-full md:w-1/2 flex justify-center items-center px-6 py-10 bg-white bg-opacity-40 backdrop-filter backdrop-blur-xl">

        <div class="w-full max-w-md">

            <!-- LOGIN FORM -->
            <div id="loginForm">

            <!-- BACK BUTTON -->
                <a href="/" class="flex items-center text-white text-lg hover:text-gray-200 transition mb-6 pr-4 pl-4 pt-px pb-px w-min bg-gray-900 rounded-xl">
                    <span class="text-2xl font-bold mr-2">&larr;</span>
                    <span class="text-sm">Back</span>
                </a>

                <h1 class="text-3xl font-semibold text-gray-800 mb-8">Login</h1>

                <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email"
                            class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Enter your Email">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password"
                            class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Enter your password">
                        <p class="text-sm text-blue-600 mt-1 cursor-pointer hover:underline">Forgot password?</p>
                    </div>

                    <p class="text-sm text-gray-600 text-center mt-4">
                        Don’t have an account?
                        <span id="registerBtn"
                            class="text-blue-600 font-semibold cursor-pointer hover:underline">Register here</span>
                    </p>

                    <button
                        class="w-full mt-4 bg-red-600 text-white py-3 rounded-xl shadow hover:bg-red-700 transition">
                        Login →
                    </button>

                </form>
            </div>

            <!-- REGISTER FORM -->
            
            <div id="registerForm" class="hidden">
                <!-- BACK BUTTON -->
                   <a href="/" class="flex items-center text-white text-lg hover:text-gray-200 transition mb-6 pr-4 pl-4 pt-px pb-px w-min bg-gray-900 rounded-xl">
                       <span class="text-2xl font-bold mr-2">&larr;</span>
                       <span class="text-sm">Back</span>
                   </a>

                <h1 class="text-3xl font-semibold text-gray-800 mb-8">Register</h1>

                <form action="{{ route('register.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="username"
                            class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Enter your Name">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email"
                            class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Enter your Email">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password"
                            class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Enter your Password">
                    </div>

                    <p class="text-sm text-gray-600 text-center mt-4">
                        Already have an account?
                        <span id="loginBtn"
                            class="text-blue-600 font-semibold cursor-pointer hover:underline">Login here</span>
                    </p>

                    <button
                        class="w-full mt-4 bg-blue-600 text-white py-3 rounded-xl shadow hover:bg-blue-700 transition">
                        Register →
                    </button>

                </form>
            </div>

        </div>

    </div>

    <!-- Toggle Script -->
    <script>
        const loginForm = document.getElementById("loginForm");
        const registerForm = document.getElementById("registerForm");

        document.getElementById("loginBtn").onclick = () => {
            loginForm.classList.remove("hidden");
            registerForm.classList.add("hidden");
        };

        document.getElementById("registerBtn").onclick = () => {
            loginForm.classList.add("hidden");
            registerForm.classList.remove("hidden");
        };
    </script>

</body>

</html>
