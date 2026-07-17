<p align="center">
  <img src="public/logo.png" width="100" alt="Node Center Logo">
</p>

<h1 align="center">Node Center - Application Monitoring Dashboard</h1>

<p align="center">
  Sebuah dashboard pemantauan aplikasi (Application Monitoring) berbasis Laravel 11 dengan antarmuka <strong>Neo-Brutalist</strong> modern. Dibuat khusus untuk mengawasi berbagai aplikasi Laravel secara terpusat, langsung (real-time), dan aman.
</p>

---

## ✨ Fitur Utama

- **Real-time Metrics (Push & Pull):** Menerima data secara instan dari aplikasi agen, sekaligus melakukan *Active Ping* ke server target untuk membedakan antara *Server Offline* dan *Agent Stale* (Cron Macet).
- **Historical Analytics (Chart.js):** Lacak penggunaan CPU, Memory, DB Latency, dan Cache Latency selama 24 jam terakhir dalam grafik interaktif yang indah.
- **Environment Security Scanner:** Secara otomatis memindai konfigurasi `.env` agen target untuk mendeteksi kerentanan kritis (seperti `APP_DEBUG=true` di tahap *Production* atau kunci rahasia yang kosong).
- **Slow Query & Error Catcher:** Menangkap log *Slow Queries* dan *Exceptions* dari file `laravel.log` agen target, menampilkannya dalam format *modal* yang mudah dibaca.
- **Queue & Schedule Tracker:** Memantau status *Pending Jobs*, *Failed Jobs*, dan *Scheduled Tasks (Cron)* dari jarak jauh.
- **Global Dark Mode:** Tema *Dark Mode* elegan dengan penyimpanan preferensi di *browser* (LocalStorage) menggunakan Alpine.js.

---

## 🚀 Instalasi Dashboard (Node Center)

1. **Clone & Install Dependencies**
   ```bash
   git clone <repo-url> node-center
   cd node-center
   composer install
   npm install && npm run build
   ```

2. **Konfigurasi Lingkungan (.env)**
   Salin file `.env.example` ke `.env` dan atur koneksi database Anda.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Migrasi Database**
   ```bash
   php artisan migrate:fresh
   ```

4. **Jalankan Scheduler & Server**
   Untuk mengaktifkan fitur *Active Ping*, jalankan Laravel Scheduler di server Dashboard:
   ```bash
   # Di background terminal atau crontab server
   php artisan schedule:work
   
   # Jalankan server
   php artisan serve
   ```

---

## 🔌 Cara Menyambungkan Aplikasi Target (Klien)

Untuk menyambungkan aplikasi Laravel Anda (contoh: SISMA-AKA) ke **Node Center**, ikuti langkah berikut:

### 1. Daftarkan Aplikasi di Node Center
1. Login ke **Node Center**.
2. Masuk ke menu **My Apps** > **Register App**.
3. Masukkan Nama (Misal: "SISMA-AKA") dan URL Root aplikasi (Misal: `http://127.0.0.1:8001`).
4. Klik simpan. Anda akan mendapatkan **API Token** rahasia.

### 2. Konfigurasi Agen (Klien)
Pada aplikasi target (Klien), tambahkan baris berikut di akhir file `.env` Anda:
```env
# Konfigurasi Dashboard Monitor API
DASHBOARD_MONITOR_URL="http://127.0.0.1:8000"
DASHBOARD_API_TOKEN="API_TOKEN_YANG_DIDAPAT_DARI_NODE_CENTER"
```

### 3. Pasang Drop-in Command
Salin file `app/Console/Commands/SendDashboardMetrics.php` ke dalam direktori aplikasi agen Anda. 

Lalu, daftarkan *command* tersebut di `routes/console.php` klien agar berjalan secara otomatis setiap menit:
```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('dashboard:send-metrics')->everyMinute();
```
*(Pastikan Anda telah menjalankan `php artisan schedule:work` atau cron job dasar Laravel pada server klien).*

---

## 🛡️ Membaca Status Monitoring

Pada kartu aplikasi di dashboard, Anda akan melihat berbagai indikator:
- 🟢 **ONLINE**: Server hidup dan *cron* berjalan normal.
- 🟡 **AGENT STALE**: Server hidup, tetapi *cron* mati (tidak ada metrik terkirim dalam 5 menit terakhir).
- 🔴 **SERVER OFFLINE**: Server target sama sekali tidak bisa dijangkau oleh *Ping*.
- 🛡️ **WARNING**: Ditemukan kerentanan di `.env` target. Klik logo tameng merah untuk melihat detailnya.

---
**Node Center** &copy; 2026. *Built for professionals.*
