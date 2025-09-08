@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-center">
                    <div class="flex items-center space-x-4">
                        <!-- Step 1 -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                1
                            </div>
                            <span class="ml-2 text-sm font-medium text-blue-600">Pilih Perjalanan</span>
                        </div>

                        <!-- Connector -->
                        <div class="w-16 h-1 bg-gray-300"></div>

                        <!-- Step 2 -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-bold">
                                2
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500">Pilih Rute</span>
                        </div>

                        <!-- Connector -->
                        <div class="w-16 h-1 bg-gray-300"></div>

                        <!-- Step 3 -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-bold">
                                3
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500">Pilih Kursi</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Pilih Perjalanan Anda</h1>
                    <p class="text-gray-600">Tentukan kota asal, tujuan, dan tanggal perjalanan</p>
                </div>

                <form action="{{ route('booking.step1') }}" method="POST" class="max-w-2xl mx-auto">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Kota Awal -->
                        <div>
                            <label for="kota_awal" class="block text-sm font-medium text-gray-700 mb-2">
                                Kota Awal
                            </label>
                            <select id="kota_awal" name="kota_awal" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Pilih Kota Awal</option>
                                @foreach($kotaAwal as $kota)
                                    <option value="{{ $kota }}" {{ old('kota_awal') == $kota ? 'selected' : '' }}>
                                        {{ $kota }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kota_awal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kota Tujuan -->
                        <div>
                            <label for="kota_tujuan" class="block text-sm font-medium text-gray-700 mb-2">
                                Kota Tujuan
                            </label>
                            <select id="kota_tujuan" name="kota_tujuan" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Pilih Kota Tujuan</option>
                                @foreach($kotaTujuan as $kota)
                                    <option value="{{ $kota }}" {{ old('kota_tujuan') == $kota ? 'selected' : '' }}>
                                        {{ $kota }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kota_tujuan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-8">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Keberangkatan
                        </label>
                        <input type="date" id="tanggal" name="tanggal"
                               value="{{ old('tanggal', date('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit"
                                class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Cari Perjalanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-swap cities if user selects same city for both
    const kotaAwal = document.getElementById('kota_awal');
    const kotaTujuan = document.getElementById('kota_tujuan');

    function validateCities() {
        if (kotaAwal.value && kotaTujuan.value && kotaAwal.value === kotaTujuan.value) {
            alert('Kota awal dan tujuan tidak boleh sama');
            kotaTujuan.value = '';
        }
    }

    kotaAwal.addEventListener('change', validateCities);
    kotaTujuan.addEventListener('change', validateCities);
});
</script>
@endpush
