<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NusaJual</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f5f5dc] min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <!-- Logo -->
        <div class="flex items-center justify-center mb-6">
            <div class="bg-green-800 text-white px-4 py-2 rounded font-bold mr-2">Logo</div>
            <span class="text-xl font-semibold">Nusa Belanja</span>
        </div>

        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang</h1>
            <p class="text-gray-600">Masuk sebagai penjual untuk mengelola toko Anda</p>
        </div>

        <!-- Alert Error -->
        @if($errors->any())
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4 flex items-start">
            <span class="text-amber-600 mr-2">⚠️</span>
            <div class="text-sm text-amber-800">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Alert Success -->
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4 flex items-start">
            <span class="text-green-600 mr-2">✓</span>
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('seller.login.submit') }}" method="POST">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('email') border-red-500 @enderror" 
                    placeholder="nama@email.com" 
                    required>
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('password') border-red-500 @enderror" 
                    placeholder="••••••••" 
                    required>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full bg-green-800 text-white py-3 rounded-lg font-semibold hover:bg-green-900 transition flex items-center justify-center">
                Login Sekarang →
            </button>
        </form>

        <!-- Register Link -->
        <p class="text-center text-gray-600 mt-4">
            Belum Punya Akun? 
            <a href="{{ route('seller.register') }}" class="text-green-800 font-semibold hover:underline">
                Daftar Sekarang
            </a>
        </p>
    </div>
</body>
</html>
