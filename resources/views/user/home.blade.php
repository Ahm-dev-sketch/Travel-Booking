@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row items-center mt-10">

        {{-- Left side: Text --}}
        <div class="md:w-1/2 space-y-6 px-6" data-aos="fade-right">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                Travel Nyaman, Aman <br>
                Dan Terpercaya Bersama <br>
                <span class="text-blue-600">PT. Pelita Transport </span>
            </h1>
            <p class="text-gray-600">
                Pesan tiket travel dengan mudah dan cepat.
                Cek jadwal keberangkatan, pilih rute, dan pesan tiket Anda secara online.
                Kami siap melayani perjalanan Anda dengan armada yang nyaman dan aman.
            </p>
            <div class="flex gap-4">
                <a href="{{ route('pesan') }}"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                    Pesan Sekarang
                </a>
                <a href="{{ route('jadwal') }}"
                    class="px-6 py-3 border border-gray-400 rounded-lg hover:bg-gray-100 transition">
                    Cek Jadwal & Tarif
                </a>
            </div>
        </div>

        {{-- Right side: Illustration --}}
        <div class="md:w-1/2 mt-10 md:mt-0 flex flex-col items-center" data-aos="fade-left">
            <img src="{{ asset('home.jpg') }}" alt="Ilustrasi pemesanan tiket"
                class="w-full max-w-lg rounded-2xl shadow-xl object-contain" />

            <div class="mt-6 mb-12 text-center">
                <h3 class="font-bold text-xl md:text-2xl">PT. Pelita Transport</h3>
                <p class="text-gray-500 text-base md:text-lg">Travel Terbaik Untuk Anda</p>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <section class="py-16 bg-white -mx-6">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-12" data-aos="fade-up">Layanan Kami</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-lg hover:shadow-lg transition duration-300" data-aos="fade-up"
                    data-aos-delay="100">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bus text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Bus Nyaman</h3>
                    <p class="text-gray-600">Armada bus dengan fasilitas lengkap dan nyaman untuk perjalanan jarak jauh</p>
                </div>
                <div class="text-center p-6 rounded-lg hover:shadow-lg transition duration-300" data-aos="fade-up"
                    data-aos-delay="200">
                    <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Keamanan Terjamin</h3>
                    <p class="text-gray-600">Sopir berpengalaman dan sistem keamanan terdepan untuk perjalanan yang aman</p>
                </div>
                <div class="text-center p-6 rounded-lg hover:shadow-lg transition duration-300" data-aos="fade-up"
                    data-aos-delay="300">
                    <div class="bg-yellow-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-3xl text-yellow-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Tepat Waktu</h3>
                    <p class="text-gray-600">Jadwal keberangkatan dan kedatangan yang tepat waktu sesuai jadwal</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact & Location Section -->
    <section class="py-16 bg-gray-50 -mx-6">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-12" data-aos="fade-up">Hubungi Kami</h2>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div data-aos="fade-right">
                    <h3 class="text-2xl font-semibold mb-6 text-gray-800">Informasi Kontak</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Alamat</h4>
                                <p class="text-gray-600">Jl. Sudirman No. 123, Pekanbaru, Riau 28112</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Telepon</h4>
                                <p class="text-gray-600">+62 761 123456</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="bg-red-100 w-12 h-12 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-red-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Email</h4>
                                <p class="text-gray-600">info@pelitatransport.com</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Jam Operasional</h4>
                                <p class="text-gray-600">Senin - Minggu: 06.00 - 22.00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div data-aos="fade-left">
                    <h3 class="text-2xl font-semibold mb-6 text-gray-800">Lokasi Kami</h3>
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.692873287629!2d101.3479711!3d0.454352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5a9de27931077%3A0xcd486083af3b9367!2sPelita%20Transport!5e0!3m2!1sen!2sid!4v1756371798177!5m2!1sen!2sid"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                    <div class="mt-6">
                        <a href="https://www.google.com/maps/place/Pelita+Transport/@0.454352,101.3479711,17z"
                            target="_blank"
                            class="block w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-300 text-center">
                            <i class="fas fa-directions mr-2"></i>Buka di Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="py-16 bg-blue-900 text-white -mx-6" id="stats-section">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div data-aos="fade-up" data-aos-delay="100">
                    <div class="text-4xl font-bold mb-2 counter" data-target="500">0</div>
                    <p class="text-blue-200">Perjalanan per Bulan</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200">
                    <div class="text-4xl font-bold mb-2 counter" data-target="50">0</div>
                    <p class="text-blue-200">Armada Bus</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="300">
                    <div class="text-4xl font-bold mb-2 counter" data-target="25">0</div>
                    <p class="text-blue-200">Rute Tujuan</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="400">
                    <div class="text-4xl font-bold mb-2 counter" data-target="99" data-suffix="%">0%</div>
                    <p class="text-blue-200">Kepuasan Pelanggan</p>
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- Footer Section - Full Width --}}
@section('footer')
    <footer class="bg-gray-800 text-white py-12 w-full">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">PT. Pelita Transport</h3>
                    <p class="text-gray-300 mb-4">
                        Melayani perjalanan Anda dengan aman, nyaman, dan terpercaya sejak 1995.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition duration-300">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition duration-300">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition duration-300">
                            <i class="fab fa-whatsapp text-lg"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Layanan</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">Travel Antar
                                Kota</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">Charter
                                Bus</a></li>
                        <li><a href="#"
                                class="text-gray-300 hover:text-white transition duration-300">Pariwisata</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">Kargo</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Rute Populer</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">Pekanbaru -
                                Jakarta</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">Pekanbaru -
                                Medan</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">Pekanbaru -
                                Padang</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">Pekanbaru -
                                Batam</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak Cepat</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><i class="fas fa-phone mr-2"></i>+62 761 123456</li>
                        <li><i class="fas fa-envelope mr-2"></i>info@pelitatransport.com</li>
                        <li><i class="fab fa-whatsapp mr-2"></i>+62 812 3456 7890</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">&copy; 2024 PT. Pelita Transport. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
@endsection
