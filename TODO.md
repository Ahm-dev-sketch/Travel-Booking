# TODO List - Sistem Pemesanan Travel Booking (Dari Project Baru)

Daftar tugas untuk membangun sistem pemesanan tiket travel berbasis Laravel dari project kosong. Setiap fitur utama akan di-commit secara terpisah untuk tracking progress yang baik. Asumsikan mulai dari `composer create-project laravel/laravel travel-booking`.

## 1. Setup Project Dasar
- [ ] Buat project Laravel baru: `composer create-project laravel/laravel travel-booking`
- [ ] Masuk ke direktori project: `cd travel-booking`
- [ ] Install dependencies: `composer install && npm install`
- [ ] Generate app key: `php artisan key:generate`
- [ ] Setup database di .env (MySQL)
- [ ] Setup Tailwind CSS: `npm install -D tailwindcss postcss autoprefixer && npx tailwindcss init -p`
- [ ] Konfigurasi tailwind.config.js dan resources/css/app.css
- [ ] Build asset awal: `npm run build`
- [ ] Commit: "Initial Laravel project setup with Tailwind"

## 2. Database & Model User Dasar
- [ ] Buat migration untuk users: `php artisan make:migration create_users_table`
- [ ] Edit migration users: tambah kolom whatsapp_number, role, dll (hapus email jika perlu)
- [ ] Buat model User: `php artisan make:model User`
- [ ] Edit model User dengan fillable dan casts
- [ ] Buat migration otp_tokens: `php artisan make:migration create_otp_tokens_table`
- [ ] Buat model OtpToken: `php artisan make:model OtpToken`
- [ ] Jalankan migrasi: `php artisan migrate`
- [ ] Commit: "Basic user and OTP models setup"

## 3. Sistem Autentikasi WhatsApp OTP
- [ ] Buat OtpService: `php artisan make:service OtpService` (atau buat manual di app/Services)
- [ ] Buat WhatsappService: `php artisan make:service WhatsappService`
- [ ] Buat AuthController: `php artisan make:controller AuthController`
- [ ] Implementasi method login, register, verify di AuthController
- [ ] Buat middleware Authenticate: `php artisan make:middleware Authenticate`
- [ ] Buat view auth/login.blade.php dan auth/register.blade.php
- [ ] Setup route di routes/web.php untuk auth
- [ ] Commit: "WhatsApp OTP authentication system"

## 4. Model & Migration Entitas Utama
- [ ] Buat migration rutes: `php artisan make:migration create_rutes_table`
- [ ] Buat model Rute: `php artisan make:model Rute`
- [ ] Buat migration mobils: `php artisan make:migration create_mobils_table`
- [ ] Buat model Mobil: `php artisan make:model Mobil`
- [ ] Buat migration supirs: `php artisan make:migration create_supirs_table`
- [ ] Buat model Supir: `php artisan make:model Supir`
- [ ] Buat migration jadwals: `php artisan make:migration create_jadwals_table`
- [ ] Buat model Jadwal: `php artisan make:model Jadwal`
- [ ] Buat migration bookings: `php artisan make:migration create_bookings_table`
- [ ] Buat model Booking: `php artisan make:model Booking`
- [ ] Setup relasi di semua model (belongsTo, hasMany)
- [ ] Jalankan migrasi: `php artisan migrate`
- [ ] Commit: "Core business models and migrations"

## 5. Fitur User - Halaman Utama & Jadwal
- [ ] Buat Controller untuk home: edit app/Http/Controllers/Controller.php atau buat HomeController
- [ ] Buat JadwalController: `php artisan make:controller JadwalController`
- [ ] Buat view user/home.blade.php sebagai landing page
- [ ] Buat view user/jadwal.blade.php untuk list jadwal
- [ ] Setup route untuk / dan /jadwal
- [ ] Commit: "User home and jadwal viewing pages"

## 6. Wizard Pemesanan Tiket (Multi-Step)
- [ ] Buat BookingController: `php artisan make:controller BookingController`
- [ ] Implementasi step1, step2, step3 di BookingController
- [ ] Buat view booking/step1.blade.php (pilih jadwal)
- [ ] Buat view booking/step2.blade.php (pilih kursi)
- [ ] Buat view booking/step3.blade.php (konfirmasi)
- [ ] Buat view user/riwayat.blade.php untuk history
- [ ] Setup route untuk booking wizard
- [ ] Commit: "Multi-step booking wizard implementation"

