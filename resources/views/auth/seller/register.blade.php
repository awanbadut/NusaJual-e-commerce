<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Sebagai Penjual - Nusa Belanja</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FCF8] min-h-screen flex items-center justify-center p-4 py-6 md:py-10">

    <div x-data="registerSellerData()" x-init="init()"
        class="bg-white w-full max-w-4xl rounded-xl shadow-lg border border-gray-100 p-5 md:p-12 flex flex-col items-center gap-5 md:gap-8 relative">

        <div class="text-center">
            <div class="flex items-center justify-center mb-3 md:mb-4">
                <img src="{{ asset('img/logo/3.jpeg') }}" alt="Icon Nusa Belanja"
                    class="h-8 md:h-10 w-auto object-contain">
            </div>
            <h1 class="text-xl md:text-3xl font-bold text-black mb-1 md:mb-2 tracking-tight">Daftar Sebagai Penjual</h1>
            <p class="text-gray-500 font-medium text-[11px] md:text-sm">Bergabung dan mulai kembangkan bisnis Anda</p>
        </div>

        @if($errors->any())
        <div class="w-full bg-red-50 border border-red-200 rounded-lg p-3 md:p-4 mb-2">
            <ul class="list-disc list-inside text-xs md:text-sm text-red-800 space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('seller.register.submit') }}" method="POST" class="w-full flex flex-col gap-4 md:gap-5">
            @csrf

            <div class="border-b border-gray-200 pb-2 mb-1 md:mb-2 mt-2 md:mt-0">
                <h3 class="text-[#0F4C20] font-bold text-xs md:text-sm uppercase tracking-wide">Informasi Toko</h3>
            </div>

            <div>
                <label for="store_name" class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Nama
                    Toko</label>
                <input id="store_name" type="text" name="store_name" value="{{ old('store_name') }}"
                    class="w-full px-3 py-2.5 md:px-4 md:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20]"
                    placeholder="Contoh: Jaya Selalu" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                <div>
                    <label for="owner_name" class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Nama
                        Pemilik</label>
                    <input id="owner_name" type="text" name="owner_name" value="{{ old('owner_name') }}"
                        class="w-full px-3 py-2.5 md:px-4 md:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20]"
                        placeholder="Nama Lengkap Anda" required />
                </div>
                <div>
                    <label for="phone" class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Nomor
                        Telepon</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                        class="w-full px-3 py-2.5 md:px-4 md:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20]"
                        placeholder="0812-xxxx-xxxx" required />
                </div>
            </div>

            <div class="border-b border-gray-200 pb-2 mb-1 md:mb-2 mt-3 md:mt-4">
                <h3 class="text-[#0F4C20] font-bold text-xs md:text-sm uppercase tracking-wide">Alamat Toko</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                <div>
                    <label class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Provinsi</label>
                    <select name="province_code" x-model="form.province_code" @change="fetchCities()" required
                        class="w-full px-3 py-2.5 md:px-4 md:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] bg-white">
                        <option value="">Pilih Provinsi</option>
                        <template x-for="item in provinces" :key="item.code">
                            <option :value="item.code" x-text="item.name"></option>
                        </template>
                    </select>
                    <input type="hidden" name="province" :value="getName(provinces, form.province_code)">
                </div>

                <div>
                    <label
                        class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Kota/Kabupaten</label>
                    <select name="city_code" x-model="form.city_code" @change="fetchDistricts()"
                        :disabled="!form.province_code" required
                        class="w-full px-3 py-2.5 md:px-4 md:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] bg-white disabled:bg-gray-100">
                        <option value="">Pilih Kota</option>
                        <template x-for="item in cities" :key="item.code">
                            <option :value="item.code" x-text="item.name"></option>
                        </template>
                    </select>
                    <input type="hidden" name="city" :value="getName(cities, form.city_code)">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-5">
                <div class="md:col-span-1">
                    <label class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Kecamatan</label>
                    <select name="district_code" x-model="form.district_code" @change="fetchVillages()"
                        :disabled="!form.city_code" required
                        class="w-full px-3 py-2.5 md:px-4 md:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] bg-white disabled:bg-gray-100">
                        <option value="">Pilih Kecamatan</option>
                        <template x-for="item in districts" :key="item.code">
                            <option :value="item.code" x-text="item.name"></option>
                        </template>
                    </select>
                    <input type="hidden" name="district" :value="getName(districts, form.district_code)">
                </div>

                <div class="md:col-span-1">
                    <label class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Kelurahan</label>
                    <select name="village_code" x-model="form.village_code" ::disabled="!form.district_code" required
                        class="w-full px-3 py-2.5 md:px-4 md:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20] bg-white disabled:bg-gray-100">
                        <option value="">Pilih Kelurahan</option>
                        <template x-for="item in villages" :key="item.code">
                            <option :value="item.code" x-text="item.name"></option>
                        </template>
                    </select>
                    <input type="hidden" name="village" :value="getName(villages, form.village_code)">
                </div>

                <div class="md:col-span-1">
                    <label class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Kode Pos</label>
                    <input type="text" name="postal_code" x-model="form.postal_code"
                        class="w-full px-3 py-2.5 md:px-4 md:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20]"
                        placeholder="Kode Pos" required />
                </div>
            </div>

            <div>
                <label for="address" class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Alamat
                    Lengkap</label>
                <textarea id="address" name="address" rows="3"
                    class="w-full px-3 py-2.5 md:px-4 md:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20]"
                    placeholder="Nama Jalan, Gedung, No. Rumah..." required>{{ old('address') }}</textarea>
            </div>


            <div class="border-b border-gray-200 pb-2 mb-1 md:mb-2 mt-3 md:mt-4">
                <h3 class="text-[#0F4C20] font-bold text-xs md:text-sm uppercase tracking-wide">Akun Login</h3>
            </div>

            <div>
                <label for="email" class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2.5 md:px-4 md:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20]"
                    placeholder="nama@email.com" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                <div>
                    <label for="password"
                        class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password"
                            class="w-full px-3 py-2.5 md:px-4 md:py-3 pr-10 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20]"
                            placeholder="********" required />
                        <button type="button" onclick="togglePassword('password', 'eye-icon-pass')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#0F4C20]">
                            <svg id="eye-icon-pass-off" class="h-4 w-4 md:h-5 md:w-5 hidden" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                            <svg id="eye-icon-pass-on" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="password_confirmation"
                        class="block text-gray-700 font-bold mb-1.5 md:mb-2 text-xs md:text-sm">Konfirmasi
                        Password</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            class="w-full px-3 py-2.5 md:px-4 md:py-3 pr-10 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C20] focus:border-[#0F4C20]"
                            placeholder="********" required />
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-conf')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#0F4C20]">
                            <svg id="eye-icon-conf-off" class="h-4 w-4 md:h-5 md:w-5 hidden" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                            <svg id="eye-icon-conf-on" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="mt-2 md:mt-4 w-full bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold py-3 md:py-3.5 px-6 rounded-lg flex items-center justify-center gap-2 transition shadow-md text-sm md:text-base">
                Daftar Sekarang
                <x-heroicon-m-arrow-right class="h-4 w-4 md:h-5 md:w-5" />
            </button>
        </form>

        <div class="text-center text-xs md:text-sm text-gray-500 mt-1 md:mt-0">
            Sudah Punya Akun?
            <a href="{{ route('seller.login') }}" class="text-[#0F4C20] hover:underline font-bold">
                Login Disini
            </a>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconPrefix) {
            const input = document.getElementById(inputId);
            const iconOn = document.getElementById(iconPrefix + '-on');
            const iconOff = document.getElementById(iconPrefix + '-off');

            if (input.type === "password") {
                input.type = "text";
                iconOn.classList.add('hidden');
                iconOff.classList.remove('hidden');
            } else {
                input.type = "password";
                iconOn.classList.remove('hidden');
                iconOff.classList.add('hidden');
            }
        }

        function registerSellerData() {
            return {
                provinces: [],
                cities: [],
                districts: [],
                villages: [],

                form: {
                    province_code: '',
                    city_code: '',
                    district_code: '',
                    village_code: '',
                    postal_code: ''
                },

                init() {
                    this.fetchProvinces();
                },

                async fetchProvinces() {
                    let res = await fetch('/api/location/provinces');
                    this.provinces = await res.json();
                },
                async fetchCities() {
                    if (!this.form.province_code) return;
                    this.cities = []; this.districts = []; this.villages = [];
                    this.form.city_code = ''; this.form.district_code = ''; this.form.village_code = '';
                    
                    let res = await fetch(`/api/location/cities/${this.form.province_code}`);
                    this.cities = await res.json();
                },
                async fetchDistricts() {
                    if (!this.form.city_code) return;
                    this.districts = []; this.villages = [];
                    this.form.district_code = ''; this.form.village_code = '';
                    
                    let res = await fetch(`/api/location/districts/${this.form.city_code}`);
                    this.districts = await res.json();
                },
                async fetchVillages() {
                    if (!this.form.district_code) return;
                    this.villages = [];
                    this.form.village_code = '';
                    
                    let res = await fetch(`/api/location/villages/${this.form.district_code}`);
                    this.villages = await res.json();
                },
                getName(list, code) {
                    let item = list.find(i => i.code == code);
                    return item ? item.name : '';
                }
            }
        }
    </script>
</body>

</html>