<x-layouts.profile title="Profil Saya - Nusa Belanja">

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 md:p-8">

        <div class="mb-8 border-b border-gray-100 pb-6">
            <h2 class="text-xl font-bold text-[#2E3B27] mb-1">Informasi Pribadi</h2>
            <p class="text-gray-500 text-sm">Perbarui detail pribadi Anda di sini.</p>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="flex flex-col gap-6">
                <div>
                    <x-form.label for="name" value="Nama Lengkap" />
                    <x-form.input id="name" name="name" :value="old('name', Auth::user()->name)" class="w-full"
                        required />
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-form.label for="email" value="Email" />
                        <x-form.input id="email" type="email" name="email" :value="old('email', Auth::user()->email)"
                            class="bg-gray-50 text-gray-500 cursor-not-allowed" readonly />
                    </div>
                    <div>
                        <x-form.label for="phone" value="Nomor Telepon" />
                        <x-form.input id="phone" name="phone" :value="old('phone', Auth::user()->phone)"
                            placeholder="0812..." />
                    </div>
                </div>

                @php
                $dob = Auth::user()->date_of_birth ? \Carbon\Carbon::parse(Auth::user()->date_of_birth) : null;
                @endphp

                <div>
                    <x-form.label value="Tanggal Lahir" />
                    <div class="grid grid-cols-3 gap-4">
                        <x-form.select name="dob_day">
                            <option value="">Tanggal</option>
                            @for ($i = 1; $i <= 31; $i++) <option value="{{ $i }}" {{ ($dob && $dob->day == $i) ?
                                'selected' : '' }}>{{ $i }}</option>
                                @endfor
                        </x-form.select>

                        <x-form.select name="dob_month">
                            <option value="">Bulan</option>
                            @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                            'September', 'Oktober', 'November', 'Desember'] as $index => $month)
                            <option value="{{ $index + 1 }}" {{ ($dob && $dob->month == ($index + 1)) ? 'selected' : ''
                                }}>{{ $month }}</option>
                            @endforeach
                        </x-form.select>

                        <x-form.select name="dob_year">
                            <option value="">Tahun</option>
                            @for ($i = date('Y'); $i >= 1950; $i--)
                            <option value="{{ $i }}" {{ ($dob && $dob->year == $i) ? 'selected' : '' }}>{{ $i }}
                            </option>
                            @endfor
                        </x-form.select>
                    </div>
                </div>

                {{-- <div>
                    <x-form.label value="Jenis Kelamin" />
                    <div class="flex gap-6 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="gender" value="Perempuan"
                                class="w-5 h-5 text-[#0F4C20] focus:ring-[#0F4C20] border-gray-300" {{
                                Auth::user()->gender == 'Perempuan' ? 'checked' : '' }}>
                            <span
                                class="text-gray-700 font-medium group-hover:text-[#0F4C20] transition">Perempuan</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="gender" value="Laki-laki"
                                class="w-5 h-5 text-[#0F4C20] focus:ring-[#0F4C20] border-gray-300" {{
                                Auth::user()->gender == 'Laki-laki' ? 'checked' : '' }}>
                            <span
                                class="text-gray-700 font-medium group-hover:text-[#0F4C20] transition">Laki-Laki</span>
                        </label>
                    </div>
                </div> --}}
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('home') }}"
                    class="px-6 py-3 rounded-lg bg-[#F0EFE6] text-[#8B4513] font-bold text-sm hover:bg-[#e6e4d6] transition">
                    Batal
                </a>

                {{-- Gunakan x-ui.button jika punya, atau button biasa --}}
                <button type="submit"
                    class="px-6 py-3 rounded-lg bg-[#0F4C20] text-white font-bold text-sm hover:bg-[#0b3a18] transition shadow-md flex items-center gap-2">
                    Simpan Perubahan
                    {{-- Icon Opsional --}}
                    {{--
                    <x-heroicon-m-check class="h-5 w-5" /> --}}
                </button>
            </div>
        </form>
    </div>

</x-layouts.profile>