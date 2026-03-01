<x-layouts.profile title="Profil Saya - Nusa Belanja">

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 md:p-8">

        <div class="mb-4 md:mb-8 border-b border-gray-100 pb-3 md:pb-6">
            <h2 class="text-lg md:text-xl font-bold text-[#2E3B27] mb-0.5 md:mb-1">Informasi Pribadi</h2>
            <p class="text-gray-500 text-xs md:text-sm">Perbarui detail pribadi Anda di sini.</p>
        </div>

        @if(session('success'))
        <div
            class="mb-4 md:mb-6 p-3 md:p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-xs md:text-sm font-medium shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="flex flex-col gap-4 md:gap-6">
                <div>
                    <x-form.label for="name" value="Nama Lengkap" class="text-xs md:text-sm" />
                    <x-form.input id="name" name="name" :value="old('name', Auth::user()->name)"
                        class="w-full text-sm py-2 md:py-2.5" required />
                    @error('name') <span class="text-red-500 text-[10px] md:text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <div>
                        <x-form.label for="email" value="Email" class="text-xs md:text-sm" />
                        <x-form.input id="email" type="email" name="email" :value="old('email', Auth::user()->email)"
                            class="bg-gray-50 text-gray-500 cursor-not-allowed text-sm py-2 md:py-2.5" readonly />
                    </div>
                    <div>
                        <x-form.label for="phone" value="Nomor Telepon" class="text-xs md:text-sm" />
                        <x-form.input id="phone" name="phone" :value="old('phone', Auth::user()->phone)"
                            placeholder="0812..." class="text-sm py-2 md:py-2.5" />
                    </div>
                </div>

                @php
                $dob = Auth::user()->date_of_birth ? \Carbon\Carbon::parse(Auth::user()->date_of_birth) : null;
                @endphp

                <div>
                    <x-form.label value="Tanggal Lahir" class="text-xs md:text-sm" />
                    <div class="grid grid-cols-3 gap-2 md:gap-4 mt-1">
                        <x-form.select name="dob_day" class="text-xs md:text-sm py-2 md:py-2.5 px-2">
                            <option value="">Tgl</option>
                            @for ($i = 1; $i <= 31; $i++) <option value="{{ $i }}" {{ ($dob && $dob->day == $i) ?
                                'selected' : '' }}>{{ $i }}</option>
                                @endfor
                        </x-form.select>

                        <x-form.select name="dob_month" class="text-xs md:text-sm py-2 md:py-2.5 px-2">
                            <option value="">Bulan</option>
                            @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                            'September', 'Oktober', 'November', 'Desember'] as $index => $month)
                            <option value="{{ $index + 1 }}" {{ ($dob && $dob->month == ($index + 1)) ? 'selected' : ''
                                }}>{{ substr($month, 0, 3) }}</option> {{-- Dipersingkat agar muat di HP (Jan, Feb, Mar)
                            --}}
                            @endforeach
                        </x-form.select>

                        <x-form.select name="dob_year" class="text-xs md:text-sm py-2 md:py-2.5 px-2">
                            <option value="">Tahun</option>
                            @for ($i = date('Y'); $i >= 1950; $i--)
                            <option value="{{ $i }}" {{ ($dob && $dob->year == $i) ? 'selected' : '' }}>{{ $i }}
                            </option>
                            @endfor
                        </x-form.select>
                    </div>
                </div>

            </div>

            <div
                class="flex flex-row items-center justify-between md:justify-end gap-2 md:gap-4 mt-6 md:mt-8 pt-4 md:pt-6 border-t border-gray-100">
                <a href="{{ route('home') }}"
                    class="w-full md:w-auto text-center px-4 md:px-6 py-2.5 md:py-3 rounded-lg bg-[#F0EFE6] text-[#8B4513] font-bold text-xs md:text-sm hover:bg-[#e6e4d6] transition">
                    Batal
                </a>

                <button type="submit"
                    class="w-full md:w-auto px-4 md:px-6 py-2.5 md:py-3 rounded-lg bg-[#0F4C20] text-white font-bold text-xs md:text-sm hover:bg-[#0b3a18] transition shadow-md flex justify-center items-center gap-2">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

</x-layouts.profile>