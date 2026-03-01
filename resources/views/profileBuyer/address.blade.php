<x-layouts.profile title="Alamat Saya - Nusa Belanja" headerTitle="Alamat Saya"
    headerSubtitle="Kelola alamat pengiriman yang akan digunakan untuk pesanan Anda">

    <div x-data="locationData()" x-init="initComponent()">

        <div class="flex justify-end mb-4 md:mb-6">
            <button @click="openModal()"
                class="w-full md:w-auto bg-[#0F4C20] hover:bg-[#0b3a18] text-white font-bold py-3 md:py-2.5 px-5 rounded-lg flex items-center justify-center gap-2 transition shadow-sm text-sm">
                <x-heroicon-s-plus class="w-5 h-5" />
                Tambah Alamat Baru
            </button>
        </div>

        <div class="flex flex-col gap-4 md:gap-6">
            @forelse($addresses as $address)
            <div
                class="bg-white border border-gray-200 rounded-xl p-4 md:p-6 shadow-sm relative flex flex-col gap-3 md:gap-4">

                <div class="flex justify-between items-start gap-3">
                    <div class="flex flex-col md:flex-row md:items-center gap-0.5 md:gap-2">
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-[#111827] text-base md:text-lg line-clamp-1">{{
                                $address->receiver_name }}</h3>
                            @if($address->is_primary)
                            <span
                                class="md:hidden bg-[#563B1F] text-white text-[9px] font-bold px-2 py-0.5 rounded-full shrink-0">Utama</span>
                            @endif
                        </div>
                        <span class="hidden md:inline text-gray-300">|</span>
                        <span class="text-gray-500 font-medium text-xs md:text-base">{{ $address->phone }}</span>
                    </div>

                    <div class="shrink-0">
                        @if($address->is_primary)
                        <span
                            class="hidden md:inline-block bg-[#563B1F] text-white text-[10px] font-bold px-3 py-1 rounded-full">Utama</span>
                        @else
                        <form action="{{ route('profile.address.setPrimary', $address->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="bg-[#FEF9C3] hover:bg-[#fef08a] text-[#854D0E] text-[9px] md:text-[10px] font-bold px-2 py-1 md:px-3 md:py-1 rounded-full transition cursor-pointer text-center border border-yellow-300 shadow-sm whitespace-nowrap">
                                Jadikan Utama
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <div class="text-gray-600 text-xs md:text-sm leading-relaxed">
                    <p class="font-medium">{{ $address->detail_address }}</p>
                    <p class="mt-0.5">{{ $address->village_name }}, {{ $address->district_name }}, {{
                        $address->city_name }}, {{ $address->province_name }} {{ $address->postal_code }}</p>
                </div>

                <div
                    class="flex items-center gap-2 md:gap-3 mt-1 md:mt-2 pt-3 md:pt-0 border-t border-gray-100 md:border-none">
                    <button @click="editAddress({{ $address }})"
                        class="flex-1 md:flex-none flex justify-center items-center gap-1.5 md:gap-2 text-[#0F4C20] text-xs font-bold border border-[#0F4C20] rounded-lg px-3 py-2 md:px-4 transition hover:bg-green-50">
                        <x-heroicon-o-pencil-square class="w-4 h-4" />
                        <span>Edit</span>
                    </button>

                    <form action="{{ route('profile.address.destroy', $address->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus alamat ini?');"
                        class="flex-1 md:flex-none flex">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full flex justify-center items-center gap-1.5 md:gap-2 text-red-600 text-xs font-bold border border-red-600 rounded-lg px-3 py-2 md:px-4 transition hover:bg-red-50">
                            <x-heroicon-o-trash class="w-4 h-4" />
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-10 md:py-12 bg-white border border-dashed border-gray-300 rounded-xl">
                <div
                    class="w-12 h-12 md:w-16 md:h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                    <x-heroicon-o-map class="w-6 h-6 md:w-8 md:h-8 text-gray-400" />
                </div>
                <h3 class="text-base md:text-lg font-bold text-gray-900 mb-1">Belum ada alamat</h3>
                <button @click="openModal()" class="text-[#0F4C20] font-bold text-xs md:text-sm hover:underline">Tambah
                    Alamat Sekarang</button>
            </div>
            @endforelse
        </div>

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" x-show="showModal"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <div class="flex min-h-full items-center justify-center p-3 md:p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 w-full max-w-2xl border-t-4 border-[#0F4C20]"
                    x-show="showModal" @click.away="showModal = false" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">

                    <div
                        class="px-4 py-3 md:px-6 md:py-4 border-b border-gray-100 flex justify-between items-center bg-[#F8FCF8]">
                        <h3 class="text-lg md:text-xl font-bold text-[#0F4C20]"
                            x-text="isEdit ? 'Ubah Alamat' : 'Tambah Alamat Baru'"></h3>
                        <button @click="showModal = false"
                            class="text-gray-400 hover:text-gray-600 transition bg-white rounded-full p-1 shadow-sm">
                            <x-heroicon-o-x-mark class="w-5 h-5 md:w-6 md:h-6" />
                        </button>
                    </div>

                    <div x-show="isLoading"
                        class="absolute inset-0 bg-white/80 z-10 flex flex-col items-center justify-center rounded-xl">
                        <svg class="animate-spin h-8 w-8 md:h-10 md:w-10 text-[#0F4C20] mb-3"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <p class="text-xs md:text-sm font-semibold text-[#0F4C20]">Sedang memuat data wilayah...</p>
                    </div>

                    <form :action="isEdit ? updateUrl : storeUrl" method="POST"
                        class="p-4 md:p-6 lg:p-8 flex flex-col gap-4 md:gap-5 max-h-[80vh] overflow-y-auto no-scrollbar">
                        @csrf
                        <template x-if="isEdit">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <input type="hidden" name="latitude" :value="form.latitude">
                        <input type="hidden" name="longitude" :value="form.longitude">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5">
                            <div>
                                <x-form.label for="receiver_name" value="Nama Penerima" class="text-xs md:text-sm" />
                                <x-form.input id="receiver_name" name="receiver_name" x-model="form.receiver_name"
                                    placeholder="Contoh: Nienow-Heidenreich" required class="text-sm py-2" />
                            </div>
                            <div>
                                <x-form.label for="phone" value="Nomor Telepon" class="text-xs md:text-sm" />
                                <x-form.input id="phone" name="phone" x-model="form.phone"
                                    placeholder="Contoh: 0812-xxxx-xxxx" required class="text-sm py-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5">
                            <div>
                                <x-form.label for="province_code" value="Provinsi" class="text-xs md:text-sm" />
                                <div class="relative">
                                    <select id="province_code" name="province_code" x-model="form.province_code"
                                        @change="fetchCities()"
                                        class="w-full rounded-lg border-gray-300 focus:border-[#0F4C20] focus:ring-[#0F4C20] text-sm text-gray-700 appearance-none py-2 md:py-2.5 px-3">
                                        <option value="">Pilih Provinsi</option>
                                        <template x-for="item in provinces" :key="item.code">
                                            <option :value="item.code" x-text="item.name"></option>
                                        </template>
                                    </select>
                                    <x-heroicon-m-chevron-down
                                        class="w-4 h-4 text-gray-400 absolute right-3 top-2.5 pointer-events-none" />
                                </div>
                                <input type="hidden" name="province_name"
                                    :value="getName(provinces, form.province_code)">
                            </div>
                            <div>
                                <x-form.label for="city_code" value="Kota/Kabupaten" class="text-xs md:text-sm" />
                                <div class="relative">
                                    <select id="city_code" name="city_code" x-model="form.city_code"
                                        @change="fetchDistricts()" :disabled="!form.province_code"
                                        class="w-full rounded-lg border-gray-300 focus:border-[#0F4C20] focus:ring-[#0F4C20] text-sm text-gray-700 appearance-none py-2 md:py-2.5 px-3 disabled:bg-gray-100">
                                        <option value="">Pilih Kabupaten</option>
                                        <template x-for="item in cities" :key="item.code">
                                            <option :value="item.code" x-text="item.name"></option>
                                        </template>
                                    </select>
                                    <x-heroicon-m-chevron-down
                                        class="w-4 h-4 text-gray-400 absolute right-3 top-2.5 pointer-events-none" />
                                </div>
                                <input type="hidden" name="city_name" :value="getName(cities, form.city_code)">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-5">
                            <div class="sm:col-span-2 md:col-span-1">
                                <x-form.label for="district_code" value="Kecamatan" class="text-xs md:text-sm" />
                                <div class="relative">
                                    <select id="district_code" name="district_code" x-model="form.district_code"
                                        @change="fetchVillages()" :disabled="!form.city_code"
                                        class="w-full rounded-lg border-gray-300 focus:border-[#0F4C20] focus:ring-[#0F4C20] text-sm text-gray-700 appearance-none py-2 md:py-2.5 px-3 disabled:bg-gray-100">
                                        <option value="">Pilih Kecamatan</option>
                                        <template x-for="item in districts" :key="item.code">
                                            <option :value="item.code" x-text="item.name"></option>
                                        </template>
                                    </select>
                                    <x-heroicon-m-chevron-down
                                        class="w-4 h-4 text-gray-400 absolute right-3 top-2.5 pointer-events-none" />
                                </div>
                                <input type="hidden" name="district_name"
                                    :value="getName(districts, form.district_code)">
                            </div>
                            <div class="sm:col-span-1 md:col-span-1">
                                <x-form.label for="village_code" value="Kelurahan" class="text-xs md:text-sm" />
                                <div class="relative">
                                    <select id="village_code" name="village_code" x-model="form.village_code"
                                        :disabled="!form.district_code"
                                        class="w-full rounded-lg border-gray-300 focus:border-[#0F4C20] focus:ring-[#0F4C20] text-sm text-gray-700 appearance-none py-2 md:py-2.5 px-3 disabled:bg-gray-100">
                                        <option value="">Pilih Kelurahan</option>
                                        <template x-for="item in villages" :key="item.code">
                                            <option :value="item.code" x-text="item.name"></option>
                                        </template>
                                    </select>
                                    <x-heroicon-m-chevron-down
                                        class="w-4 h-4 text-gray-400 absolute right-3 top-2.5 pointer-events-none" />
                                </div>
                                <input type="hidden" name="village_name" :value="getName(villages, form.village_code)">
                            </div>
                            <div class="sm:col-span-1 md:col-span-1">
                                <x-form.label for="postal_code" value="Kode Pos" class="text-xs md:text-sm" />
                                <x-form.input id="postal_code" name="postal_code" x-model="form.postal_code"
                                    placeholder="Masukkan Kode Pos" required class="text-sm py-2 md:py-2.5" />
                            </div>
                        </div>

                        <div>
                            <x-form.label for="detail_address" value="Alamat Lengkap" class="text-xs md:text-sm" />
                            <x-form.textarea id="detail_address" name="detail_address" x-model="form.detail_address"
                                rows="3" placeholder="Nama Jalan, gedung, No. rumah, RT/RW dan patokan"
                                class="text-sm py-2">
                            </x-form.textarea>
                        </div>

                        <div>
                            <label class="block text-xs md:text-sm font-bold text-gray-900 mb-1.5 md:mb-2">Pin Lokasi
                                (Opsional)</label>
                            <div id="map" class="w-full h-40 md:h-64 rounded-lg border border-gray-300 z-0 bg-gray-100">
                            </div>
                            <p class="text-[10px] md:text-xs text-gray-500 mt-1.5">
                                *Geser pin biru ke lokasi rumah Anda. <br>
                                Koordinat: <span class="font-mono text-[#0F4C20]"
                                    x-text="form.latitude ? form.latitude + ', ' + form.longitude : 'Belum dipilih'"></span>
                            </p>
                        </div>

                        <div class="flex items-center gap-3 mt-1 bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <div class="relative inline-block w-5 h-5 shrink-0">
                                <input type="checkbox" id="is_primary" name="is_primary" value="1"
                                    x-model="form.is_primary"
                                    class="w-5 h-5 rounded border-2 border-gray-300 text-[#0F4C20] focus:ring-[#0F4C20] cursor-pointer mt-0.5">
                            </div>
                            <label for="is_primary"
                                class="text-xs md:text-sm text-gray-700 font-bold cursor-pointer">Jadikan Sebagai Alamat
                                Utama</label>
                        </div>

                        <div
                            class="mt-2 flex flex-col-reverse md:flex-row justify-end gap-2 md:gap-3 pt-4 md:pt-6 border-t border-gray-100">
                            <button type="button" @click="showModal = false"
                                class="w-full md:w-auto px-6 py-3 md:py-2.5 rounded-lg bg-[#F3F4F6] text-[#1F2937] font-bold text-sm hover:bg-gray-200 transition">Batal</button>
                            <button type="submit"
                                class="w-full md:w-auto px-8 py-3 md:py-2.5 rounded-lg bg-[#0F4C20] text-white font-bold text-sm hover:bg-[#0b3a18] transition shadow-md">Simpan
                                Alamat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.profile>

<script>
    let map = null;
    let marker = null;

    function locationData() {
        return {
            showModal: false,
            isLoading: false, 
            isEdit: false,
            storeUrl: "{{ route('profile.address.store') }}",
            updateUrl: '',
            
            provinces: [], cities: [], districts: [], villages: [],
            
            form: {
                receiver_name: '', phone: '',
                province_code: '', city_code: '', district_code: '', village_code: '',
                postal_code: '', detail_address: '', is_primary: false,
                latitude: '', longitude: '' 
            },

            initComponent() {
                this.fetchProvinces();
            },

            openModal() {
                this.resetForm();
                this.isEdit = false;
                this.showModal = true;
                this.$nextTick(() => { this.initMap(-0.9471, 100.3542); });
            },
            
            async editAddress(address) {
                this.isEdit = true;
                this.updateUrl = `/profile/address/${address.id}`;
                this.isLoading = true; 
                this.showModal = true; 
                
                this.form.receiver_name = address.receiver_name;
                this.form.phone = address.phone;
                this.form.detail_address = address.detail_address;
                this.form.postal_code = address.postal_code;
                this.form.is_primary = address.is_primary;
                this.form.latitude = address.latitude;
                this.form.longitude = address.longitude;

                this.$nextTick(() => {
                    const lat = address.latitude ? parseFloat(address.latitude) : -0.9471;
                    const lng = address.longitude ? parseFloat(address.longitude) : 100.3542;
                    this.initMap(lat, lng);
                });

                this.form.province_code = address.province_code;
                await this.fetchCities(false); 
                this.form.city_code = address.city_code;
                await this.fetchDistricts(false);
                this.form.district_code = address.district_code;
                await this.fetchVillages(false);
                this.form.village_code = address.village_code;

                this.isLoading = false; 
            },

            initMap(lat, lng) {
                if (map) { map.remove(); map = null; }

                map = L.map('map').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                marker = L.marker([lat, lng], { draggable: true }).addTo(map);

                this.updateCoordinates(lat, lng);

                marker.on('dragend', (e) => {
                    let pos = marker.getLatLng();
                    this.updateCoordinates(pos.lat, pos.lng);
                });

                map.on('click', (e) => {
                    marker.setLatLng(e.latlng);
                    this.updateCoordinates(e.latlng.lat, e.latlng.lng);
                });
                
                setTimeout(() => { map.invalidateSize(); }, 200);
            },

            updateCoordinates(lat, lng) {
                this.form.latitude = lat.toFixed(8);
                this.form.longitude = lng.toFixed(8);
            },

            async updateMapLocation(query) {
                if (!query) return;
                try {
                    let res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=1`);
                    let data = await res.json();
                    if (data && data.length > 0) {
                        let lat = parseFloat(data[0].lat);
                        let lon = parseFloat(data[0].lon);
                        if (map && marker) {
                            map.flyTo([lat, lon], 13);
                            marker.setLatLng([lat, lon]);
                            this.updateCoordinates(lat, lon);
                        }
                    }
                } catch (error) { console.error(error); }
            },

            async fetchProvinces() {
                let res = await fetch('/api/location/provinces');
                this.provinces = await res.json();
            },
            async fetchCities(autoMove = true) {
                if(!this.form.province_code) return;
                if(autoMove) this.updateMapLocation(this.getName(this.provinces, this.form.province_code));
                let res = await fetch(`/api/location/cities/${this.form.province_code}`);
                this.cities = await res.json();
                if(!this.isEdit) { this.form.city_code = ''; this.districts = []; this.villages = []; }
            },
            async fetchDistricts(autoMove = true) {
                if(!this.form.city_code) return;
                if(autoMove) this.updateMapLocation(`${this.getName(this.cities, this.form.city_code)}, ${this.getName(this.provinces, this.form.province_code)}`);
                let res = await fetch(`/api/location/districts/${this.form.city_code}`);
                this.districts = await res.json();
                if(!this.isEdit) { this.form.district_code = ''; this.villages = []; }
            },
            async fetchVillages(autoMove = true) {
                if(!this.form.district_code) return;
                if(autoMove) this.updateMapLocation(`Kecamatan ${this.getName(this.districts, this.form.district_code)}, ${this.getName(this.cities, this.form.city_code)}`);
                let res = await fetch(`/api/location/villages/${this.form.district_code}`);
                this.villages = await res.json();
                if(!this.isEdit) { this.form.village_code = ''; }
            },
            resetForm() {
                this.form = { receiver_name: '', phone: '', province_code: '', city_code: '', district_code: '', village_code: '', postal_code: '', detail_address: '', is_primary: false, latitude: '', longitude: '' };
                this.cities = []; this.districts = []; this.villages = [];
            },
            getName(list, code) {
                let item = list.find(i => i.code == code);
                return item ? item.name : '';
            }
        }
    }
</script>