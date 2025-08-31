@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6 flex items-center gap-2" data-aos="fade-down">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
    </svg>
    Dashboard
</h1>

<!-- Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white p-6 rounded shadow flex items-center gap-4" data-aos="zoom-in">
        <div class="bg-blue-100 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 10c-4.41 0-8-1.79-8-4V6c0-2.21 3.59-4 8-4s8 1.79 8 4v8c0 2.21-3.59 4-8 4z" />
            </svg>
        </div>
        <div>
            <h3 class="text-gray-500">Pendapatan (Bulan ini)</h3>
            <div class="flex items-center gap-2">
                <p id="revenue-amount" class="text-2xl font-bold text-blue-700">Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</p>
                <button id="toggle-revenue-visibility" aria-label="Toggle revenue visibility" class="focus:outline-none">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded shadow flex items-center gap-4" data-aos="zoom-in" data-aos-delay="100">
        <div class="bg-green-100 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h8m-8 0V7a2 2 0 012-2h2a2 2 0 012 2v2m0 4h2a2 2 0 012 2v6M9 17h6" />
            </svg>
        </div>
        <div>
            <h3 class="text-gray-500">Pemesanan (Bulan ini)</h3>
            <p class="text-2xl font-bold text-green-700">{{ $jumlahPemesananBulanIni }} Tiket</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded shadow flex items-center gap-4" data-aos="zoom-in" data-aos-delay="200">
        <div class="bg-yellow-100 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10M5 11h14v10H5V11z" />
            </svg>
        </div>
        <div>
            <h3 class="text-gray-500">Perjalanan Aktif</h3>
            <p class="text-2xl font-bold text-yellow-600">{{ $perjalananAktif }} Jadwal</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded shadow flex items-center gap-4" data-aos="zoom-in" data-aos-delay="300">
        <div class="bg-purple-100 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1119 12.804M9 12h.01M15 12h.01" />
            </svg>
        </div>
        <div>
            <h3 class="text-gray-500">Jumlah Pelanggan</h3>
            <p class="text-2xl font-bold text-purple-700">{{ $totalPelanggan }} Orang</p>
        </div>
    </div>
</div>

<!-- Grafik Pendapatan -->
<div class="bg-white p-6 rounded shadow" data-aos="fade-up">
    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19V6a2 2 0 012-2h2m4 0h2a2 2 0 012 2v13a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2h4" />
        </svg>
        Grafik Pendapatan (7 Hari Terakhir)
    </h3>
    <canvas id="chartPendapatan" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartPendapatan');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($labels7Hari) !!},
        datasets: [{
            label: 'Pendapatan',
            data: {!! json_encode($pendapatan7Hari) !!},
            borderColor: '#2563eb',
            backgroundColor: '#3b82f6',
            fill: false,
            tension: 0.3
        }]
    }
});
</script>
@endsection
