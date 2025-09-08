@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-center">
                    <div class="flex items-center space-x-4">
                        <!-- Step 1 -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                ✓
                            </div>
                            <span class="ml-2 text-sm font-medium text-green-600">Pilih Perjalanan</span>
                        </div>

                        <!-- Connector -->
                        <div class="w-16 h-1 bg-blue-600"></div>

                        <!-- Step 2 -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                2
                            </div>
                            <span class="ml-2 text-sm font-medium text-blue-600">Pilih Rute</span>
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

            <!-- Step 1 Summary -->
            <div class="bg-white rounded-lg shadow mb-6 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">{{ $step1Data['kota_asal'] }}</span>
                            <span class="mx-2">→</span>
                            <span class="font-medium">{{ $step1Data['kota_tujuan'] }}</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            📅 {{ \Carbon\Carbon::parse($step1Data['tanggal'])->format('d M Y') }}
                        </div>
                    </div>
                    <a href="{{ route('pesan') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Ubah Perjalanan
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Pilih Rute Perjalanan</h1>
                    <p class="text-gray-600">Pilih jadwal keberangkatan yang tersedia</p>
                </div>

                @if($jadwals->isEmpty())
                    <div class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Jadwal Tersedia</h3>
                        <p class="text-gray-600 mb-6">Belum ada jadwal untuk rute dan tanggal yang dipilih.</p>
                        <a href="{{ route('pesan') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Cari Perjalanan Lain
                        </a>
                    </div>
                @else
                    <div class="grid gap-4">
                        @foreach($jadwals as $jadwal)
                            <div class="border border-gray-200 rounded-lg p-6 hover:border-blue-300 hover:shadow-md transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4 mb-3">
                                            <div class="text-lg font-semibold text-gray-900">
                                                {{ $jadwal->rute->kota_asal }} → {{ $jadwal->rute->kota_tujuan }}
                                            </div>
                                            <div class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                                {{ $jadwal->jam }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">Tanggal:</span><br>
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Jam:</span><br>
                                                {{ $jadwal->jam }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Harga:</span><br>
                                                Rp {{ number_format($jadwal->harga, 0, ',', '.') }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Mobil:</span><br>
                                                {{ $jadwal->mobil->merk ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ml-6">
                                        <form action="{{ route('booking.step2.process') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                                            <button type="submit"
                                                    class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                                Pilih & Lanjutkan
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