## 7. Panel Admin - Dashboard & Layout
- [ ] Buat AdminMiddleware: `php artisan make:middleware AdminMiddleware`
- [ ] Buat AdminController: `php artisan make:controller Admin/AdminController`
- [ ] Implementasi dashboard method di AdminController
- [ ] Buat layout resources/views/layouts/app.blade.php dengan sidebar admin
- [ ] Buat view admin/dashboard.blade.php
- [ ] Setup route admin dengan middleware
- [ ] Commit: "Admin dashboard and layout setup"

## 8. CRUD Admin - Rute
- [ ] Tambah method index, create, store, edit, update, destroy di AdminController untuk rute
- [ ] Buat view admin/rute/index.blade.php
- [ ] Buat view admin/rute/create.blade.php dan edit.blade.php
- [ ] Setup route CRUD rute
- [ ] Commit: "Admin CRUD for routes (rute)"

## 9. CRUD Admin - Mobil (Kendaraan)
- [ ] Tambah method CRUD di AdminController untuk mobil
- [ ] Buat view admin/mobil/index.blade.php, create.blade.php, edit.blade.php
- [ ] Setup route CRUD mobil
- [ ] Commit: "Admin CRUD for vehicles (mobil)"

## 10. CRUD Admin - Supir
- [ ] Tambah method CRUD di AdminController untuk supir
- [ ] Buat view admin/supir/index.blade.php, create.blade.php, edit.blade.php
- [ ] Setup route CRUD supir
- [ ] Commit: "Admin CRUD for drivers (supir)"

## 11. CRUD Admin - Jadwal
- [ ] Tambah method CRUD di AdminController untuk jadwal
- [ ] Buat view admin/jadwals/index.blade.php, create.blade.php, edit.blade.php
- [ ] Setup route CRUD jadwal
- [ ] Commit: "Admin CRUD for schedules (jadwal)"

## 12. CRUD Admin - Pelanggan (Users)
- [ ] Tambah method CRUD di AdminController untuk users
- [ ] Buat view admin/pelanggan/index.blade.php, create.blade.php, edit.blade.php
- [ ] Setup route CRUD pelanggan
- [ ] Commit: "Admin CRUD for customers (pelanggan)"

## 13. Manajemen Pemesanan Admin
- [ ] Tambah method untuk bookings di AdminController
- [ ] Buat view admin/bookings.blade.php
- [ ] Implementasi update status booking
- [ ] Setup route untuk bookings
- [ ] Commit: "Admin booking management"

## 14. Laporan & Statistik
- [ ] Tambah method laporan di AdminController
- [ ] Buat view admin/laporan.blade.php
- [ ] Implementasi query statistik
- [ ] Setup route laporan
- [ ] Commit: "Reports and statistics"

## 15. Integrasi Email & Notifikasi
- [ ] Buat Mail class: `php artisan make:mail BookingStatusUpdated`
- [ ] Konfigurasi mail di .env
- [ ] Implementasi notifikasi email di BookingController
- [ ] Perbaiki WhatsappService untuk notifikasi
- [ ] Commit: "Email and WhatsApp notifications"

## 16. Testing Suite
- [ ] Buat unit test untuk model: `php artisan make:test Unit/Models/UserTest`
- [ ] Buat unit test untuk service: `php artisan make:test Unit/Services/OtpServiceTest`
- [ ] Buat feature test untuk controller: `php artisan make:test Feature/AuthControllerTest`
- [ ] Buat factory: `php artisan make:factory UserFactory`
- [ ] Jalankan test: `php artisan test`
- [ ] Commit: "Comprehensive test suite"

## 17. Command & Optimization
- [ ] Buat command: `php artisan make:command CancelExpiredBookings`
- [ ] Implementasi logic cancel booking expired
- [ ] Optimasi query dengan eager loading
- [ ] Setup caching jika perlu
- [ ] Commit: "Commands and performance optimization"

## 18. Seeders & Data Awal
- [ ] Buat seeder: `php artisan make:seeder AdminUserSeeder`
- [ ] Buat seeder untuk data awal (rute, mobil, dll)
- [ ] Jalankan seeder: `php artisan db:seed`
- [ ] Commit: "Database seeders for initial data"

## 19. Final Setup & Deployment
- [ ] Setup .env production
- [ ] Konfigurasi Vite untuk production
- [ ] Build asset: `npm run build`
- [ ] Test end-to-end
- [ ] Commit: "Final setup and deployment ready"

## Catatan Penting
- Setiap commit harus menyertakan pesan yang jelas
- Pastikan migrasi dan test pass sebelum commit
- Gunakan git branch untuk setiap fitur
- Dokumentasi di README.md
- Untuk controller, pastikan setiap controller di-commit setelah implementasi lengkap
