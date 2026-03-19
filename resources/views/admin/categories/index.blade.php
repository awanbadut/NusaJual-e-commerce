@extends('layouts.admin')

@section('title', 'Manajemen Kategori - Admin')

@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-[28px] font-bold text-[#15803D] mb-1">Manajemen Kategori</h1>
            <p class="text-[13px] text-[#78716C]">Tambah, edit, dan hapus kategori produk</p>
        </div>
        <button onclick="openAddModal()"
            class="flex items-center gap-2 px-5 py-2.5 bg-[#15803D] text-white rounded-xl font-bold text-sm hover:bg-[#166534] transition shadow-sm">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Tambah Kategori
        </button>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 text-[13px] flex items-center gap-3">
        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-[13px] flex items-center gap-3">
        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-[13px]">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Stats Bar --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#E5E7EB] text-center">
            <p class="text-[11px] text-[#78716C] mb-1">Total Kategori</p>
            <p class="text-[26px] font-bold text-[#15803D]">{{ $categories->total() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#E5E7EB] text-center">
            <p class="text-[11px] text-[#78716C] mb-1">Aktif</p>
            <p class="text-[26px] font-bold text-green-600">{{ $categories->getCollection()->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#E5E7EB] text-center">
            <p class="text-[11px] text-[#78716C] mb-1">Nonaktif</p>
            <p class="text-[26px] font-bold text-gray-400">{{ $categories->getCollection()->where('is_active', false)->count() }}</p>
        </div>
    </div>

    {{-- Grid Kategori --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
        @forelse($categories as $category)
        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB] overflow-hidden group hover:shadow-md transition duration-200 {{ !$category->is_active ? 'opacity-60' : '' }}">

            {{-- Gambar --}}
            <div class="relative h-36 bg-[#F3F4F6] overflow-hidden">
                @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}"
                    alt="{{ $category->name }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                <div class="w-full h-full flex flex-col items-center justify-center gap-1">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-[10px] text-gray-300">Belum ada gambar</span>
                </div>
                @endif

                {{-- Badge Status --}}
                <div class="absolute top-2 left-2">
                    @if($category->is_active)
                    <span class="px-2 py-0.5 rounded-full bg-green-500 text-white text-[9px] font-bold shadow-sm">Aktif</span>
                    @else
                    <span class="px-2 py-0.5 rounded-full bg-gray-400 text-white text-[9px] font-bold shadow-sm">Nonaktif</span>
                    @endif
                </div>

                {{-- Jumlah Produk --}}
                <div class="absolute top-2 right-2">
                    <span class="px-2 py-0.5 rounded-full bg-black/40 text-white text-[9px] font-bold backdrop-blur-sm">
                        {{ $category->products_count }} produk
                    </span>
                </div>
            </div>

            {{-- Info & Aksi --}}
            <div class="p-3">
                <h3 class="font-bold text-[#111827] text-[13px] line-clamp-1 mb-3">{{ $category->name }}</h3>

                <div class="flex gap-1.5">
                    {{-- Tombol Edit --}}
                    <button
                        onclick="openEditModal(
                            {{ $category->id }},
                            '{{ addslashes($category->name) }}',
                            {{ $category->is_active ? 'true' : 'false' }},
                            '{{ $category->image ? asset('storage/'.$category->image) : '' }}'
                        )"
                        class="flex-1 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white text-[11px] font-bold border border-blue-200 transition text-center">
                        ✏️ Edit
                    </button>

                    {{-- Toggle Aktif/Nonaktif --}}
                    <form action="{{ route('admin.categories.toggleActive', $category) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit"
                            title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                            class="py-1.5 px-2.5 rounded-lg border text-[13px] transition
                                {{ $category->is_active
                                    ? 'bg-yellow-50 text-yellow-600 border-yellow-200 hover:bg-yellow-500 hover:text-white'
                                    : 'bg-green-50 text-green-600 border-green-200 hover:bg-green-500 hover:text-white' }}">
                            {{ $category->is_active ? '⏸' : '▶' }}
                        </button>
                    </form>

                    {{-- Hapus --}}
                    @if($category->products_count == 0)
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                        onsubmit="return confirm('Yakin hapus kategori \'{{ addslashes($category->name) }}\'? Tindakan ini tidak bisa dibatalkan.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            title="Hapus"
                            class="py-1.5 px-2.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white text-[13px] border border-red-200 transition">
                            🗑
                        </button>
                    </form>
                    @else
                    <span title="Tidak bisa dihapus, masih ada produk"
                        class="py-1.5 px-2.5 rounded-lg bg-gray-100 text-gray-300 text-[13px] border border-gray-200 cursor-not-allowed">
                        🗑
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-5 bg-white rounded-2xl border border-dashed border-[#D1D5DB] py-20 text-center">
            <svg class="w-14 h-14 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <p class="font-bold text-[15px] text-gray-400 mb-1">Belum ada kategori</p>
            <p class="text-[13px] text-gray-300 mb-5">Tambahkan kategori pertama untuk memulai</p>
            <button onclick="openAddModal()"
                class="px-6 py-2.5 bg-[#15803D] text-white rounded-xl text-sm font-bold hover:bg-[#166534] transition">
                + Tambah Kategori
            </button>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($categories->hasPages())
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
    @endif

</div>

{{-- ============================== --}}
{{-- MODAL TAMBAH KATEGORI          --}}
{{-- ============================== --}}
<div id="addModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">

        <button onclick="closeAddModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>

        <div class="text-center pt-7 pb-5 px-6 border-b border-gray-100">
            <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-[#15803D]" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h2 class="text-[20px] font-bold text-[#111827]">Tambah Kategori Baru</h2>
            <p class="text-[12px] text-[#6B7280] mt-1">Isi detail kategori produk</p>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            {{-- Nama Kategori --}}
            <div>
                <label class="block text-[12px] font-bold text-[#111827] mb-1.5">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" required
                    placeholder="Contoh: Makanan, Minuman, Kerajinan..."
                    class="w-full px-4 py-2.5 border border-[#D1D5DB] rounded-xl text-[13px] focus:ring-2 focus:ring-[#15803D] focus:border-transparent transition">
            </div>

            {{-- Upload Gambar --}}
            <div>
                <label class="block text-[12px] font-bold text-[#111827] mb-1.5">
                    Gambar Kategori
                    <span class="text-[11px] font-normal text-[#6B7280] ml-1">(JPG, PNG, WEBP - Maks 2MB)</span>
                </label>

                <div id="add_upload_area"
                    class="border-2 border-dashed border-[#D1D5DB] rounded-xl overflow-hidden hover:border-[#15803D] transition cursor-pointer"
                    onclick="document.getElementById('add_image').click()">

                    {{-- Default state --}}
                    <div id="add_upload_placeholder" class="p-6 text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <p class="text-[12px] font-semibold text-[#374151]">Klik untuk upload gambar</p>
                        <p class="text-[11px] text-[#9CA3AF] mt-0.5">atau drag & drop di sini</p>
                    </div>

                    {{-- Preview state --}}
                    <img id="add_image_preview" src="" alt="Preview"
                        class="hidden w-full h-44 object-cover">
                </div>

                <input type="file" id="add_image" name="image" accept="image/*" class="hidden"
                    onchange="handleImagePreview(this, 'add_image_preview', 'add_upload_placeholder')">

                <p id="add_file_name" class="text-[11px] text-[#15803D] mt-1.5 hidden">✓ <span></span></p>
            </div>

            {{-- Status Aktif --}}
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                <input type="checkbox" name="is_active" id="add_is_active" value="1" checked
                    class="w-4 h-4 rounded border-gray-300 text-[#15803D] focus:ring-[#15803D] cursor-pointer">
                <div>
                    <label for="add_is_active" class="text-[13px] font-semibold text-[#111827] cursor-pointer">
                        Aktifkan kategori ini
                    </label>
                    <p class="text-[11px] text-[#6B7280]">Kategori aktif akan tampil di halaman produk</p>
                </div>
            </div>

            <div class="flex gap-3 pt-1">
                <button type="button" onclick="closeAddModal()"
                    class="flex-1 px-4 py-3 border border-[#D1D5DB] text-[#374151] rounded-xl text-[13px] font-bold hover:bg-[#F9FAFB] transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-[#15803D] text-white rounded-xl text-[13px] font-bold hover:bg-[#166534] transition shadow-sm">
                    ✓ Tambah Kategori
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ============================== --}}
{{-- MODAL EDIT KATEGORI            --}}
{{-- ============================== --}}
<div id="editModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">

        <button onclick="closeEditModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>

        <div class="text-center pt-7 pb-5 px-6 border-b border-gray-100">
            <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
            </div>
            <h2 class="text-[20px] font-bold text-[#111827]">Edit Kategori</h2>
            <p class="text-[12px] text-[#6B7280] mt-1">Perbarui detail kategori</p>
        </div>

        <form id="editForm" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf @method('PUT')

            {{-- Nama --}}
            <div>
                <label class="block text-[12px] font-bold text-[#111827] mb-1.5">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="edit_name" required
                    class="w-full px-4 py-2.5 border border-[#D1D5DB] rounded-xl text-[13px] focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            {{-- Gambar Saat Ini --}}
            <div id="edit_current_wrapper" class="hidden">
                <p class="text-[12px] font-bold text-[#111827] mb-1.5">Gambar Saat Ini</p>
                <div class="relative rounded-xl overflow-hidden border border-[#E5E7EB]">
                    <img id="edit_current_image" src="" alt="" class="w-full h-40 object-cover">
                    <label class="absolute bottom-3 right-3 flex items-center gap-2 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-lg text-[11px] font-bold text-red-600 cursor-pointer border border-red-200 hover:bg-red-50 transition shadow-sm">
                        <input type="checkbox" name="remove_image" value="1"
                            class="w-3.5 h-3.5 rounded border-gray-300 text-red-500">
                        🗑 Hapus gambar
                    </label>
                </div>
            </div>

            {{-- Upload Gambar Baru --}}
            <div>
                <label class="block text-[12px] font-bold text-[#111827] mb-1.5">
                    <span id="edit_image_label">Ganti Gambar</span>
                    <span class="text-[11px] font-normal text-[#6B7280] ml-1">(Opsional)</span>
                </label>

                <div class="border-2 border-dashed border-[#D1D5DB] rounded-xl overflow-hidden hover:border-blue-400 transition cursor-pointer"
                    onclick="document.getElementById('edit_image_input').click()">
                    <div id="edit_upload_placeholder" class="p-5 text-center">
                        <svg class="w-8 h-8 mx-auto mb-1.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-[11px] text-[#6B7280]">Klik untuk pilih gambar baru</p>
                    </div>
                    <img id="edit_image_preview" src="" alt="Preview"
                        class="hidden w-full h-40 object-cover">
                </div>

                <input type="file" id="edit_image_input" name="image" accept="image/*" class="hidden"
                    onchange="handleImagePreview(this, 'edit_image_preview', 'edit_upload_placeholder')">
            </div>

            {{-- Status Aktif --}}
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                    class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                <div>
                    <label for="edit_is_active" class="text-[13px] font-semibold text-[#111827] cursor-pointer">
                        Aktifkan kategori ini
                    </label>
                    <p class="text-[11px] text-[#6B7280]">Kategori aktif akan tampil di halaman produk</p>
                </div>
            </div>

            <div class="flex gap-3 pt-1">
                <button type="button" onclick="closeEditModal()"
                    class="flex-1 px-4 py-3 border border-[#D1D5DB] text-[#374151] rounded-xl text-[13px] font-bold hover:bg-[#F9FAFB] transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl text-[13px] font-bold hover:bg-blue-700 transition shadow-sm">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ========== ADD MODAL ==========
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // ========== EDIT MODAL ==========
    function openEditModal(id, name, isActive, imageUrl) {
        // Isi data
        document.getElementById('edit_name').value        = name;
        document.getElementById('edit_is_active').checked = isActive;
        document.getElementById('editForm').action        = `/admin/categories/${id}`;

        // Tampilkan gambar saat ini
        const currentWrapper = document.getElementById('edit_current_wrapper');
        const currentImg     = document.getElementById('edit_current_image');
        if (imageUrl && imageUrl !== '') {
            currentImg.src = imageUrl;
            currentWrapper.classList.remove('hidden');
        } else {
            currentWrapper.classList.add('hidden');
        }

        // Reset upload baru
        document.getElementById('edit_image_input').value = '';
        document.getElementById('edit_image_preview').classList.add('hidden');
        document.getElementById('edit_image_preview').src = '';
        document.getElementById('edit_upload_placeholder').classList.remove('hidden');

        document.getElementById('editModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // ========== PREVIEW GAMBAR ==========
    function handleImagePreview(input, previewId, placeholderId) {
        const file = input.files[0];
        if (!file) return;

        // Validasi ukuran
        if (file.size > 2 * 1024 * 1024) {
            alert('⚠️ Ukuran file terlalu besar! Maksimal 2MB.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            const preview     = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);

            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    // Tutup modal klik di luar
    document.getElementById('addModal').addEventListener('click', function(e) {
        if (e.target === this) closeAddModal();
    });
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
</script>
@endpush